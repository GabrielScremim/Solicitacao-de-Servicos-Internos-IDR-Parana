<?php
class Tecnico{
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

    public function CadastrarTecnico($chapa, $nome_tecnico, $area_tecnico){
        //verificando se a chapa ou o nome ja existem
        $comando = $this->pdo->prepare("SELECT chapa FROM usuario WHERE chapa = :c OR nome = :n");
        $comando->bindValue(":c",$chapa);
        $comando->bindValue(":n",$nome_tecnico);
        $comando->execute();
        //caso ja exista o tecnico cadastrado, modifica o id = 1, para ser mostrado
        if($comando->rowCount() > 0){
            $comando = $this->pdo->prepare("UPDATE usuario SET mostrar = 1 WHERE chapa = :c OR nome = :n");
            $comando->bindValue(":c", $chapa);
            $comando->bindValue(":n", $nome_tecnico);
            $comando->execute();
            header("Location: tecnico.php");
            exit();
        // se não tiver uma chapa ou um nome igual no banco, insere os dados do tecnico
        }else{
            $comando = $this->pdo->prepare("INSERT INTO usuario (mostrar,chapa,nome, tipo_usuario, area_tecnico) VALUES (:i,:c,:n,:tp,:a)");
            $mostrar = 1;
            $tipo_usuario = "tecnico";
            $comando->bindValue(":i",$mostrar);
            $comando->bindValue(":c",$chapa);
            $comando->bindValue(":n",$nome_tecnico);
            $comando->bindValue(":tp",$tipo_usuario);
            $comando->bindValue(":a",$area_tecnico);
            $comando->execute();
            header("Location: tecnico.php");
            exit();
        }
    }

    //mostra todos os tecnicos ativos
    public function BuscarTecnico()
    {
        $resultado = array();
        $comando = $this->pdo->query("SELECT chapa,nome FROM usuario where mostrar = 1 AND tipo_usuario = 'tecnico' or tipo_usuario = 'gerente' ORDER BY nome");
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    }

    //Excluir tecnico, modificando o id para 0
    public function ExcluirTecnico($chapa){
        $comando = $this->pdo->prepare("UPDATE usuario SET mostrar = 0 WHERE chapa = :c");
        $comando->bindValue(":c",$chapa);
        $comando->execute();
    }

    // Identificar os dados quer serão atualizados através da chapa e mostrar
    public function BuscarEditar($chapa){
        $resultado = array();
        $comando = $this->pdo->prepare("SELECT nome,chapa,area_tecnico FROM usuario WHERE chapa = :c");
        $comando->bindValue(":c",$chapa);
        $comando->execute();
        $resultado = $comando->fetch(PDO::FETCH_ASSOC);
        return $resultado;
    }

    //Atualizar os dados
    public function Editar($nome_tecnico, $chapa, $area_tecnico){
        $comando = $this->pdo->prepare("UPDATE usuario SET nome = :n, chapa = :c, area_tecnico = :a WHERE chapa = :c");
        $comando->bindValue(":n",$nome_tecnico);
        $comando->bindValue(":c",$chapa);
        $comando->bindValue(":a",$area_tecnico);
        $comando->execute();
    }
}

?>