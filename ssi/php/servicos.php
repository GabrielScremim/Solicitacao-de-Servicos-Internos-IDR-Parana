<?php
$titulo = "Registrar Serviço";
$page = 'tipos-servico';
include '../php/includes/header.inc.php';
include '../php/includes/menu.inc.php';
require_once '../classes/controller/servico-cont.class.php';
require '../classes/controller/verificar_session.php';
$servico = new Servico("banco_ssi", "localhost", "root", "");

// verifica se o usuario possui acesso para está página
if ($_SESSION['tipo_usuario'] != 'gerente') {
    header("location: index.php");
    exit();
}

if (isset($_GET['id_servico_excluir'])) {
    $servico_id = addslashes($_GET['id_servico_excluir']);
    $servico->ExcluirServico($servico_id);
    header("location: servicos.php");
    exit();
}

if (isset($_POST['id_servico'])) {
    if (isset($_GET['id_servico']) && !empty($_GET['id_servico'])) {
        $id = addslashes($_GET['id_servico']);
        $nome_servico = addslashes($_POST['nome_servico']);
        $area_servico = addslashes($_POST['area_servico']);

        if (!empty($id)  && !empty($nome_servico) && !empty($area_servico)) {
            $servico->Editar($id, $nome_servico, $area_servico);
            header("location: servicos.php");
            exit();
        } else {
            echo "Preencha todos os campos";
        }
    } else {
        $nome_servico = addslashes($_POST['nome_servico']);
        $area_servico = addslashes($_POST['area_servico']);


        if (!empty($nome_servico) && !empty($area_servico)) {
            if (!$servico->CadastrarServico($nome_servico, $area_servico)) {
                echo "Serviço já cadastrado!";
            }
        } else {
            echo "Preencha TODOS* os campos!";
        }
    }
}

if (isset($_GET['id_servico'])) {
    $id = addslashes($_GET['id_servico']);
    $resultado = $servico->BuscarEditar($id);
}
?>

<div class="container-fluid" id="main">
    <form method="POST">
        <h1>Categorias de Serviços</h1>
        <div class="row form">
            <div class="col-lg-3 col-md- col-sm-12">
                <label for="nome_servico">Nome Serviço</label>
                <input required type="text" name="nome_servico" id="nome_servico" class="form-control" value="<?php if (isset($resultado)) {echo $resultado['nome_servico'];} ?>">
                <input required type="hidden" name="id_servico" value="<?php echo isset($resultado) ? $resultado['servico_id'] : ''; ?>">
            </div>
            <div class="col-lg-2 col-md-1 col-sm">
                <label for="area_servico">Área do Serviço</label>
                <select name="area_servico" class="form-control">
                    <option value="<?php echo isset($resultado) ? $resultado['area_servico'] : ''; ?>"><?php echo isset($resultado) ? $resultado['area_servico'] : 'Selecione Área Serviço'; ?></option>
                    <option value="TI">Informática</option>
                    <option value="GEM">Engenharia e Manutenção</option>
                    <option value="AEP">Experimentação e Produção</option>
                    <option value="CDT">Telefonia e Segurança</option>
                </select>
            </div>
            <div class="col-lg-1 col-md-1 col-sm-12 py-2 my-2">
                <button type="submit" value="cadastrar" class="btn btn-success my-3">
                    Salvar
                </button>
            </div>
        </div>
    </form>
</div>

<h3 class="text-center">Lista de Serviços</h3>
<table class="table table-borderless table-striped table-hover table-sm">
    <thead>
        <tr>
            <th class="col-10">Nome Serviço</th>
            <th>Editar</th>
            <th>Apagar</th>
        </tr>
    </thead>
    <?php
    $dados = $servico->BuscarServico();
    if (count($dados) > 0) {
        for ($i = 0; $i < count($dados); $i++) {
            echo "<tr>";
            foreach ($dados[$i] as $k => $v) {
                if ($k !== 'servico_id') {
                    echo "<td>" . $v . "</td>";
                }
            }
    ?>
            <td>
                <a href="servicos.php?id_servico=<?php echo $dados[$i]['servico_id']; ?>">
                    <button class="btn btn-secondary btn-sm">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                </a>
            </td>
            <td>
                <a href="servicos.php?id_servico_excluir=<?php echo $dados[$i]['servico_id']; ?>">
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