<?php

class Servico{
    private $pdo;
    public function __construct($dbname, $host,$user,$senha){
        try {
            $this->pdo = new PDO ("mysql:dbname=".$dbname.";host=".$host,$user,$senha);
        } catch (PDOException $th) {
            echo "erro com banco de dados".$th->getMessage();
            exit();
        }
        catch (PDOException $th) {
        echo"erro generico: ".$th->getMessage();
        exit();
        }
    }
    
    //Mostrar os servicos cadastrados na tela de serviço
    public function BuscarServico()
    {
        $resultado = array();
        $comando = $this->pdo->query("SELECT servico_id , nome_servico FROM servico WHERE mostrar <> 0 ORDER BY nome_servico");
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    }

    //Cadastrar servico
    public function CadastrarServico($nome_servico, $area_servico){
        $comando = $this->pdo->prepare("SELECT servico_id FROM servico WHERE nome_servico = :n");
        $comando->bindValue(":n",$nome_servico);
        $comando->execute();
        //Se o serviço ja estiver cadastrado, mas não estiver sendo exibido, mude o id mostrar para 1
        if($comando->rowCount() > 0){
            $comando = $this->pdo->prepare("UPDATE servico SET mostrar = 1 WHERE nome_servico = :n");
            $comando->bindValue(":n", $nome_servico);
            $comando->execute();
            header("Location: servicos.php");
            exit();
        
        //Caso não esteja cadastrado, adicione um novo servciço
        }else{
            $comando = $this->pdo->prepare("INSERT INTO servico (mostrar,nome_servico,area_servico) VALUES(:i, :n, :a)");
            $mostrar = 1;
            $comando->bindValue(":i",$mostrar);
            $comando->bindValue(":n",$nome_servico);
            $comando->bindValue(":a",$area_servico);
            $comando->execute();
            header("Location: servicos.php");
            exit();
        }
    }

    //Exclui o serviço mudando o id mostrar para 0
    public function ExcluirServico($servico_id){
        $comando = $this->pdo->prepare("UPDATE servico SET mostrar = 0 WHERE servico_id = :id");
        $comando->bindValue(":id", $servico_id);
        $comando->execute();
    }

    //Busca as informações do serviço que será modificado
    public function BuscarEditar($id_servico){
        $resultado = array();
        $comando = $this->pdo->prepare("SELECT * FROM servico WHERE servico_id = :id");
        $comando->bindValue(":id",$id_servico);
        $comando->execute();
        $resultado = $comando->fetch(PDO::FETCH_ASSOC);
        return $resultado;
    }

    //Edita o serviço através do id selecionado
    public function Editar( $id, $nome_servico, $area_servico){
        $comando = $this->pdo->prepare("UPDATE servico SET nome_servico = :n , area_servico = :a WHERE servico_id = :id");
        $comando->bindValue(":id",$id);
        $comando->bindValue(":n",$nome_servico);
        $comando->bindValue(":a",$area_servico);
        $comando->execute();
    }
}