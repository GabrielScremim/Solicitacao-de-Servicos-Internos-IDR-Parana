<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            /* Largura do formulário */
        }
    </style>
</head>

<body>
    <?php
    require_once '../classes/controller/ssi-usuario-cont.class.php';
    $usuario = new Usuario("banco_ssi", "localhost", "root", "");

    // Inicia a sessão
    session_start();

    // Verifica se o usuário já está autenticado, se sim, redireciona para a página home
    if (isset($_SESSION['nome'])) {
        header("Location: home.php");
        exit();
    }


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $chapa = addslashes($_POST['chapa']);
        $senha = addslashes($_POST['senha']);

        if ($usuario->AutenticarUser($chapa, $senha)) {
            $infos_user = $usuario->ObterInfosUser($chapa);
            $_SESSION['tipo_usuario'] = $infos_user['tipo_usuario'];
            $_SESSION['chapa'] = $infos_user['chapa'];
            $_SESSION['nome'] = $infos_user['nome'];

            header("Location: home.php");
            exit();
        } else {
            echo "Login incorreto. Tente novamente.";
        }
    }

    ?>

    <div class="card">
        <div class="card-header">
            <h2 class="text-center">Login</h2>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label for="chapa">Chapa:</label>
                    <input type="text" class="form-control" name="chapa" id="chapa" maxlength="6" required>
                </div>

                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input type="password" class="form-control" name="senha" id="senha" required>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Entrar</button>
                </div>
            </form>
            <div>
                <p>Não possui cadastro? <a href="cadastroUsuario.php">Clique aqui para se cadastrar</a></p>
            </div>
        </div>
    </div>

</body>

</html>