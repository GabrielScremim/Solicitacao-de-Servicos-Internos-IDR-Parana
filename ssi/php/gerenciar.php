<?php
$titulo = "Gerenciar";
$page = 'gerenciar';
include '../php/includes/header.inc.php';
include '../php/includes/menu.inc.php';
require_once '../classes/controller/ssi-gerencia-cont.class.php';
require_once '../classes/controller/acompanhar-cont.class.php';
require '../classes/controller/verificar_session.php';
$gerenciar = new Gerenciar("banco_ssi", "localhost", "root", "");
$infos = new Acompanhar("banco_ssi", "localhost", "root", "");

// verifica se o usuario possui acesso para está página
if ($_SESSION['tipo_usuario'] != 'gerente') {
    header("location: index.php");
    exit();
}

if (isset($_POST['fk_responsavel_tecnico']) && isset($_POST['id']) && isset($_POST['fk_id_servico'])) {
    $fk_responsavel_tecnico = addslashes($_POST['fk_responsavel_tecnico']);
    $fk_id_servico = addslashes($_POST['fk_id_servico']);
    $id_ssi = $_POST['id'];
    $andamento = '2';
    $gerenciar->AtribuirTecnico($id_ssi, $fk_responsavel_tecnico, $andamento, $fk_id_servico);
    header("location: gerenciar.php");
    exit();
}
$chapa = $_SESSION['chapa'];
$descricaoServico = 'SSI Recusada';
if (isset($_POST['id_recusar'])) {
    // Obter a data e hora atual no formato brasileiro
    // Define o fuso horário para Brasília
    date_default_timezone_set('America/Sao_Paulo');
    // Obtendo a data e hora atual de Brasília
    $data_hora_registro = new DateTime();
    // Formata a data e hora
    $data_hora_formatada = $data_hora_registro->format('Y-m-d H:i:s');
    $id = $_POST['id_recusar'];
    $gerenciar->Recusar($id, $data_hora_formatada);
    $infos->AtualizarSSI($data_hora_formatada, $descricaoServico, $id, $chapa);
    header("location: gerenciar.php");
    exit();
}
?>

<div class="container-fluid" id="main">
    <h3>SSI aguardando técnico</h3>

    <table class="table table-borderless table-striped table-hover">
        <tr>
            <thead>
                <th>Nome Solicitante</th>
                <th>Ramal</th>
                <th>Descrição do Problema</th>
                <th class="col-3">Categoria do serviço</th>
                <th>Responsável Técnico</th>
                <th>Aceitar</th>
                <th>Negar</th>
            </thead>
        </tr>
        <?php
        $ssi = $gerenciar->AguardandoTecnico();
        $tecnico = $gerenciar->BuscarTecnico();
        $servicos = $gerenciar->servicos();
        if (count($ssi) > 0) {
            foreach ($ssi as $servico) {
                echo "<tr>";
                foreach ($servico as $k => $v) {
                    if ($k !== 'ssi_id') {
                        echo "<td>" . $v . "</td>";
                    }
                }
        ?>
                <td>
                    <form method="POST" action="">
                        <select name="fk_id_servico" class="form-control" id="servico">';

                            <!-- Adiciona uma opção desabilitada para servir como rótulo -->
                            <option value="" disabled selected>Selecione um Serviço</option>

                            <?php
                            foreach ($servicos as $dadosServicos) {
                                echo '<option value="' . $dadosServicos['servico_id'] . '">' . $dadosServicos['nome_servico'] . '</option>';
                            }
                            ?>
                        </select>
                </td>
                <td>
                    <form method="POST" action="">
                        <select name="fk_responsavel_tecnico" class="form-control" id="tecnico">

                            <!-- Adiciona uma opção desabilitada para servir como rótulo -->
                            <option value="" disabled selected>Selecione um técnico</option>

                            <?php
                            foreach ($tecnico as $dadosTecnico) {
                                echo '<option value="' . $dadosTecnico['chapa'] . '">' . $dadosTecnico['nome'] . '</option>';
                            }

                            ?>
                        </select>
                </td>

                <!-- Adiciona um campo oculto para armazenar o ID do serviço -->
                <?php
                echo '<input type="hidden" name="id" value="' . $servico['ssi_id'] . '">';
                ?>
                <td>
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="bi bi-check2"></i>
                    </button>
                </td>
                <?php
                echo '<input type="hidden" name="id_recusar" value="' . $servico['ssi_id'] . '">';
                ?>
                <td>
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </td>
                </form>
                </tr>
        <?php
            }
        }
        ?>
    </table>

    <p>&nbsp;</p>

    <table class="table table-borderless table-striped table-hover">
        <h3>SSI em andamento</h3>
        <tr>
            <thead>
                <th>Nome Solicitante</th>
                <th>Ramal</th>
                <th>Categoria do serviço</th>
                <th>Descrição do Problema</th>
                <th>Responsável Técnico</th>
                <th>Acompanhar</th>
            </thead>
        </tr>
        <?php
        $ssi = $gerenciar->EmAndamento();
        if (count($ssi) > 0) {
            foreach ($ssi as $servico) {
                echo "<tr>";
                foreach ($servico as $k => $v) {
                    if ($k !== 'ssi_id') {
                        echo "<td>" . $v . "</td>";
                    }
                }
                // Pegue o valor do ID da coluna "id"
                $id = $servico['ssi_id'];

                echo "<td>
                        <a href='informacoes.php?ssi_id=" . $id . "'>
                            <button class='btn btn-success btn-sm'>
                                <i class='bi bi-info-lg'></i>
                            </button>
                        </a>
                    </td>";
            }
        }
        ?>
        </tbody>
    </table>
    <?php
    require_once '../php/includes/footer.inc.php'
    ?>
</div>
</body>

</html>