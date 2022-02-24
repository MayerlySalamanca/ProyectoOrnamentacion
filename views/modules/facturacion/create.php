<?php
require("../../partials/routes.php");
// require_once("../../partials/check_login.php");

use App\Models\GeneralFunctions;
use Carbon\Carbon;

$nameModel = "Factura";
$nameForm = 'frmCreate'.$nameModel;
$pluralModel = $nameModel.'s';
$frmSession = $_SESSION[$nameForm] ?? NULL;
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $_ENV['TITLE_SITE'] ?> | Crear <?= $nameModel ?></title>
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
                        <h1>Crear una nueva <?= $nameModel ?></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?=  $adminlteURL; ?>/views/"><?= $_ENV['ALIASE_SITE'] ?></a></li>
                            <li class="breadcrumb-item"><a href="index.php"><?= $pluralModel ?></a></li>
                            <li class="breadcrumb-item active">Crear</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Generar Mensaje de alerta -->
            <?= (!empty($_GET['respuesta'])) ? GeneralFunctions::getAlertDialog($_GET['respuesta'], $_GET['mensaje']) : ""; ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Horizontal Form -->
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-box"></i> &nbsp; Información de la <?= $nameModel ?></h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="card-refresh"
                                            data-source="create.php" data-source-selector="#card-refresh-content"
                                            data-load-on-init="false"><i class="fas fa-sync-alt"></i></button>
                                    <button type="button" class="btn btn-tool" data-card-widget="maximize"><i
                                                class="fas fa-expand"></i></button>
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                                class="fas fa-minus"></i></button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <form class="form-horizontal" method="post" id="<?= $nameForm ?>" name="<?= $nameForm ?>"
                                      action="../../../app/Controllers/MainController.php?controller=<?= $pluralModel ?>&action=create">

                                    <div class="form-group row">
                                        <label for="numeroFactura" class="col-sm-2 col-form-label">numeroFactura</label>
                                        <div class="col-sm-10">
                                            <input required type="number" class="form-control" id="numeroFactura" name="numeroFactura"
                                                   placeholder="designe un numero a la factura" value="<?= $frmSession['numeroFactura'] ?? '' ?>">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="nombreCliente" class="col-sm-2 col-form-label">Nombre Cliente</label>
                                        <div class="col-sm-10">
                                            <input required type="text" class="form-control" id="nombreCliente" name="nombreCliente"
                                                   placeholder="Ingrese los nombres" value="<?= $frmSession['nombreCliente'] ?? '' ?>">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="cantidad" class="col-sm-2 col-form-label">cantidad</label>
                                        <div class="col-sm-10">
                                            <input required type="number" class="form-control" id="cantidad" name="cantidad"
                                                   placeholder="Ingrese la cantidad" value="<?= $frmSession['cantidad'] ?? '' ?>">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="fecha" class="col-sm-2 col-form-label">fecha</label>
                                        <div class="col-sm-10">
                                            <input required type="date" class="form-control" id="fecha" name="fecha"
                                                   placeholder="fecha" value="<?= $frmSession['fecha'] ?? '' ?>">
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label for="estado" class="col-sm-2 col-form-label">Estado</label>
                                        <div class="col-sm-10">
                                            <select required id="estado" name="estado" class="custom-select">
                                                <option <?= ( !empty($frmSession['estado']) && $frmSession['estado'] == "Activo") ? "selected" : ""; ?> value="Activo">Activo</option>
                                                <option <?= ( !empty($frmSession['estado']) && $frmSession['estado'] == "Inactivo") ? "selected" : ""; ?> value="Inactivo">Inactivo</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="valor" class="col-sm-2 col-form-label">valor</label>
                                        <div class="col-sm-10">
                                            <input required type="number" class="form-control" id="valor" name="valor"
                                                   placeholder="Ingrese lel valor" value="<?= $frmSession['valor'] ?? '' ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="usuarioVendedor" class="col-sm-2 col-form-label">Vendedor</label>
                                        <div class="col-sm-10">
                                            <input required type="number" class="form-control" id="usuarioVendedor" name="usuarioVendedor"
                                                   placeholder="Ingrese el nombre del vendedor" value="<?= $frmSession['usuarioVendedor'] ?? '' ?>">
                                        </div>
                                    </div>
                                        <hr>
                                    <hr>
                                    <button id="frmName" name="frmName" value="<?= $nameForm ?>" type="submit" class="btn btn-info">Enviar</button>
                                    <a href="index.php" role="button" class="btn btn-default float-right">Cancelar</a>
                                </form>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>

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
