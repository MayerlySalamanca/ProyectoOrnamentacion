<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="../../index3.html" class="brand-link">
        <img src="<?= $baseURL ?>/views/public/img/descarga.png"
             alt="AdminLTE Logo"
             class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light"><?= $_ENV['ALIASE_SITE'] ?></span>
    </a>

    <!-- Sidebar -->
<div class="sidebar">

        <!--<div class="user-panel mt-3 d-flex">
            <div class="image align-middle">
                <img src="<?= $baseURL ?>/views/public/img/AdminLTELogo.png" class="img-circle elevation-2" alt="usuario">
            </div>
            <div class="d-flex flex-column">
                <div class="info">
                    <a href="<?= "$baseURL/views/modules/usuarios/show.php?id=" .$_SESSION['UserInSession']['idUsuario']?>" >
                        <?= $_SESSION['UserInSession']['nombre'] ?>
                    </a>
                </div>
                <div class="info">
                    <a href="#" >
                        <?= $_SESSION['UserInSession']['roll'] ?>
                    </a>
                </div>
            </div>
        </div>-->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="<?=  $baseURL; ?>/views/index.php" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Inicio
                        </p>
                        </p>
                    </a>
                </li>
                <li class="nav-header">Modulos Principales</li>
                <li class="nav-item has-treeview <?= strpos($_SERVER['REQUEST_URI'],'usuarios') ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= strpos($_SERVER['REQUEST_URI'],'usuarios') ? 'active' : '' ?>">
                        <i class="nav-icon far fa-user"></i>
                        <p>
                            Usuarios
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?=  $baseURL ?>/views/modules/usuarios/index.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Gestionar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $baseURL ?>/views/modules/usuarios/create.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Registrar</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview <?= strpos($_SERVER['REQUEST_URI'],'proveedores') ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= strpos($_SERVER['REQUEST_URI'],'proveedores') ? 'active' : '' ?>">
                        <i class="nav-icon far fa-user"></i>

                        <p>
                            Proveedores
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= $baseURL ?>/views/modules/proveedores/index.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Gestionar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $baseURL ?>/views/modules/proveedores/create.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Registrar</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview <?= strpos($_SERVER['REQUEST_URI'],'productos') ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= strpos($_SERVER['REQUEST_URI'],'productos') ? 'active' : '' ?>">
                        <i class="nav-icon fa fa-sitemap"></i>

                        <p>
                            Productos
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?=   $baseURL ?>/views/modules/productos/index.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Gestionar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?=   $baseURL ?>/views/modules/productos/create.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Registrar</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview <?= strpos($_SERVER['REQUEST_URI'],'facturacion') ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= strpos($_SERVER['REQUEST_URI'],'facturacion') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-dolly"></i>
                        <p>
                            Facturacion
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?=   $baseURL ?>/views/modules/facturacion/index.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Gestionar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?=   $baseURL ?>/views/modules/facturacion/create.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Registrar</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview <?= strpos($_SERVER['REQUEST_URI'],'compras') ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= strpos($_SERVER['REQUEST_URI'],'compras') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-shopping-basket"></i>
                        <p>
                           Pedidos
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= $baseURL ?>/views/modules/compras/index.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Gestionar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $baseURL ?>/views/modules/compras/create.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Registrar</p>
                            </a>
                        </li>

                    </ul>
                </li>
                </li>


            </ul>

        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>