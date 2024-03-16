<?php
$titulo = "Menu";
$page = 'home';
include '../php\includes\header.inc.php';
include '../php\includes\menu.inc.php';
require_once '../classes/controller/acompanhar-cont.class.php';
require '../classes/controller/verificar_session.php';
$andamento = new Acompanhar("banco_ssi", "localhost", "root", "");
?>
<div class="container-fluid" id="main">
    <table class="table table-borderless table-striped">
        <tr>
            <th>Número SSI</th>
            <th>Nome Solicitante</th>
            <?php
            if ($_SESSION['tipo_usuario'] == 'gerente') {
                echo '<th>Responsável Técnico</th>';
                $id_ssi = $andamento->BuscarTodasSSI();
            }
            ?>
            <th>Tipo de Serviço</th>
            <th>Aberto em</th>
            <th>Acompanhar</th>
            <?php
            $chapaUsuario = $_SESSION['chapa'];
            $gerencia = $_SESSION['tipo_usuario'];
            // Verifica o tipo de usuário
            if ($_SESSION['tipo_usuario'] == 'gerente') {
                echo '<h1>SSI em Aberto<h1>';
                $id_ssi = $andamento->BuscarTodasSSI();
            } elseif ($_SESSION['tipo_usuario'] == 'tecnico') {
                echo '<h1>Minhas SSI em Aberto<h1>';
                $id_ssi = $andamento->BuscarSSITecnico($chapaUsuario);
            } else {
                $id_ssi = $andamento->BuscarSSI($chapaUsuario);
            }

            for ($i = 0; $i < count($id_ssi); $i++) {
                echo "<tr>";
                foreach ($id_ssi[$i] as $k => $v) {
                    echo "<td>" . $v . "</td>";
                }

                // Pegue o valor do ID da coluna "id"
                $id = $id_ssi[$i]['ssi_id'];

                echo "<td>
                    <a href='informacoes.php?ssi_id=" . $id . "'>
                        <button class='btn btn-success btn-sm'>
                            <i class='bi bi-info-lg'></i>
                        </button>
                    </a>
                </td>
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