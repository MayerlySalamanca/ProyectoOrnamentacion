<?php require("partials/routes.php"); ?>

<?php  require("partials/check_login.php"); ?>


<!DOCTYPE html>
<html>
<head>
    <title><?= $_ENV['TITLE_SITE'] ?> | Inicio</title>
    <?php require("partials/head_imports.php"); ?>
</head>
<body class="hold-transition sidebar-mini">

<!-- Site wrapper -->
<div class="wrapper">
    <?php require("partials/navbar_customization.php"); ?>
    <?php require("partials/sliderbar_main_menu.php"); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Diarma</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?=$adminlteURL; ?>/views/index.php"><?= $_ENV['ALIASE_SITE'] ?></a></li>
                            <li class="breadcrumb-item active">Home</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Bienvenidos</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
                            <i class="fas fa-times"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    Bienvenidos a industria diarma una empresa familiar que lleva en la industria
                    de la ornamentación hace mas de 10 años, que implementa los valores de la industria
                    Colombia en cada uno de los procesos de construcción de sus productos, el cumplimiento y
                    compromiso con los clientes hacen de nuestra industria un núcleo familiar.
                    Es de nuestro gran aprecio saber que nuestros clientes son parte de esta gran familia que día a día esta creciendo y agradecemos su confianza en nosotros, bienvenidos.
                    <br>
                    <br>
                  <center> <img src="<?= $baseURL ?>/views/public/img/soldadura.jpg" width="700" height="329"
                                alt="Derechos:https://www.bing.com/images/search?view=detailV2&ccid=hCPr0rKb&id=620B1BBFB2C74EC2DEA0A11761CD0C58A13FEA11&thid=OIP.hCPr0rKbwj_Vt1zKg8zzjwHaEV&mediaurl=https%3a%2f%2fconstruyored.com%2fstorage%2foportunidades%2fimages%2flarge%2f15157218875a58149fab189.jpg&cdnurl=https%3a%2f%2fth.bing.com%2fth%2fid%2fR.8423ebd2b29bc23fd5b75cca83ccf38f%3frik%3dEeo%252foVgMzWEXoQ%26pid%3dImgRaw%26r%3d0&exph=800&expw=1366&q=ornamentacion&simid=607997945764799956&FORM=IRPRST&ck=94AEFCD6BFBD4CA5095738DEF6BC68DA&selectedIndex=4&ajaxhist=0&ajaxserp=0">/></center>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    Gracias por ingresar a nuestro Programa
                </div>
                <!-- /.card-footer-->
            </div>
            <!-- /.card -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <?php require ('partials/footer.php');?>
</div>
<!-- ./wrapper -->
<?php require ('partials/scripts.php');?>
</body>
</html>