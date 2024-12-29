<?php
ob_start();
session_start();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.5/css/select.dataTables.min.css">
    <link rel="stylesheet" href="css/main.css">
    <?php

    require 'lib/phpPasswordHashing/passwordLib.php';
    require 'app/DB.php';
    require 'app/Util.php';
    require 'app/models/StatusEnum.php';
    require 'app/models/RequirementEnum.php';
    require 'app/dao/CustomerDAO.php';
    require 'app/dao/BookingDetailDAO.php';
    require 'app/models/Customer.php';
    require 'app/models/Booking.php';
    require 'app/models/Reservation.php';
    require 'app/handlers/CustomerHandler.php';
    require 'app/handlers/BookingDetailHandler.php';

    $username = null;
    $isSessionExists = $isAdmin = false;
    $pendingReservation = $confirmedReservation = $totalCustomers = $totalReservations = null;
    $allBookings = $cCommon = $allCustomer = null;
    if (isset($_SESSION["username"]))
    {
        $username = $_SESSION["username"];
        $isSessionExists = true;

        $cHandler = new CustomerHandler();
        $cHandler = $cHandler->getCustomerObj($_SESSION["accountEmail"]);

        $cAdmin = new Customer();
        $cAdmin->setEmail($cHandler->getEmail());

        // display all reservations
        $bdHandler = new BookingDetailHandler();
        $allBookings = $bdHandler->getAllBookings();
        $cCommon = new CustomerHandler();
        $allCustomer = $cCommon->getAllCustomer();

        // reservation stats
        $pendingReservation = $bdHandler->getPending();
        $confirmedReservation = $bdHandler->getConfirmed();
        $totalCustomers = $cCommon->totalCustomersCount();
        $totalReservations = count($bdHandler->getAllBookings());
    }
    if (isset($_SESSION["isAdmin"]) && isset($_SESSION["username"])) {
        $isSessionExists = true;
        $username = $_SESSION["username"];
        $isAdmin = $_SESSION["isAdmin"];
    }

    ?>

    <title>Manage Booking</title>
</head>
<body>

