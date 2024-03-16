<?php

class Gerenciar
{
    private $pdo;
    public function __construct($dbname, $host, $user, $senha)
    {
        try {
            $this->pdo = new PDO("mysql:dbname=" . $dbname . ";host=" . $host, $user, $senha);
        } catch (PDOException $th) {
            echo "erro com banco de dados" . $th->getMessage();
            exit();
        } catch (PDOException $th) {
            echo "erro generico: " . $th->getMessage();
            exit();
        }
    }

    //Mostra os técnicos disponíveis para ser selecionado nos trabalhos
    public function BuscarTecnico()
    {
        $resultado = array();
        $comando = $this->pdo->query("SELECT chapa, nome FROM usuario WHERE tipo_usuario = 'tecnico' or tipo_usuario = 'gerente' and mostrar <> 0 ORDER BY nome");
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    }

    public function servicos()
    {
        $resultado = array();
        $comando = $this->pdo->query("SELECT servico_id, nome_servico FROM servico  WHERE mostrar <> 0 ORDER BY nome_servico");
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    }

    //Mostra as SSI que aguardam técnicos
    public function AguardandoTecnico()
    {
        $resultado = array();
        $comando = $this->pdo->query("SELECT ssi.ssi_id, nome_solicitante, ramal, desc_problema
            FROM ssi ssi
            LEFT JOIN servico s ON ssi.fk_servico_id = s.servico_id
            WHERE ssi.fk_usuario_chapa IS NULL AND andamento <> 5
            ORDER BY nome_solicitante
        ");

        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    }

    //Atribui um técnico a SSI
    public function AtribuirTecnico($id, $fk_responsavel_tecnico, $andamento, $fk_id_servico)
    {
        $comando = $this->pdo->prepare("UPDATE ssi SET fk_usuario_chapa = :fk_tecnico_chapa, fk_servico_id = :fk_servicos_id_servico, andamento = :a WHERE ssi_id = :id");
        $comando->bindParam(":id", $id);
        $comando->bindParam(":fk_tecnico_chapa", $fk_responsavel_tecnico);
        $comando->bindParam(":fk_servicos_id_servico", $fk_id_servico);
        $comando->bindValue(":a", $andamento);
        $comando->execute();
    }

    //Seleciona as SSI que já estão com técnico
    public function EmAndamento()
    {
        $resultado = array();
        $comando = $this->pdo->query("SELECT ssi.ssi_id, ssi.nome_solicitante, ssi.ramal, s.nome_servico AS id_tipo_servico, ssi.desc_problema, t.nome AS nome_responsavel_tecnico FROM ssi ssi
            LEFT JOIN servico s ON ssi.fk_servico_id	 = s.servico_id
            LEFT JOIN usuario t ON ssi.fk_usuario_chapa = t.chapa
            WHERE ssi.fk_usuario_chapa IS NOT NULL AND ssi.andamento <> 5
            ORDER BY ssi.nome_solicitante;");

        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    }

    //Finaliza a SSI atribuindo o valor do andamento = 5
    public function Finalizar($id, $data_hora_formatada)
    {
        $comando = $this->pdo->prepare("UPDATE ssi SET andamento = 5, data_finalizacao = :data_finalizacao WHERE ssi_id = :id");
        $comando->bindValue(":id", $id);
        $comando->bindValue(":data_finalizacao", $data_hora_formatada);
        $comando->execute();
    }

    public function Recusar($id, $data_hora_formatada)
    {
        $comando = $this->pdo->prepare("UPDATE ssi SET andamento = 5, data_finalizacao = :data_finalizacao WHERE ssi_id = :id");
        $comando->bindValue(":id", $id);
        $comando->bindValue(":data_finalizacao", $data_hora_formatada);
        $comando->execute();
    }
}
