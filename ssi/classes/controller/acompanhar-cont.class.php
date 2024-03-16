<?php

class Acompanhar
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
    //Buscar as SSI, do usuario logado, que estão ativas no momento para mostrar na tela de acompanhamento
    public function BuscarSSI($chapaUsuario)
    {
        $BuscarSSI = array();
        $comando = $this->pdo->prepare("SELECT ssi.ssi_id,(SELECT nome FROM usuario WHERE chapa = ssi.fk_usuario_chapa) AS nome_responsavel_tecnico, s.nome_servico AS tipo_servico, DATE_FORMAT(STR_TO_DATE(ssi.data_registro, '%Y-%m-%d %H:%i:%s'), '%d/%m/%Y %H:%i:%s') as data_formatada
            FROM 
                ssi ssi
            LEFT JOIN 
                servico s ON ssi.fk_servico_id = s.servico_id
            WHERE 
                ssi.andamento <> 5 AND
                ssi.chapa_solicitante = :chapaUsuario
            ORDER BY 
                ssi.ssi_id
        ");
        $comando->bindParam(":chapaUsuario", $chapaUsuario);
        $comando->execute();

        $BuscarSSI = $comando->fetchAll(PDO::FETCH_ASSOC);
        return $BuscarSSI;
    }

    //Seleciona todas as SSI ativas no momento
    public function BuscarTodasSSI()
    {
        $BuscarTodasSSI = array();
        $comando = $this->pdo->prepare("SELECT ssi.ssi_id,ssi.nome_solicitante,(SELECT nome FROM usuario WHERE chapa = ssi.fk_usuario_chapa) AS nome_responsavel_tecnico, s.nome_servico AS tipo_servico, DATE_FORMAT(STR_TO_DATE(ssi.data_registro, '%Y-%m-%d %H:%i:%s'), '%d/%m/%Y %H:%i:%s') as data_formatada
            FROM 
                ssi ssi
            LEFT JOIN 
                servico s ON ssi.fk_servico_id= s.servico_id
            WHERE 
                ssi.andamento <> 5
            ORDER BY 
                ssi.ssi_id
        ");
        $comando->execute();
        $BuscarTodasSSI = $comando->fetchAll(PDO::FETCH_ASSOC);
        return $BuscarTodasSSI;
    }

    //Seleciona todas as SSI ativas no momento, que o tecnico logado é responsável
    public function BuscarSSITecnico($chapaUsuario)
    {
        $BuscarSSITecnico = array();
        $comando = $this->pdo->prepare("SELECT ssi.ssi_id, ssi.nome_solicitante, s.nome_servico AS tipo_servico, DATE_FORMAT(STR_TO_DATE(ssi.data_registro, '%Y-%m-%d %H:%i:%s'), '%d/%m/%Y %H:%i:%s') as data_formatada
            FROM 
                ssi ssi
            LEFT JOIN 
                servico s ON ssi.fk_servico_id = s.servico_id
            WHERE 
                ssi.andamento <> 5 AND
                ssi.fk_usuario_chapa = :fk_tecnico
            ORDER BY 
                ssi.ssi_id
        ");

        $comando->bindParam(":fk_tecnico", $chapaUsuario);
        $comando->execute();

        $BuscarSSITecnico = $comando->fetchAll(PDO::FETCH_ASSOC);
        return $BuscarSSITecnico;
    }

    //Buscar as informações da SSI que o usuario deseja ver o andamento
    public function Acompanhar($id)
    {
        $Acompanhar = array();
        $comando = $this->pdo->prepare("SELECT 
                ssi.ssi_id,
                ssi.nome_solicitante,
                s.nome_servico AS tipo_servico,
                ssi.desc_problema,
                ssi.andamento,
                DATE_FORMAT(STR_TO_DATE(ssi.data_registro, '%Y-%m-%d %H:%i:%s'), '%d/%m/%Y %H:%i:%s') as data_formatada,
                u.nome AS nome_tecnico
            FROM 
                ssi ssi
                LEFT JOIN usuario u ON ssi.fk_usuario_chapa = u.chapa
                LEFT JOIN servico s ON ssi.fk_servico_id = s.servico_id
            WHERE 
                ssi.ssi_id = :id
        ");
        $comando->bindValue(":id", $id);
        $comando->execute();
        $Acompanhar = $comando->fetchAll(PDO::FETCH_ASSOC);
        return $Acompanhar;
    }


    //Busca as informações de todas SSI em andamento
    public function AtualizarSSI($data_hora_formatada, $descricaoServico, $id, $chapa_tecnico)
    {
        $comando = $this->pdo->prepare("INSERT INTO historico(data_atualizacao, descricao_atualizacao, ssi_ssi_id , usuario_chapa ) VALUES(:data_atualizacao, :descricao, :fk_id_ssi, :fk_id_tecnico)");

        $comando->bindValue(":data_atualizacao", $data_hora_formatada);
        $comando->bindValue(":descricao", $descricaoServico);
        $comando->bindValue(":fk_id_ssi", $id);
        $comando->bindValue(":fk_id_tecnico", $chapa_tecnico);
        $comando->execute();
    }

    public function AtualizarSSIPeca($data_hora_formatada, $descricaoPeca, $valorPeca, $id, $chapa_tecnico)
    {
        $comando = $this->pdo->prepare("INSERT INTO peca(data_peca, descricao, valor, fk_ssi_id, fk_usuario_chapa) VALUES(:data_peca, :descricao, :valor, :fk_id_registro, :fk_id_tecnico)");

        $comando->bindValue(":data_peca", $data_hora_formatada);
        $comando->bindValue(":descricao", $descricaoPeca);
        $comando->bindValue(":valor", $valorPeca);
        $comando->bindValue(":fk_id_registro", $id);
        $comando->bindValue(":fk_id_tecnico", $chapa_tecnico);
        $comando->execute();
    }

    //Atualiza o andamento após ter feito alguma atualização na SSI
    public function AtualizarAndamento($id)
    {
        $comando = $this->pdo->prepare("UPDATE ssi SET andamento = 3 WHERE ssi_id = :id");
        $comando->bindValue(":id", $id);
        $comando->execute();
    }

    //Busca todas as atualizações
    public function BuscarAtualizacao($id)
    {
        $BuscarAtualizacao = array();
        $comando = $this->pdo->prepare("SELECT data_atualizacao, descricao_atualizacao FROM historico WHERE ssi_ssi_id = :id_ssi");
        $comando->bindValue(":id_ssi", $id);
        $comando->execute();
        $BuscarAtualizacao = $comando->fetchAll(PDO::FETCH_ASSOC);
        return $BuscarAtualizacao;
    }

    public function BuscarAtualizacaoPeca($id)
    {
        $BuscarAtualizacao = array();
        $comando = $this->pdo->prepare("SELECT data_peca, descricao, valor FROM peca WHERE fk_ssi_id = :id_ssi");
        $comando->bindValue(":id_ssi", $id);
        $comando->execute();
        $BuscarAtualizacao = $comando->fetchAll(PDO::FETCH_ASSOC);
        return $BuscarAtualizacao;
    }

    // Finalizar SSI
    public function Finalizar($id, $data_hora_formatada)
    {
        $comando = $this->pdo->prepare("UPDATE ssi SET andamento = 5, data_finalizacao = :data_finalizacao WHERE ssi_id = :id");
        $comando->bindValue(":id", $id);
        $comando->bindValue(":data_finalizacao", $data_hora_formatada);
        $comando->execute();
        header("location: acompanhar.php");
        exit();
    }
}
