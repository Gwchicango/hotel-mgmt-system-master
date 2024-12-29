<?php
session_start();
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

$isSessionExists = false;
$username = '';
$cBookings = [];

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
    $cHandler = new CustomerHandler();
    $customer = $cHandler->getCustomerObj($_SESSION["accountEmail"]);
    $bdHandler = new BookingDetailHandler();
    $cBookings = $bdHandler->getCustomerBookings($customer); // Obtener las reservas del cliente
    $isSessionExists = true;
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.5/css/select.dataTables.min.css">
    <title>My Reservations</title>
</head>
<body>

<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Hotel Cielo Azul</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <?php if ($isSessionExists) { ?>
                    <li class="nav-item">
                        <span class="navbar-text text-white mr-3">Bienvenido, <?php echo $username; ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="completed-reservations.php">Mis Reservas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sign-out.php">Cerrar Sesión</a>
                    </li>
                <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="sign-in.php">Iniciar Sesión</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Registrarse</a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </nav>
</header>

<main role="main" class="container my-4">
    <h1>Mis Reservas</h1>
    <div class="container my-3" id="my-reservations-div">
        <h4>Reservas</h4>
        <table id="myReservationsTbl" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th class="text-hide p-0" data-bookId="12">12</th>
                <th scope="col">Fecha de inicio</th>
                <th scope="col">Fecha de finalización</th>
                <th scope="col">Tipo de habitación</th>
                <th scope="col">Requisitos</th>
                <th scope="col">Adultos</th>
                <th scope="col">Niños</th>
                <th scope="col">Peticiones</th>
                <th scope="col">Marca temporal</th>
                <th scope="col">Estado</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($cBookings) && $bdHandler->getExecutionFeedback() == 1) { ?>
                <?php foreach ($cBookings as $k => $v) { ?>
                    <tr>
                        <th scope="row"><?php echo ($k + 1); ?></th>
                        <td class="text-hide p-0"><?php echo htmlspecialchars($v["id"] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($v["start"] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($v["end"] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($v["type"] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($v["requirement"] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($v["adults"] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($v["children"] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($v["requests"] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($v["timestamp"] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($v["status"] ?? ''); ?></td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="11" class="text-center">No se encontraron reservas.</td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</main>

<footer class="container">
    <p>&copy; Company 2023-2024</p>
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/select/1.2.5/js/dataTables.select.min.js"></script>
<script>
    $(document).ready(function () {
        $('#myReservationsTbl').DataTable();
    });
</script>
</body>
</html>