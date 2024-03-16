<?php
$titulo = "Informações";
$page = 'acompanhar';
include '../php/includes/header.inc.php';
include '../php/includes/menu.inc.php';
require_once '../classes/controller/acompanhar-cont.class.php';
require '../classes/controller/verificar_session.php';
$infos = new Acompanhar("banco_ssi", "localhost", "root", "");
?>

<div class="container-fluid" id="main">
    <?php
    if (isset($_POST['id_finalizar_ssi'])) {
        // Obter a data e hora atual no formato brasileiro
        // Define o fuso horário para Brasília
        date_default_timezone_set('America/Sao_Paulo');
        // Obtendo a data e hora atual de Brasília
        $data_hora_registro = new DateTime();
        // Formata a data e hora
        $data_hora_formatada = $data_hora_registro->format('Y-m-d H:i:s');
        $id = $_POST['id_finalizar_ssi'];
        $infos->Finalizar($id, $data_hora_formatada);
        header("location: gerenciar.php");
        exit();
    }

    $id_ssi = $_GET['ssi_id'];
    if (isset($_POST['descricaoServico'])) {
        // Define o fuso horário para Brasília
        date_default_timezone_set('America/Sao_Paulo');
        // Obtendo a data e hora atual de Brasília
        $data_hora_registro = new DateTime();
        // Formata a data e hora
        $chapa_tecnico = $_SESSION['chapa'];
        $data_hora_formatada = $data_hora_registro->format('Y-m-d H:i:s');
        $descricaoServico = addslashes($_POST['descricaoServico']);
        $infos->AtualizarSSI($data_hora_formatada, $descricaoServico, $id_ssi, $chapa_tecnico);
        $infos->AtualizarAndamento($id_ssi);
        header("Location: {$_SERVER['PHP_SELF']}?ssi_id=$id_ssi");
        exit();
    }

    $id_ssi = $_GET['ssi_id'];
    // Código para a parte de atualização SSI
    if (isset($_POST['valorPeca']) && isset($_POST['descricaoPeca'])) {

        // Define o fuso horário para Brasília
        date_default_timezone_set('America/Sao_Paulo');
        // Obtendo a data e hora atual de Brasília
        $data_hora_registro = new DateTime();
        // Formata a data e hora
        $data_hora_formatada = $data_hora_registro->format('Y-m-d H:i:s');
        $valorPeca = addslashes($_POST['valorPeca']);
        $descricaoPeca = addslashes($_POST['descricaoPeca']);
        $chapa_tecnico = $_SESSION['chapa'];
        $infos->AtualizarSSIPeca($data_hora_formatada, $descricaoPeca, $valorPeca, $id_ssi, $chapa_tecnico);
        $infos->AtualizarAndamento($id_ssi);
        header("Location: {$_SERVER['PHP_SELF']}?ssi_id=$id_ssi");
        exit();
    }

    // Mostrar os detalhes da SSI
    // Mostrar os detalhes da SSI
    if (isset($_GET['ssi_id'])) {
        $id = $_GET['ssi_id'];
        $acompanhamento = $infos->Acompanhar($id);
        echo '<div class="container-fluid" id="main">';
        foreach ($acompanhamento as $dados) {
            echo '<h1 class="text-center">Andamento SSI: ' . $dados['ssi_id'] . '</h1>';
            echo '<p><strong>Solicitante: </strong> ' . $dados['nome_solicitante'] . '</p>';
            echo '<p><strong>Tipo de Serviço:</strong> ' . $dados['tipo_servico'] . '</p>';
            echo '<p><strong>Descrição:</strong> ' . $dados['desc_problema'] . '</p>';
            echo '<p><strong>Responsável Técnico:</strong> ' . $dados['nome_tecnico'] . '</p>';

            echo '<h3 class="text-center pb-2">Situação</h3>';

            $dados = $acompanhamento[0]['andamento'];
            $aberto = ($dados >= 1) ? 'active' : '';
            $registrado = ($dados >= 2) ? 'active' : '';
            $andamento = ($dados >= 3) ? 'active' : '';
            $aguardando = ($dados >= 4) ? 'active' : '';
            $finalizado = ($dados >= 5) ? 'active' : '';
        }
    }


    echo '<div class="step-progress">
        <div class="step ' . $aberto . '">
            <div class="step-num">1</div>
            <div class="step-title">Aberto</div>
        </div>';
    echo '<div class="step ' . $registrado . '">
            <div class="step-num">2</div>
            <div class="step-title">Registrado</div>
        </div>';
    echo '<div class="step ' . $andamento . '">
            <div class="step-num">3</div>
            <div class="step-title">Em Andamento</div>
        </div>';
    echo '<div class="step ' . $aguardando . '">
            <div class="step-num">4</div>
            <div class "step-title">Aguardando</div>
        </div>';
    echo '<div class="step ' . $finalizado . '">
            <div class="step-num">5</div>
            <div class="step-title">Finalizado</div>
        </div>
    </div>';

    // Verificar se o usuário tem permissão de gerência
    if ($_SESSION['tipo_usuario'] == 'gerente' || $_SESSION['tipo_usuario'] == 'tecnico') {
        if ($finalizado != 'active') {
            $id_ssi = $_GET['ssi_id'];
            // Código para a parte de atualização SSI
    ?>

            <div class="container-fluid">
                <span>Serviço Realizado</span>
                <button id="mostrarFormulario" class="btn btn-success btn-sm">+</button>
            </div>
            <div id="formularioServico" style="display:none;">
                <form id="formServico" method="post" action="">
                    <!-- Adicione o campo oculto para o ID da SSI -->
                    <input type="hidden" name="id_ssi" value="' . $id . '">
                    <div class="form-group">
                        <label for="descricaoServico">Descrição do Serviço:</label>
                        <textarea type="text" class="form-control" name="descricaoServico" id="descricaoServico" placeholder="Informe a descrição"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success" id="btnSalvarServico">Salvar</button>
                </form>
            </div>

            <!-- Formulario para inserção de peça e a descrição, caso necessario  -->
            <div class="container-fluid pt-3">
                <span>Peça Instalada</span>
                <button id="mostrarFormularioPeca" class="btn btn-success btn-sm">+</button>
            </div>
            <div id="formularioPeca" style="display:none;">
                <form id="formPeca" method="post" action="">
                    <!-- Adicione o campo oculto para o ID da SSI -->
                    <input type="hidden" name="id_ssi" value="' . $id . '">
                    <div class="form-group">
                        <label for="descricaoPeca">Descrição da Peça:</label>
                        <input type="text" class="form-control" name="descricaoPeca" id="descricaoPeca" placeholder="Informe a descrição da peça">
                    </div>
                    <div class="form-group">
                        <label for="valorPeca">Valor:</label>
                        <input type="text" class="form-control" name="valorPeca" id="valorPeca" placeholder="Informe o valor">
                    </div>
                    <button type="submit" class="btn btn-success" id="btnSalvarPeca">Salvar</button>
                </form>
            </div>
        <?php
        }
    }
    $id_ssi = $_GET['ssi_id'];
    // Busqueos serviços feitos para o id_ssi específico
    $historicoResult = $infos->BuscarAtualizacao($id_ssi);
    $historicoResultPeca = $infos->BuscarAtualizacaoPeca($id_ssi);

    if (count($historicoResult) > 0 || count($historicoResultPeca) > 0) {
        echo '<div class="row">';

        // Tabela de histórico de serviços
        if (count($historicoResult) > 0) {
        ?>
            <div class="col-8">
                <h4 class="my-3">Histórico de Serviços</h4>
                <table class="table table-striped table-hover table-borderless">
                    <thead>
                        <tr>
                            <th>Data e Hora</th>
                            <th>Descrição</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        foreach ($historicoResult as $historicoItem) {
                            echo '<tr>
                       <td>' . $historicoItem['data_atualizacao'] . '</td>
                       <td>' . $historicoItem['descricao_atualizacao'] . '</td>
                   </tr>';
                        }
                        echo '</tbody>
                </table>
                </div>';
                    }

                    // Tabela de histórico de peças
                    if (count($historicoResultPeca) > 0) {
                        ?>
                        <div class="col-4">
                            <h4 class="my-3">Histórico de Peças</h4>
                            <table class="table table-striped table-hover table-borderless">
                                <thead>
                                    <tr>
                                        <th>Data e Hora</th>
                                        <th>Descrição</th>
                                        <th>Valor</th>
                                    </tr>
                                </thead>
                                <tbody>

                            <?php
                            foreach ($historicoResultPeca as $historicoItemPeca) {
                                echo '<tr>
                           <td>' . $historicoItemPeca['data_peca'] . '</td>
                           <td>' . $historicoItemPeca['descricao'] . '</td>
                           <td>' . $historicoItemPeca['valor'] . '</td>
                       </tr>';
                            }
                            echo '
                            </tbody>
                </table>
                </div>';
                        }
                        echo '</div>';
                    }
                    if ($_SESSION['tipo_usuario'] == 'gerente' || $_SESSION['tipo_usuario'] == 'tecnico') {
                        if ($finalizado != 'active') {
                            //Botão para finalizar SSI
                            echo '
                            <form method="POST" class="my-2">
                                <input type="hidden" name="id_finalizar_ssi" value="' . $id . '">
                                    <button type="submit" class="btn btn-danger">Finalizar SSI</button>
                                    </form>
                                </div>';
                        }
                    }
                    $historicoResult = $infos->BuscarAtualizacaoPeca($id_ssi);
                            ?>
                        </div>

                        <?php
                        require_once '../php/includes/footer.inc.php';
                        ?>
                        </body>

                        </html>