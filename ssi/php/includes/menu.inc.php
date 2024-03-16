<div class="l-navbar" id="nav-bar">
    <nav class="nav">
        <div>
            <a href="home.php" class="nav_logo">
                <span class="nav_logo-name">SSI Online</span>
            </a>

            <div class="nav_list">
                <a href="home.php" class="nav_link <?php if ($page == 'home') echo 'active'; ?>">
                    <i class="bi bi-house"></i>
                    <span class="nav_name">Home</span>
                </a>

                <a href="registrar-ssi.php" class="nav_link <?php if ($page == 'registrar-ssi') echo 'active'; ?>">
                    <i class="bi bi-clipboard-plus"></i>
                    <span class="nav_name">Registrar SSI</span>
                </a>

                <?php if ($_SESSION['tipo_usuario'] == 'gerente') : ?>
                    <a href="gerenciar.php" class="nav_link <?php if ($page == 'gerenciar') echo 'active'; ?>">
                        <i class="bi bi-person-check"></i>
                        <span class="nav_name">Gerenciar SSI</span>
                    </a>

                    <a href="servicos.php" class="nav_link <?php if ($page == 'tipos-servico') echo 'active'; ?>">
                        <i class="bi bi-tools"></i>
                        <span class="nav_name">Tipos de Serviço</span>
                    </a>

                    <a href="tecnico.php" class="nav_link <?php if ($page == 'registro-tecnico') echo 'active'; ?>">
                        <i class="bi bi-person-plus"></i>
                        <span class="nav_name">Registrar Técnico</span>
                    </a>
                <?php endif; ?>

                <a href="acompanhar.php" class="nav_link <?php if ($page == 'acompanhar') echo 'active'; ?>">
                    <i class="bi bi-calendar-range"></i>
                    <span class="nav_name">Acompanhar SSI</span>
                </a>
                
                <?php if ($_SESSION['tipo_usuario'] == 'gerente' || $_SESSION['tipo_usuario'] == 'tecnico') : ?>
                <a href="historico.php" class="nav_link <?php if ($page == 'historico') echo 'active'; ?>">
                    <i class="bi bi-clock-history"></i>
                    <span class="nav_name">Histórico de SSI</span>
                </a>
                <?php endif; ?>
            </div>
        </div>

        <?php
        require_once '../php/includes/footer.inc.php';
        ?>
    </nav>
</div>