<?php

class Usuario
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

    public function CadastroUser($chapa, $nome, $ramal, $senha, $gerencia)
    {
        // Gerar o hash da senha
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $comando = $this->pdo->prepare("INSERT INTO usuario (chapa, nome, ramal, senha) VALUES (:chapa, :nome, :ramal, :senha)");
        $comando->bindValue(":chapa", $chapa);
        $comando->bindValue(":nome", $nome);
        $comando->bindValue(":ramal", $ramal);
        $comando->bindValue(":senha", $senhaHash);
        $comando->execute();

        // Redirecionar para a página inicial após o cadastro
        header("Location: index.php");
        exit();
    }

    public function AutenticarUser($chapa, $senha)
    {
        $comando = $this->pdo->prepare("SELECT chapa, senha FROM usuario WHERE chapa = :chapa");
        $comando->bindValue(":chapa", $chapa);
        $comando->execute();

        $usuario = $comando->fetch(PDO::FETCH_ASSOC);

        // Verificar se o usuário foi encontrado e a senha está correta
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            return true;
        } else {
            return false;
        }
    }

    public function ObterInfosUser($chapa){
        $comando = $this->pdo->prepare("SELECT * FROM usuario WHERE chapa = :chapa");
        $comando->bindValue(":chapa",$chapa);
        $comando->execute();

        $resultado = $comando->fetch(PDO::FETCH_ASSOC);
        return $resultado;
    }
}
