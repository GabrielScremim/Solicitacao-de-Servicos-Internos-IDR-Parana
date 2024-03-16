<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>

<body>

    <?php
    require_once '../classes/controller/ssi-usuario-cont.class.php';
    $usuario = new Usuario("banco_ssi", "localhost", "root", "");
    if (isset($_POST['chapa'])) {
        $chapa = addslashes($_POST['chapa']);
        $nome = addslashes($_POST['nome']);
        $ramal = addslashes($_POST['ramal']);
        $senha = addslashes($_POST['senha']);
        $usuario->CadastroUser($chapa,$nome,$ramal,$senha,$gerencia);
    }

    ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">Cadastro de Usuário</h2>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="form-group">
                                <label for="chapa">Chapa:</label>
                                <input type="text" class="form-control" name="chapa" id="chapa" required>
                            </div>

                            <div class="form-group">
                                <label for="nome">Nome:</label>
                                <input type="text" class="form-control" name="nome" id="nome" required>
                            </div>

                            <div class="form-group">
                                <label for="ramal">Ramal:</label>
                                <input type="text" class="form-control" name="ramal" id="ramal" required>
                            </div>

                            <div class="form-group">
                                <label for="senha">Senha:</label>
                                <input type="password" class="form-control" name="senha" id="senha" required>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">Cadastrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>