<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <title><?php echo $titulo; ?></title>
</head>

<body id="body-pd">
    <?php
    require '../classes/controller/verificar_session.php';
    ?>
    <header class="header" id="header">
        <div class="row">
            <div class="col-lg-9 col-md-8 col-sm-7">
                <i class="btn btn-lg text-white bi bi-list my-2 py-2" id="header-toggle"></i>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-3 text-white my-2 py-2">
                <span><?php echo $_SESSION['nome']; ?></span>
            </div>
            <div class="col-lg-1 col-md-2 col-sm-2 ">
                <a href="../classes/controller/logout.php"><i class="btn btn-lg text-white bi-box-arrow-left my-2 py-2"></i></a>
            </div>
        </div>
    </header>