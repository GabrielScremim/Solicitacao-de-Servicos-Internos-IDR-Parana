<?php
$titulo = "Histórico SSI";
$page = 'historico';
include '../php/includes/header.inc.php';
include '../php/includes/menu.inc.php';
require_once '../classes/controller/ssi-historico-cont.class.php';
require '../classes/controller/verificar_session.php';
$historico = new Historico("banco_ssi", "localhost", "root", "");

// verifica se o usuario possui acesso para está página
if ($_SESSION['tipo_usuario'] == 'usuario') {
    header("location: index.php");
    exit();
}
?>

<div class="container-fluid" id="main">
    <h1>Histórico de SSI</h1>

    <!-- Formulário de Filtro -->
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

    <table class="table table-borderless table-hover table-striped">
        <thead>
            <tr>
                <th>Nome Solicitante</th>
                <th>Descrição</th>
                <?php
                $filtroSSI = isset($_GET['filtroSSI']) ? $_GET['filtroSSI'] : '';
                if ($filtroSSI == 'todas') {
                    echo "<th>Responsável/Técnico</th>";
                }
                ?>
                <th>Registro</th>
                <th>finalização</th>
                <th>Informações</th>
            </tr>
        </thead>
        <?php
        $chapaUsuario = $_SESSION['chapa'];
        $gerencia = $_SESSION['tipo_usuario'];

        $filtroSSI = isset($_GET['filtroSSI']) ? $_GET['filtroSSI'] : 'todas';

        if ($filtroSSI == 'todas') {
            $ssi = $historico->TodoHistorico();
        } else {
            if ($filtroSSI == 'responsavel') {
                $ssi = $historico->TodoHistoricoTecnico($chapaUsuario);
            } else {
                $ssi = $historico->Historico($chapaUsuario);
            }
        }


        foreach ($ssi as $servico) {
            // Armazene o ID da SSI em uma variável
            $id_ssi = $servico['ssi_id'];

            echo "<tr>";
            foreach ($servico as $k => $v) {
                if ($k !== 'ssi_id') {
                    echo "<td>" . $v . "</td>";
                }
            }

            // Adicione uma coluna para exibir o botão de Informações
            echo "<td>
            <a href='informacoes.php?ssi_id=" . $id_ssi . "'>
                <button class='btn btn-success btn-sm'>
                    <i class='bi bi-info-lg'></i>
                </button>
            </a>
        </td>";
        }
        ?>

    </table>
</div>

<?php
require_once '../php/includes/footer.inc.php'
?>
</body>

</html>