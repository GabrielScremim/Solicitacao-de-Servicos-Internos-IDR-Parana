<?php

class AcompanharDAO
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function buscarSSI($chapaUsuario)
    {
        $comando = $this->pdo->prepare("SELECT r.ssi_id,(SELECT nome FROM usuario WHERE chapa = r.usuario_chapa) AS nome_responsavel_tecnico, s.nome_servico AS tipo_servico, DATE_FORMAT(STR_TO_DATE(r.data_registro, '%Y-%m-%d %H:%i:%s'), '%d/%m/%Y %H:%i:%s') as data_formatada
            FROM 
                ssi r
            LEFT JOIN 
                servico s ON r.servico_servico_id = s.servico_id
            WHERE 
                r.andamento <> 5 AND
                r.chapa_solicitante = :chapaUsuario
            ORDER BY 
                r.ssi_id
        ");
        $comando->bindParam(":chapaUsuario", $chapaUsuario);
        $comando->execute();
        return $comando->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarTodasSSI()
    {
        $comando = $this->pdo->prepare("SELECT r.ssi_id,r.nome_solicitante,(SELECT nome FROM usuario WHERE chapa = r.usuario_chapa) AS nome_responsavel_tecnico, s.nome_servico AS tipo_servico, DATE_FORMAT(STR_TO_DATE(r.data_registro, '%Y-%m-%d %H:%i:%s'), '%d/%m/%Y %H:%i:%s') as data_formatada
            FROM 
                ssi r
            LEFT JOIN 
                servico s ON r.servico_servico_id	 = s.servico_id
            WHERE 
                r.andamento <> 5
            ORDER BY 
                r.ssi_id
        ");
        $comando->execute();
        return $comando->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarSSITecnico($chapaUsuario)
    {
        $comando = $this->pdo->prepare("SELECT r.ssi_id,r.nome_solicitante, s.nome_servico AS tipo_servico, DATE_FORMAT(STR_TO_DATE(r.data_registro, '%Y-%m-%d %H:%i:%s'), '%d/%m/%Y %H:%i:%s') as data_formatada
            FROM 
                ssi r
            LEFT JOIN 
                servico s ON r.servico_servico_id = s.servico_id
            WHERE 
                r.andamento <> 5 AND
                r.usuario_chapa = :fk_tecnico
            ORDER BY 
                r.ssi_id
        ");

        $comando->bindParam(":fk_tecnico", $chapaUsuario);
        $comando->execute();

        return $comando->fetchAll(PDO::FETCH_ASSOC);
    }

    public function acompanhar($id)
    {
        $comando = $this->pdo->prepare("SELECT 
            r.ssi_id,
            r.nome_solicitante,
            s.nome_servico AS tipo_servico,
            r.desc_problema,
            DATE_FORMAT(STR_TO_DATE(r.data_registro, '%Y-%m-%d %H:%i:%s'), '%d/%m/%Y %H:%i:%s') as data_formatada,
            r.andamento
        FROM 
            ssi r
        LEFT JOIN 
            servico s ON r.servico_servico_id = s.servico_id
        WHERE 
            r.ssi_id = :id
    ");
        $comando->bindValue(":id", $id);
        $comando->execute();
        return $comando->fetchAll(PDO::FETCH_ASSOC);
    }

    public function atualizarSSI($data_hora_formatada, $descricaoServico, $id, $chapa_tecnico)
    {
        $comando = $this->pdo->prepare("INSERT INTO historico(data_atualizacao, descricao_atualizacao, ssi_ssi_id , usuario_chapa ) VALUES(:data_atualizacao, :descricao, :fk_id_ssi, :fk_id_tecnico)");

        $comando->bindValue(":data_atualizacao", $data_hora_formatada);
        $comando->bindValue(":descricao", $descricaoServico);
        $comando->bindValue(":fk_id_ssi", $id);
        $comando->bindValue(":fk_id_tecnico", $chapa_tecnico);
        $comando->execute();
    }

    public function atualizarSSIPeca($data_hora_formatada, $descricaoPeca, $valorPeca, $id, $chapa_tecnico)
    {
        $comando = $this->pdo->prepare("INSERT INTO peca(data_peca, descricao, valor, ssi_ssi_id, usuario_chapa) VALUES(:data_peca, :descricao, :valor, :fk_id_registro, :fk_id_tecnico)");

        $comando->bindValue(":data_peca", $data_hora_formatada);
        $comando->bindValue(":descricao", $descricaoPeca);
        $comando->bindValue(":valor", $valorPeca);
        $comando->bindValue(":fk_id_registro", $id);
        $comando->bindValue(":fk_id_tecnico", $chapa_tecnico);
        $comando->execute();
    }

    public function atualizarAndamento($id)
    {
        $comando = $this->pdo->prepare("UPDATE ssi SET andamento = 3 WHERE ssi_id = :id");
        $comando->bindValue(":id", $id);
        $comando->execute();
    }

    public function buscarAtualizacao($id)
    {
        $comando = $this->pdo->prepare("SELECT data_atualizacao, descricao_atualizacao FROM historico WHERE ssi_ssi_id = :id_ssi");
        $comando->bindValue(":id_ssi", $id);
        $comando->execute();
        return $comando->fetchAll(PDO::FETCH_ASSOC);
    }

    public function finalizar($id, $data_hora_formatada)
    {
        $comando = $this->pdo->prepare("UPDATE ssi SET andamento = 5, data_finalizacao = :data_finalizacao WHERE ssi_id = :id");
        $comando->bindValue(":id", $id);
        $comando->bindValue(":data_finalizacao", $data_hora_formatada);
        $comando->execute();
    }
}
?>
