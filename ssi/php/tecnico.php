<?php
$page = 'registro-tecnico';
$titulo = "Registrar Técnico";
include '../php/includes/header.inc.php';
include '../php/includes/menu.inc.php';
require_once '../classes/controller/tecnico-cont.php';
require '../classes/controller/verificar_session.php';
$tecnico = new Tecnico("banco_ssi", "localhost", "root", "");

// verifica se o usuario possui acesso para está página
if ($_SESSION['tipo_usuario'] != 'gerente') {
    header("location: index.php");
    exit();
}

//exclui o técnico, atualizando o mostrar para 0
if (isset($_GET['chapa_excluir'])) {
    $chapa = addslashes($_GET['chapa_excluir']);
    $tecnico->ExcluirTecnico($chapa);
    header("location: tecnico.php");
    exit();
}


// Verifica se o formulário foi enviado
if (isset($_POST['chapa'])) {
    if (isset($_GET['chapa']) && !empty($_GET['chapa'])) {
        $chapa = addslashes($_GET['chapa']);
        $nome_tecnico = addslashes($_POST['nome_tecnico']);
        $area_tecnico = addslashes($_POST['area_tecnico']);

        if (!empty($chapa) && !empty($nome_tecnico) && !empty($area_tecnico)) {
            $tecnico->Editar($nome_tecnico, $chapa, $area_tecnico);
            header("location: tecnico.php");
            exit();
        } else {
            $error = "Preencha todos os CAMPOS!";
        }
    } else {
        $chapa = addslashes($_POST['chapa']);
        $nome_tecnico = addslashes($_POST['nome_tecnico']);
        $area_tecnico = addslashes($_POST['area_tecnico']);

        if (!empty($chapa) && !empty($nome_tecnico) && !empty($area_tecnico)) {
            if (!$tecnico->CadastrarTecnico($chapa, $nome_tecnico, $area_tecnico)) {
                $error = "Tecnico já Cadastrado!";
            }
        } else {
            $error ="Preencha TODOS os campos!";
        }
    }
}

// Busca os dados do técnico selecionado para editar
if (isset($_GET['chapa'])) {
    $chapa = addslashes($_GET['chapa']);
    $resultado = $tecnico->BuscarEditar($chapa);
}
?>

<div class="container-flunome" id="main">
    <form method="POST">
        <h1>Registrar Técnico</h1>
        <?php
         if (isset($error) && !empty($error)) {
        echo'<p>'.$error.'</p>';
         }
        ?>
        <div class="row form">
            <div class="col-lg-1 col-md-3 col-sm-5">
                <label for="chapa">Chapa</label>
                <input required type="text" name="chapa" id="chapa" maxlength="6" class="form-control" value="<?php if (isset($resultado)) {echo $resultado['chapa'];} ?>">
            </div>
            <div class="col-lg-2 col-md-6 col-sm-6">
                <label for="nome_tecnico">Nome</label>
                <input required type="text" name="nome_tecnico" id="nome_tecnico" class="form-control" value="<?php if (isset($resultado)) {echo $resultado['nome'];} ?>">
            </div>
            <div class="col-lg-2 col-md-1 col-sm">
                <label for="area_tecnico">Área do Técnico</label>
                <select name="area_tecnico" class="form-control">
                    <option value="<?php echo isset($resultado) ? $resultado['area_tecnico'] : ''; ?>"><?php echo isset($resultado) ? $resultado['area_tecnico'] : 'Selecione uma área'; ?></option>
                    <option value="TI">Informática</option>
                    <option value="GEM">Engenharia e Manutenção</option>
                    <option value="AEP">Experimentação e Produção</option>
                    <option value="CDT">Telefonia e Segurança</option>
                </select>
            </div>
            <div class="col-lg-1 col-md-1 col-sm py-2 my-2">
                <button type="submit" value="cadastrar" class="btn btn-success my-3">Salvar</button>
            </div>
        </div>
    </form>

    <h3 class="text-center">Lista de Técnicos</h3>
    <table class="table table-borderless table-striped table-hover table-sm">
        <thead>
            <tr>
                <th class="col-1">Chapa</th>
                <th class="col-10">Nome</th>
                <th>Editar</th>
                <th>Apagar</th>
            </tr>
        </thead>
        <?php
        $dados = $tecnico->BuscarTecnico();
        if (count($dados) > 0) {
            for ($i = 0; $i < count($dados); $i++) {
                echo "<tr>";
                foreach ($dados[$i] as $k => $v) {
                    echo "<td>" . $v . "</td>";
                }
        ?>
                <td>
                    <a href="tecnico.php?chapa=<?php echo $dados[$i]['chapa']; ?>">
                        <button class="btn btn-secondary btn-sm">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                    </a>
                </td>
                <td>
                    <a href="tecnico.php?chapa_excluir=<?php echo $dados[$i]['chapa']; ?>">
                        <button class="btn btn-danger btn-sm">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </a>
                </td>
            <?php
                echo "</tr>";
            }

            ?>
        <?php
        }
        ?>
    </table>
</div>

<?php
require_once '../php/includes/footer.inc.php'
?>
</body>

</html>