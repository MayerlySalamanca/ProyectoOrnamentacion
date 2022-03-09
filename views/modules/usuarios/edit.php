<?php
require("../../partials/routes.php");
require_once("../../partials/check_login.php");
require("../../../app/Controllers/UsuariosController.php");

use App\Controllers\DepartamentosController;
use App\Controllers\MunicipiosController;
use App\Controllers\UsuariosController;
use App\Models\Usuario;
use App\Models\GeneralFunctions;
use Carbon\Carbon;

$nameModel = "Usuario";
$pluralModel = $nameModel.'s';
$nameForm = 'frmEdit'.$nameModel;
$frmSession = $_SESSION['frm'.$pluralModel] ?? NULL;

?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $_ENV['TITLE_SITE'] ?> | Editar <?= $nameModel ?></title>
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
                        <h1>Editar <?= $nameModel ?></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= $baseURL; ?>/views/"><?= $_ENV['ALIASE_SITE'] ?></a></li>
                            <li class="breadcrumb-item"><a href="index.php"><?= $pluralModel ?></a></li>
                            <li class="breadcrumb-item active">Editar</li>
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
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-box"></i>&nbsp; Información del <?= $nameModel ?></h3>
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

                            <?php if (!empty($_GET["id"]) && isset($_GET["id"])) { ?>
                                <p>
                                <?php
                                $DataUsuario = UsuariosController::searchForID(["id" => $_GET["id"]]);
                                /* @var $DataUsuario Usuario */
                                if (!empty($DataUsuario)) {
                                    ?>
                                    <div class="card-body">
                                        <!-- form start -->
                                        <form class="form-horizontal" method="post" id="<?= $nameForm ?>" name="<?= $nameForm ?>"
                                              action="../../../app/Controllers/MainController.php?controller=<?= $pluralModel ?>&action=edit">
                                            <input id="idUsuario" name="idUsuario" value="<?= $DataUsuario->getIdUsuario(); ?>"
                                                   hidden required="required" type="text">
                                            <div class="form-group row">
                                                <label for="documento" class="col-sm-2 col-form-label">Documento</label>
                                                <div class="col-sm-10">
                                                    <input required type="number" class="form-control" id="nombres"
                                                           name="documento" value="<?= $DataUsuario->getDocumento(); ?>"
                                                           placeholder="Ingrese el numero del documento">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="nombre" class="col-sm-2 col-form-label">Nombres</label>
                                                <div class="col-sm-10">
                                                    <input required type="text" class="form-control" id="nombre"
                                                           name="nombre" value="<?= $DataUsuario->getNombres(); ?>"
                                                           placeholder="Ingrese el nombres">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="telefono" class="col-sm-2 col-form-label">Telefono</label>
                                                <div class="col-sm-10">
                                                    <input required type="text" class="form-control" id="telefono"
                                                           name="telefono" value="<?= $DataUsuario->getTelefono(); ?>"
                                                           placeholder="Ingrese el numero del telefono">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="direccion" class="col-sm-2 col-form-label">Direccion</label>
                                                <div class="col-sm-10">
                                                    <input required type="text" class="form-control" id="direccion"
                                                           name="direccion" value="<?= $DataUsuario->getDireccion(); ?>"
                                                           placeholder="Ingrese el numero del telefono">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="roll" class="col-sm-2 col-form-label">Rol</label>
                                                <div class="col-sm-10">
                                                    <select id="roll" name="roll" class="custom-select">
                                                        <option <?= ($DataUsuario->getRoll() == 'administrador') ? "selected" : ' '; ?>
                                                                value="administrador">Administrador
                                                        </option>
                                                        <option <?= ($DataUsuario->getRoll() == 'vendedor') ?  "selected" : ' ' ;?>
                                                                value="vendedor">Vendedor
                                                        </option>
                                                        <option <?= ($DataUsuario->getRoll() == 'cliente') ?  "selected" : ' ' ;?>
                                                                value="cliente">Cliente
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div id="div-hide">
                                                <div class="form-group row">
                                                    <label for="usuario" class="col-sm-2 col-form-label">Usuario</label>
                                                    <div class="col-sm-10">
                                                        <input required type="text" class="form-control" id="usuario"
                                                               name="usuario" value="<?= $DataUsuario->getUsuario(); ?>"
                                                               placeholder="Ingrese el Usuario">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="contrasena" class="col-sm-2 col-form-label">Contraseña</label>
                                                    <div class="col-sm-10">
                                                        <input required type="text" class="form-control" id="contrasena"
                                                               name="contrasena"
                                                               placeholder="Ingrese su Contraseña">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="estado" class="col-sm-2 col-form-label">Estado</label>
                                                <div class="col-sm-10">
                                                    <select id="estado" name="estado" class="custom-select">
                                                        <option <?= ($DataUsuario->getEstado() == "Activo") ? "selected" : ""; ?>
                                                                value="Activo">Activo
                                                        </option>
                                                        <option <?= ($DataUsuario->getEstado() == "Inactivo") ? "selected" : ""; ?>
                                                                value="Inactivo">Inactivo
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="municipiosId" class="col-sm-2 col-form-label">Municipio</label>
                                                <div class="col-sm-5">
                                                    <?=
                                                    DepartamentosController::selectDepartamentos(
                                                        array(
                                                            'id' => 'idDepartamentos',
                                                            'name' => 'idDepartamentos',
                                                            'defaultValue' => (!empty($DataUsuario)) ? $DataUsuario->getMunicipio()->getDepartamento()->getIdDepartamentos() : '15',
                                                            'class' => 'form-control select2bs4 select2-info',
                                                            'where' => "estado = 'Activo'"
                                                        )
                                                    )
                                                    ?>
                                                </div>
                                                <div class="col-sm-5 ">
                                                    <?= MunicipiosController::selectMunicipios(
                                                        array (
                                                            'id' => 'municipiosId',
                                                            'name' => 'municipiosId',
                                                            'defaultValue' => (!empty($DataUsuario)) ? $DataUsuario->getMunicipiosId() : '',
                                                            'class' => 'form-control select2bs4 select2-info',
                                                            'where' => "departamentosId = ".$DataUsuario->getMunicipio()->getDepartamento()->getIdDepartamentos()." and estado = 'Activo'")
                                                    )
                                                    ?>
                                                </div>
                                            </div>
                                            <hr>
                                            <button id="frmName" name="frmName" value="<?= $nameForm ?>" type="button" class="btn btn-info">Enviar</button>
                                            <a href="index.php" role="button" class="btn btn-default float-right">Cancelar</a>
                                        </form>
                                    </div>
                                    <!-- /.card-body -->

                                <?php } else { ?>
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                            &times;
                                        </button>
                                        <h5><i class="icon fas fa-ban"></i> Error!</h5>
                                        No se encontro ningun registro con estos parametros de
                                        busqueda <?= ($_GET['mensaje']) ?? "" ?>
                                    </div>
                                <?php } ?>
                                </p>
                            <?php } ?>
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
<script>
    $(function() {
        $('#frmName').on('click', function() {
            $('#frmEditUsuario').trigger('submit');
        });

        $('#roll').on('change', function() {
            if(this.value == 'cliente'){
                $('#contrasena').val( ' ' );
                $('#div-hide').hide();
            }else{
                $('#div-hide').show();
            }
        });



        $('#idDepartamentos').on('change', function() {
            $.post("../../../app/Controllers/MainController.php?controller=Municipios&action=selectMunicipios", {
                isMultiple: false,
                isRequired: true,
                id: "municipiosId",
                nombre: "municipiosId",
                defaultValue: "",
                class: "form-control select2bs4 select2-info",
                where: "departamentosId = "+$('#idDepartamentos').val()+" and estado = 'Activo'",
                request: 'ajax'
            }, function(e) {
                if (e)
                    console.log(e);
                $("#municipiosId").html(e).select2({ height: '100px'});
            });
        });
        $('.btn-file span').html('Seleccionar');
    });
</script>
</body>
</html>