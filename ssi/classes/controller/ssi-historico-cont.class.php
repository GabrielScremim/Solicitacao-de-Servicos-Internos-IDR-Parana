<?php

class Historico
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

    public function Historico($chapaUsuario)
    {
        $resultado = array();
        $comando = $this->pdo->prepare("SELECT nome_solicitante, desc_problema, ssi_id,(SELECT nome FROM usuario WHERE chapa = r.fk_usuario_chapa) AS nome_responsavel_tecnico,
        DATE_FORMAT(STR_TO_DATE(data_registro, '%Y-%m-%d %H:%i:%s'), '%d/%m/%Y %H:%i:%s') as data_formatada,DATE_FORMAT(STR_TO_DATE(data_finalizacao, '%Y-%m-%d %H:%i:%s'), '%d/%m/%Y %H:%i:%s') as data_finalizacao_formatada
        FROM ssi r
        WHERE andamento = 5 AND  r.chapa_solicitante = :chapaUsuario"
        );
        $comando->bindValue(":chapaUsuario", $chapaUsuario);
        $comando->execute();
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    }

    public function TodoHistorico()
    {
        $resultado = array();
        $comando = $this->pdo->query("SELECT nome_solicitante, desc_problema, ssi.ssi_id,(SELECT nome FROM usuario WHERE chapa = ssi.fk_usuario_chapa) AS nome_responsavel_tecnico,
        DATE_FORMAT(STR_TO_DATE(data_registro, '%Y-%m-%d %H:%i:%s'), '%d/%m/%Y %H:%i:%s') as data_formatada, DATE_FORMAT(STR_TO_DATE(data_finalizacao, '%Y-%m-%d %H:%i:%s'), '%d/%m/%Y %H:%i:%s') as data_finalizacao_formatada
        FROM ssi ssi
        WHERE andamento = 5"
        );
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    }


    public function TodoHistoricoTecnico($chapaUsuario)
    {
        $resultado = array();
        $comando = $this->pdo->prepare("SELECT nome_solicitante, desc_problema, ssi_id,
        DATE_FORMAT(STR_TO_DATE(data_registro, '%Y-%m-%d %H:%i:%s'), '%d/%m/%Y %H:%i:%s') as data_formatada,DATE_FORMAT(STR_TO_DATE(data_finalizacao, '%Y-%m-%d %H:%i:%s'), '%d/%m/%Y %H:%i:%s') as data_finalizacao_formatada
        FROM ssi ssi
        WHERE andamento = 5 AND ssi.fk_usuario_chapa = :fk_tecnico_chapa"
        );
        $comando->bindValue(":fk_tecnico_chapa", $chapaUsuario);
        $comando->execute();
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    }
}