<header>
    <div class="bg-dark collapse" id="navbarHeader" style="">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-md-7 py-4">
                    <h4 class="text-white">Acerca de</h4>
                    <p class="text-muted">Esperamos darte la bienvenida pronto en Hotel Azul, donde la comodidad y la satisfacción de nuestros huéspedes son nuestra prioridad.</p>
                </div>
                <div class="col-sm-4 offset-md-1 py-4 text-right">
                    <!-- User full name or email if logged in -->
                    <?php if ($isSessionExists) { ?>
                    <h4 class="text-white"><?php echo $username; ?></h4>
                    <ul class="list-unstyled">
                        <li><a href="index.php" class="text-white">Inicio</a><i class="fas fa-home ml-2"></i></a></li>
                        <li><a href="#" id="sign-out-link" class="text-white">Desconectar<i class="fas fa-sign-out-alt ml-2"></i></a></li>
                    </ul>
                    <?php } else { ?>
                    <h4>
                        <a class="text-white" href="sign-in.php">Iniciar sesión</a> <span class="text-white">or</span>
                        <a href="register.php" class="text-white">Registro </a>
                    </h4>
                    <p class="text-muted">Inicia sesión para que puedas aprovechar nuestros precios de habitaciones de hotel.</p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="navbar navbar-dark bg-dark box-shadow">
        <div class="container d-flex justify-content-between">
            <a href="#" class="navbar-brand d-flex align-items-center">
                <i class="fas fa-h-square mr-2"></i>
                <strong>Hotel cielo azul</strong>
            </a>
            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </div>
</header>

<main role="main">

    <?php if ($isSessionExists && $isAdmin) { ?>
    <!-- Añade este botón en la ubicación deseada, por ejemplo, en la sección de clientes -->
<div class="my-3">
   <!-- En tu archivo admin.html o index.php -->
<button id="send-email-button" class="btn btn-primary">Enviar correos a todos los usuarios</button>
</div>

    
    
        <div class="container my-3">
        <div class="row">
            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="card text-white bg-primary o-hidden h-100">
                    <div class="card-body">
                        <div class="card-body-icon">
                            <i class="fas fa-address-book"></i>
                        </div>
                        <div class="mr-5"><?php echo $totalReservations; ?> Reservations</div>
                    </div>
                    <a class="card-footer text-white clearfix small z-1" href="#reservation">
                        <span class="float-left">Ver detalles</span>
                        <span class="float-right"><i class="fa fa-angle-right"></i></span>
                    </a>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="card text-white bg-warning o-hidden h-100">
                    <div class="card-body">
                        <div class="card-body-icon">
                            <i class="fas fa-users ml-2"></i>
                        </div>
                        <div class="mr-5"><?php echo $totalCustomers; ?> Clientes</div>
                    </div>
                    <a class="card-footer text-white clearfix small z-1" href="#customers">
                        <span class="float-left">Ver detalles</span>
                        <span class="float-right"><i class="fa fa-angle-right"></i></span>
                    </a>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="card text-white bg-success o-hidden h-100">
                    <div class="card-body">
                        <div class="card-body-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="mr-4"><?php echo $confirmedReservation; ?> Reservas confirmadas</div>
                    </div>
                    <div class="card-footer text-white clearfix small z-1"></div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="card text-white bg-danger o-hidden h-100">
                    <div class="card-body">
                        <div class="card-body-icon">
                            <i class="fa fa-fw fa-support"></i>
                        </div>
                        <div class="mr-5"><?php echo $pendingReservation; ?> Reservas Pendientes</div>
                    </div>
                    <div class="card-footer text-white clearfix small z-1"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="container" id="tableContainer">
        <ul class="nav nav-tabs" id="adminTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="reservation-tab" data-toggle="tab" href="#reservation" role="tab"
                   aria-controls="reservation" aria-selected="true">Reserva</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="customers-tab" data-toggle="tab" href="#customers" role="tab"
                   aria-controls="customers" aria-selected="false">Clientes</a>
            </li>
        </ul>
        <div class="tab-content py-3" id="adminTabContent">
            <div class="tab-pane fade show active" id="reservation" role="tabpanel" aria-labelledby="reservation-tab">
                <table id="reservationDataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th class="text-hide p-0" data-bookId="12">12</th>
                        <th scope="col">Email</th>
                        <th scope="col">Inicio</th>
                        <th scope="col">Fin</th>
                        <th scope="col">Tipo de habitación</th>
                        <th scope="col">Fechas de la reserva</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Notas</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($allBookings)) { ?>
                        <?php   foreach ($allBookings as $k => $v) { ?>
                            <tr>
                                <th scope="row"><?php echo ($k + 1); ?></th>
                                <td class="text-hide p-0" data-id="<?php echo $v["id"]; ?>">
                                    <?php echo $v["id"]; ?>
                                </td>
                                <?php $cid = $v["cid"]; ?>
                                <td><?php echo $cCommon->getCustomerObjByCid($cid)->getEmail(); ?></td>
                                <td><?php echo $v["start"]; ?></td>
                                <td><?php echo $v["end"]; ?></td>
                                <td><?php echo $v["type"]; ?></td>
                                <td><?php echo $v["timestamp"]; ?></td>
                                <td><?php echo $v["status"]; ?></td>
                                <td><?php echo $v["notes"]; ?></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                    </tbody>
                </table>
                <div class="my-3">
                    <div class="row">
                        <div class="col-6">
                            <label class="text-secondary font-weight-bold">Celda selecionada:</label>
                            <button type="button" id="confirm-booking" class="btn btn-outline-success btn-sm">Confirmar
                            </button>
                            <button type="button" id="cancel-booking" class="btn btn-outline-danger btn-sm">Cancelar
                            </button>
                        </div>
                        <div class="col-6 text-right">
                             Vista:
                            <input type="radio" name="viewOption" value="confirmed">&nbsp;Confirmado&nbsp;
                            <input type="radio" name="viewOption" value="pending">&nbsp;Pendiente
                            <input type="radio" name="viewOption" value="all">&nbsp;Todo
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="customers" role="tabpanel" aria-labelledby="customers-tab">
                <table id="customerTable" class="table table-bordered">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre completo</th>
                        <th scope="col">Correo electrónico</th>
                        <th scope="col">Teléfono</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($allCustomer)) { ?>
                        <?php foreach ($cCommon->getAllCustomer() as $key => $value) { ?>
                        <tr>
                            <td scope="row"><?php echo ($key + 1); ?></td>
                            <td><?php echo $value->getFullName(); ?></td>
                            <td><?php echo $value->getEmail(); ?></td>
                            <td><?php echo $value->getPhone(); ?></td>
                        </tr>
                        <?php } ?>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar reserva(s) seleccionada(s)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro de que desea continuar con esta acción?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="confirmTrue">Yes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cancelar reserva(s) seleccionada(s)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro de que desea continuar con esta acción?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="cancelTrue">Yes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>

    <?php } ?>

</main>

<footer class="container">
    <p>&copy; Compania 2023-2024</p>
</footer>

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>

<script defer src="https://use.fontawesome.com/releases/v5.0.10/js/all.js"
        integrity="sha384-slN8GvtUJGnv6ca26v8EzVaR9DC58QEwsIk9q1QXdCU8Yu8ck/tL/5szYlBbqmS+"
        crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/select/1.2.5/js/dataTables.select.min.js"></script>
<script src="js/form-submission.js"></script>
<script src="js/admin.js"></script>
</body>
</html>