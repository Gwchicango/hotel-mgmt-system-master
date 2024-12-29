<?php
ob_start();
session_start();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <link rel="apple-touch-icon" sizes="180x180" href="image/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="image/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="image/favicon/favicon-16x16.png">
    <link rel="manifest" href="image/favicon/site.webmanifest">
    <link rel="mask-icon" href="image/favicon/safari-pinned-tab.svg" color="#5bbad5">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.5/css/select.dataTables.min.css">
    <link rel="stylesheet" href="css/main.css">

    <?php

    require 'lib/phpPasswordHashing/passwordLib.php';
    require 'app/DB.php';
    require 'app/Util.php';
    require 'app/dao/CustomerDAO.php';
    require 'app/dao/BookingDetailDAO.php';
    require 'app/models/RequirementEnum.php';
    require 'app/models/Customer.php';
    require 'app/models/Booking.php';
    require 'app/models/Reservation.php';
    require 'app/handlers/CustomerHandler.php';
    require 'app/handlers/BookingDetailHandler.php';

    $username = $cHandler = $bdHandler = $cBookings = null;
    $isSessionExists = false;
    $isAdmin = [];
    if (isset($_SESSION["username"])) {
        $username = $_SESSION["username"];

        $cHandler = new CustomerHandler();
        $cHandler = $cHandler->getCustomerObj($_SESSION["accountEmail"]);
        $cAdmin = new Customer();
        $cAdmin->setEmail($cHandler->getEmail());

        $bdHandler = new BookingDetailHandler();
        $cBookings = $bdHandler->getCustomerBookings($cHandler);
        $isSessionExists = true;
        $isAdmin = $_SESSION["authenticated"];
    }
    if (isset($_SESSION["isAdmin"]) && isset($_SESSION["username"])) {
        $isSessionExists = true;
        $username = $_SESSION["username"];
        $isAdmin = $_SESSION["isAdmin"];
    }

    ?>
    <title>Home</title>
</head>
<body>

