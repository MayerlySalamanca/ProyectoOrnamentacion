<?php
require("../../partials/routes.php");
require_once("../../partials/check_login.php");
require("../../../app/Controllers/UsuariosController.php");

use App\Controllers\UsuariosController;
use App\Models\Usuario ;
use App\Models\GeneralFunctions;

$nameModel = "Usuario";
$pluralModel = $nameModel.'s';
$frmSession = $_SESSION['frm'.$pluralModel] ?? NULL;
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $_ENV['TITLE_SITE'] ?> | Datos del <?= $nameModel ?></title>
    <?php require("../../partials/head_imports.php"); ?>
</head>
<body class="hold-transition sidebar-mini">

<!-- Site wrapper -->
<div class="wrapper">
    <?php require("../../partials/navbar_customization.php"); ?>
    <?php require("../../partials/sliderbar_main_menu.php"); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Información del <?= $nameModel ?></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= $baseURL; ?>/views/"><?= $_ENV['ALIASE_SITE'] ?></a></li>
                            <li class="breadcrumb-item"><a href="index.php"><?= $pluralModel ?></a></li>
                            <li class="breadcrumb-item active">Ver</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Generar Mensajes de alerta -->
            <?= (!empty($_GET['respuesta'])) ? GeneralFunctions::getAlertDialog($_GET['respuesta'], $_GET['mensaje']) : ""; ?>
            <?= (empty($_GET['id'])) ? GeneralFunctions::getAlertDialog('error', 'Faltan Criterios de Búsqueda') : ""; ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Horizontal Form -->
                        <div class="card card-green">
                            <?php if (!empty($_GET["id"]) && isset($_GET["id"])) {
                                $DataUsuario = UsuariosController::searchForID(["id" => $_GET["id"]]);
                                /* @var $DataUsuario Usuario */
                                if (!empty($DataUsuario)) {
                                    ?>
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="fas fa-box"></i> &nbsp; Ver Información
                                            de <?= $DataUsuario->getNombres() ?? '' ?></h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="card-refresh"
                                                    data-source="show.php" data-source-selector="#card-refresh-content"
                                                    data-load-on-init="false"><i class="fas fa-sync-alt"></i></button>
                                            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i
                                                        class="fas fa-expand"></i></button>
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                                    data-toggle="tooltip" title="Collapse">
                                                <i class="fas fa-minus"></i></button>
                                            <button type="button" class="btn btn-tool" data-card-widget="remove"
                                                    data-toggle="tooltip" title="Remove">
                                                <i class="fas fa-times"></i></button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-10">
                                                <p>
                                                    <strong><i class="far fa-file-alt mr-1"></i> Documento</strong>
                                                <p class="text-muted"><?= $DataUsuario->getDocumento() ?></p>
                                                <hr>
                                                    <strong><i class="fas fa-book mr-1"></i> Nombres</strong>
                                                <p class="text-muted">
                                                    <?= $DataUsuario->getNombres() ?>
                                                </p>
                                                <hr>
                                                <strong><i class="fas fa-align-justify mr-1"></i> Telefono</strong>
                                                <p class="text-muted"><?= $DataUsuario->getTelefono() ?></p>
                                                <hr>
                                                <strong><i class="fas fa-align-justify mr-1"></i> Direccion</strong>
                                                <p class="text-muted"><?= $DataUsuario->getDireccion() ?></p>
                                                <hr>
                                                <hr>
                                                <strong><i class="fas fa-align-justify mr-1"></i> Rol</strong>
                                                <p class="text-muted"><?= $DataUsuario->getRoll() ?></p>
                                                <hr>
                                                <hr>
                                                <strong><i class="fas fa-align-justify mr-1"></i> Usuario</strong>
                                                <p class="text-muted"><?= $DataUsuario->getUsuario() ?></p>
                                                <hr>
                                                <hr>
                                                <strong><i class="fas fa-align-justify mr-1"></i> Contraseña</strong>
                                                <p class="text-muted"><?= $DataUsuario->getContrasena() ?></p>
                                                <hr>
                                                <strong><i class="far fa-file-alt mr-1"></i> Estado</strong>
                                                <p class="text-muted"><?= $DataUsuario->getEstado() ?></p>
                                                <hr>
                                                <strong><i class="far fa-file-alt mr-1"></i> Lugar de Nacimiento</strong>
                                                <p class="text-muted"><?= $DataUsuario->getMunicipio()->getDepartamento()->getNombre() . ' ' . $DataUsuario->getMunicipio()->getNombre() ?></p>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="row">
                                            <div class="col-auto mr-auto">
                                                <a role="button" href="index.php" class="btn btn-success float-right"
                                                   style="margin-right: 5px;">
                                                    <i class="fas fa-tasks"></i> Gestionar <?= $pluralModel ?>
                                                </a>
                                            </div>
                                            <div class="col-auto">
                                                <a role="button" href="edit.php?id=<?= $DataUsuario->getIdUsuario(); ?>" class="btn btn-primary float-right"
                                                   style="margin-right: 5px;">
                                                    <i class="fas fa-edit"></i> Editar <?= $nameModel ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                            &times;
                                        </button>
                                        <h5><i class="icon fas fa-ban"></i> Error!</h5>
                                        No se encontro ningun registro con estos parametros de
                                        busqueda <?= ($_GET['mensaje']) ?? "" ?>
                                    </div>
                                <?php }
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <?php require('../../partials/footer.php'); ?>
</div>
<!-- ./wrapper -->
<?php require('../../partials/scripts.php'); ?>
</body>
</html>