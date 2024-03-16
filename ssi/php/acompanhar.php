<?php
$titulo = "Acompanhar";
$page = 'acompanhar';
include '../php/includes/header.inc.php';
include '../php/includes/menu.inc.php';
require_once '../classes/controller/acompanhar-cont.class.php';
require '../classes/controller/verificar_session.php';
$andamento = new Acompanhar("banco_ssi", "localhost", "root", "");
if (isset($_POST['id_finalizar'])) {
    // Obter a data e hora atual no formato brasileiro
    // Define o fuso horário para Brasília
    date_default_timezone_set('America/Sao_Paulo');
    // Obtendo a data e hora atual de Brasília
    $data_hora_registro = new DateTime();
    // Formata a data e hora
    $data_hora_formatada = $data_hora_registro->format('Y-m-d H:i:s');
    $id = $_POST['id_finalizar'];
    $andamento->Finalizar($id, $data_hora_formatada);
}
?>

<div class="container-fluid" id="main">
    <h1>
        <?php
        // Verifica o tipo de usuário
        if ($_SESSION['tipo_usuario'] == 'gerente' || $_SESSION['tipo_usuario'] == 'tecnico') {
            echo 'SSI em andamento';
        } else {
            echo 'Minhas SSI';
        }
        ?>
    </h1>

    <!-- Formulário de Filtro -->
    <?php if ($_SESSION['tipo_usuario'] == 'gerente' || $_SESSION['tipo_usuario'] == 'tecnico') : ?>
        <form method="GET" action="">
            <label for="filtroSSI">Filtrar por:</label>
            <select name="filtroSSI" id="filtroSSI">
                <option value="todas" <?php echo isset($_GET['filtroSSI']) && $_GET['filtroSSI'] === 'todas' ? 'selected' : ''; ?>>
                    Todas as SSI
                </option>
                <option value="responsavel" <?php echo isset($_GET['filtroSSI']) && $_GET['filtroSSI'] === 'responsavel' ? 'selected' : ''; ?>>
                    SSI que sou Responsável Técnico
                </option>
            </select>
            <button type="submit" class="btn btn-success mb-2">Aplicar Filtro</button>
        </form>
    <?php endif; ?>


    <table class="table table-borderless table-striped">
        <tr>
            <th>Número SSI</th>
            <?php $filtroSSI = isset($_GET['filtroSSI']) ? $_GET['filtroSSI'] : 'todas';
            if ($_SESSION['tipo_usuario'] == 'gerente' || $_SESSION['tipo_usuario'] == 'tecnico') {
                if ($filtroSSI === 'responsavel') {
                    echo '<th>Nome Solicitante</th>';
                } else {
                    echo '<th>Nome Solicitante</th>
                    <th>Responsável Técnico</th>';
                }
            } elseif ($_SESSION['tipo_usuario'] == 'usuario') {
                echo '<th>Responsável Técnico</th>';
            }
            ?>
            <th>Tipo de Serviço</th>
            <th>Aberto em</th>
            <th>Acompanhar</th>
            <?php
            // Verifica se o tipo de usuário não é 'usuario' para exibir a coluna 'Finalizar'
            if ($_SESSION['tipo_usuario'] != 'usuario') {
                echo '<th>Finalizar</th>';
            }
            ?>
        </tr>

        <?php
        $chapaUsuario = $_SESSION['chapa'];
        $gerencia = $_SESSION['tipo_usuario'];

        // Verifica se há um filtro na URL
        $filtroSSI = isset($_GET['filtroSSI']) ? $_GET['filtroSSI'] : 'todas';

        if ($gerencia != 'usuario' && $filtroSSI === 'todas') {
            $id_ssi = $andamento->BuscarTodasSSI();
        } else {
            // Aplica o filtro de acordo com a escolha do usuário
            if ($gerencia != 'usuario' && $filtroSSI === 'responsavel') {
                $id_ssi = $andamento->BuscarSSITecnico($chapaUsuario);
            } else {
                $id_ssi = $andamento->BuscarSSI($chapaUsuario);
            }
        }

        if (count($id_ssi) > 0) {
            for ($i = 0; $i < count($id_ssi); $i++) {
                echo "<tr>";
                foreach ($id_ssi[$i] as $k => $v) {
                    echo "<td>" . $v . "</td>";
                }

                // Pegue o valor do ID da coluna "id"
                $id = $id_ssi[$i]['ssi_id'];
                echo"
                <td>
                    <a href='informacoes.php?ssi_id=" . $id . "'>
                        <button class='btn btn-success btn-sm'>
                            <i class='bi bi-info-lg'></i>
                        </button>
                    </a>
                </td>";
                ?>
        <?php
                if ($_SESSION['tipo_usuario'] != 'usuario') {
                    echo '
                    <td>
                    <form method="POST" action="">
                        <input type="hidden" name="id_finalizar" value="' . $id . '">
                        <button type="submit" class="btn btn-success btn-sm">
                        <i class="bi bi-check-square"></i>
                        </button>
                    </form>
                </td>';
                }
            }
        }
        ?>
    </table>

    <?php
    require_once '../php/includes/footer.inc.php'
    ?>
</div>

</body>

</html>