<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-h-square mr-2"></i>
                Hotel Cielo Azul
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ml-auto">
                    <?php if ($isSessionExists) { ?>
                        <li class="nav-item">
                            <span class="navbar-text text-white mr-3">Bienvenido, <?php echo $username; ?></span>
                        </li>
                        <?php if ($isAdmin[1] == "true" && isset($_COOKIE['is_admin']) && $_COOKIE['is_admin'] == "true") { ?>
                            <li class="nav-item"><a href="admin.php" class="nav-link">Gestionar Reservas</a></li>
                        <?php } else { ?>
                            <li class="nav-item"><a href="completed-reservations.php" class="nav-link">Mis Reservas</a></li>
                            <li class="nav-item"><a href="#" class="nav-link" data-toggle="modal" data-target="#myProfileModal">Perfil</a></li>
                        <?php } ?>
                        <li class="nav-item"><a href="process_logout.php" class="nav-link">Cerrar Sesión</a></li>
                    <?php } else { ?>
                        <li class="nav-item"><a href="sign-in.php" class="nav-link">Iniciar Sesión</a></li>
                        <li class="nav-item"><a href="register.php" class="nav-link">Registrarse</a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>
</header>

<main role="main">
    <section class="jumbotron text-center">
        <div class="container">
            <h1 class="jumbotron-heading">Un hotel nuevo más allá de lo común</h1>
            <p class="lead text-muted">Reserva ya tus vacaciones de verano con nosotros.</p>
            <p>
                <?php if ($isSessionExists) { ?>
                    <a href="reservation.php" class="btn btn-success my-2">Reservar Ahora</a>
                <?php } else { ?>
                    <a href="sign-in.php" class="btn btn-success my-2">Reservar Ahora</a>
                <?php } ?>
            </p>
        </div>
    </section>

    <div class="container">
        <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
            <h1 class="display-4">Precios</h1>
            <p class="lead">Encuentra tu hogar lejos de casa con nuestras cómodas y asequibles habitaciones en el corazón de la ciudad.</p>
        </div>
    </div>

    <div class="album py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="card mb-4 box-shadow">
                        <img class="card-img-top" src="image/deluxe.jpg" alt="Habitación de lujo">
                        <div class="card-body">
                            <h5 class="card-title">Habitación de lujo</h5>
                            <p class="card-text">El santuario definitivo para recargar los sentidos, la habitación Deluxe de 24 metros cuadrados, bellamente decorada, irradia pura sofisticación y elegancia.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <?php if ($isSessionExists) { ?>
                                    <button type="button" class="btn btn-sm btn-outline-success" data-rtype="Deluxe" data-toggle="modal" data-target=".book-now-modal-lg">Comprar</button>
                                <?php } else { ?>
                                    <button type="button" class="btn btn-sm btn-outline-success" data-toggle="modal" data-target=".sign-in-to-book-modal">Comprar</button>
                                <?php } ?>
                                <small class="text-muted">$250 / noche</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-4 box-shadow">
                        <img class="card-img-top" src="image/double.jpg" alt="Habitación doble">
                        <div class="card-body">
                            <h5 class="card-title">Habitación doble</h5>
                            <p class="card-text">La habitación doble estándar está equipada con dos camas individuales para alojar a dos personas. Un atractivo conjunto de instalaciones de primer nivel con un nivel de seguridad óptimo.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <?php if ($isSessionExists) { ?>
                                    <button type="button" class="btn btn-sm btn-outline-success" data-rtype="Double" data-toggle="modal" data-target=".book-now-modal-lg">Comprar</button>
                                <?php } else { ?>
                                    <button type="button" class="btn btn-sm btn-outline-success" data-toggle="modal" data-target=".sign-in-to-book-modal">Comprar</button>
                                <?php } ?>
                                <small class="text-muted">$180 / noche</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-4 box-shadow">
                        <img class="card-img-top" src="image/single.jpg" alt="Habitación individual">
                        <div class="card-body">
                            <h5 class="card-title">Habitación individual</h5>
                            <p class="card-text">Habitación individual de tamaño modesto con baño privado con ducha y/o bañera, secador de pelo y artículos de tocador. Las comodidades incluyen WiFi gratuito, teléfono, minibar y TV de pantalla plana.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <?php if ($isSessionExists) { ?>
                                    <button type="button" class="btn btn-sm btn-outline-success" data-rtype="Single" data-toggle="modal" data-target=".book-now-modal-lg">Comprar</button>
                                <?php } else { ?>
                                    <button type="button" class="btn btn-sm btn-outline-success" data-toggle="modal" data-target=".sign-in-to-book-modal">Comprar</button>
                                <?php } ?>
                                <small class="text-muted">$130 / noche</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal sign-in-to-book-modal" tabindex="-1" role="dialog" aria-labelledby="signInToBookModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Inicio de sesión requerido</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4>Tienes que <a href="sign-in.php">iniciar sesión</a> para reservar una habitación.</h4>
                </div>
            </div>
        </div>
    </div>

    <?php if(($isSessionExists == 1 && $isAdmin[1] == "false") && isset($_COOKIE['is_admin']) && $_COOKIE['is_admin'] == "false") : ?>
    <div class="modal" id="myProfileModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Actualización del perfil</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card border-0">
                        <div class="card-body p-0">
                            <?php if ($isSessionExists) { ?>
                            <form class="form" role="form" autocomplete="off" id="update-profile-form" method="post">
                                <input type="number" id="customerId" hidden
                                       name="customerId" value="<?php echo $cHandler->getId(); ?>" >
                                <div class="form-group">
                                    <label for="updateFullName">Nombre completo</label>
                                    <input type="text" class="form-control" id="updateFullName"
                                           name="updateFullName" value="<?php echo $cHandler->getFullName(); ?>" >
                                </div>
                                <div class="form-group">
                                    <label for="updatePhoneNumber">Número de teléfono</label>
                                    <input type="text" class="form-control" id="updatePhoneNumber"
                                           name="updatePhoneNumber" value="<?php echo $cHandler->getPhone(); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="updateEmail">Email</label>
                                    <input type="email" class="form-control" id="updateEmail"
                                           name="updateEmail" value="<?php echo $cHandler->getEmail(); ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="updatePassword">Nueva contraseña</label>
                                    <input type="password" class="form-control" id="updatePassword"
                                           name="updatePassword"
                                           title="Al menos 4 caracteres con letras y números">
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary btn-md float-right"
                                           name="updateProfileSubmitBtn" value="Actualizar">
                                </div>
                            </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

</main>

<footer class="container">
    <p>&copy; Company 2023-2024</p>
</footer>
<script src="js/utilityFunctions.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>