<?php
$titulo = "Registrar SSI";
$page = "registrar-ssi";
include '../php/includes/header.inc.php';
include '../php/includes/menu.inc.php';
require_once '../classes/controller/registrar-ssi-cont.class.php';
require '../classes/controller/verificar_session.php';
$registrar = new Registrar("banco_ssi", "localhost", "root", "");
// Define o fuso horário para Brasília
date_default_timezone_set('America/Sao_Paulo');
// Obtendo a data e hora atual de Brasília
$data_hora_registro = new DateTime();
// Formata a data e hora
$data_hora_formatada = $data_hora_registro->format('Y-m-d H:i:s');

if (isset($_POST['nome'])) {
    $nome = addslashes($_POST['nome']);
    $chapa = addslashes($_POST['chapa']);
    $centro_custo = addslashes($_POST['centro_custo']);
    $ramal = addslashes($_POST['ramal']);
    $pat_equipamento = addslashes($_POST['pat_equipamento']);
    $andamento = '1';
    $id_servico = addslashes($_POST['id_servico']);
    $desc_problema = addslashes($_POST['desc_problema']);

    if (!empty($nome) && ($chapa) && ($centro_custo) && ($ramal)) {

        $registrar->Cadastrar_SSI($nome, $chapa, $centro_custo, $ramal, $pat_equipamento, $data_hora_formatada, $andamento, $desc_problema);
    }
}
?>

<div class="container-fluid" id="main">
    <form method="POST">
        <div class="form-group">
            <h3>Dados</h3>
            <div class="row">
                <div class="col-lg-3">
                    <label for="nome">Nome</label>
                    <input required type="text" name="nome" id="nome" class="form-control">
                </div>
                <a href=""></a>
                <div class="col-lg-1">
                    <label for="chapa">Chapa</label>
                    <input required type="text" name="chapa" id="chapa" maxlength="6" class="form-control chapa">
                </div>

                <div class="col-lg-1">
                    <label for="ramal">Ramal</label>
                    <input required type="text" name="ramal" id="ramal" maxlength="4" class="form-control">
                </div>

                <div class="col-lg-2">
                    <label for="centro_custo">Centro de Custo</label>
                    <input required type="text" name="centro_custo" id="centro_custo" class="form-control">
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12" id="PatrimonioContainer">
                    <label for="pat_equipamento">Patrimônio do Equipamento</label>
                    <input type="text" name="pat_equipamento" id="pat_equipamento" class="form-control patrimonio">
                </div>
            </div>
        </div>
        <div class="form-group my-3">
            <label for="desc_problema">Descrição do Problema</label>
            <textarea required class="form-control" type="text" name="desc_problema" id="desc_problema"></textarea>
        </div>
        <button class="btn btn-success" type="submit" value="cadastrar">Registrar</button>
    </form>
</div>

<?php
require_once '../php/includes/footer.inc.php'
?>
</body>

</html>