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
    <title>Reservar</title>
</head>
<body>

<header>
    <div class="navbar navbar-dark bg-dark box-shadow">
        <div class="container d-flex justify-content-between">
            <a href="index.php" class="navbar-brand d-flex align-items-center">
                <i class="fas fa-h-square mr-2"></i>
                <strong>Hotel cielo azul</strong>
            </a>
        </div>
    </div>
</header>

<main role="main">
    <div class="container my-3">
        <h4>Formulario de reservación</h4>
        <?php if ($isSessionExists == 1 && $isAdmin[1] == "false") { ?>
            <form role="form" autocomplete="off" method="post" id="multiStepRsvnForm">
                <div class="rsvnTab">
                    <?php if ($isSessionExists) { ?>
                        <input type="number" name="cid" value="<?php echo $cHandler->getId() ?>" hidden>
                    <?php } ?>
                    <div class="form-group row">
                        <label for="startDate" class="col-sm-3 col-form-label">Entrada</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="startDate" name="startDate" placeholder="11/05/2024" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="endDate" class="col-sm-3 col-form-label">Salida</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="endDate" name="endDate" placeholder="13/05/2024" required>
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-sm-3 col-form-label" for="roomType">Tipo de habitación
                            <span class="red-asterisk"> *</span>
                        </label>
                        <div class="col-sm-9">
                            <select required class="custom-select mr-sm-2"  name="roomType">
                                <option value="<?php echo \models\RequirementEnum::DELUXE; ?>">Habitación de lujo</option>
                                <option value="<?php echo \models\RequirementEnum::DOUBLE; ?>">Doble habitacion</option>
                                <option value="<?php echo \models\RequirementEnum::SINGLE; ?>">Habitación individual</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-sm-3 col-form-label" for="roomRequirement">Requisitos de la habitación</label>
                        <div class="col-sm-9">
                            <select class="custom-select mr-sm-2"  name="roomRequirement">
                                <option value="no preference" selected>Sin preferencias</option>
                                <option value="non smoking">De no fumadores</option>
                                <option value="smoking">De fumadores</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-sm-3 col-form-label" for="adults">Adultos
                            <span class="red-asterisk"> *</span>
                        </label>
                        <div class="col-sm-9">
                            <select required class="custom-select mr-sm-2"  name="adults">
                                <option selected value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-sm-3 col-form-label" for="children">Niños</label>
                        <div class="col-sm-9">
                            <select class="custom-select mr-sm-2"  name="children">
                                <option selected value="0">-</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-sm-3 col-form-label" for="specialRequests">Requisitos especiales</label>
                        <div class="col-sm-9">
                            <textarea rows="3" maxlength="500"  name="specialRequests" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <button type="button" class="btn btn-info" style="margin-left: 0.8em;" data-container="body" data-toggle="popover"
                                data-placement="right" data-content="El horario de registro comienza a las 15:00 horas. Si está previsto un registro tardío, comuníquese con nuestro departamento de soporte.">
                            Políticas de registro
                        </button>
                    </div>
                </div>

                <div class="rsvnTab">
                    <div class="form-group row align-items-center">
                        <label class="col-sm-3 col-form-label font-weight-bold" for="bookedDate">Fecha reservada</label>
                        <div class="col-sm-9 bookedDateTxt">
                            July 13, 2019
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-sm-3 col-form-label font-weight-bold" for="roomPrice">Precio de la habitación</label>
                        <div class="col-sm-9 roomPriceTxt">235.75</div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-sm-3 col-form-label font-weight-bold" for="numNights"><span class="numNightsTxt">3</span> nights </label>
                        <div class="col-sm-9">
                            $<span class="roomPricePerNightTxt">69.63</span> avg. / noche
                        </div>
                        <label class="col-sm-3 col-form-label font-weight-bold" for="numNights">Desde - hasta</label>
                        <div class="col-sm-9 fromToTxt">
                            Mon. July 4 to Wed. July 6
                        </div>
                        <label class="col-sm-3 col-form-label font-weight-bold">Impuestos </label>
                        <div class="col-sm-9">
                            $<span class="taxesTxt">0</span>
                        </div>
                        <label class="col-sm-3 col-form-label font-weight-bold">Total </label>
                        <div class="col-sm-9">
                            $<span class="totalTxt">0.00</span>
                        </div>
                    </div>
                </div>

                <div style="text-align:center;margin-top:40px;">
                    <span class="step"></span>
                    <span class="step"></span>
                </div>

            </form>
            <div style="overflow:auto;">
                <div style="float:right;">
                    <button type="button" class="btn btn-success" id="rsvnPrevBtn" onclick="rsvnNextPrev(-1)">Previous</button>
                    <button type="button" class="btn btn-success" id="rsvnNextBtn" onclick="rsvnNextPrev(1)" readySubmit="false">Next</button>
                </div>
                <div style="float:left;">
                    <a href="index.php" class="btn btn-secondary">Regresar al inicio</a>
                </div>
            </div>
        <?php } else { ?>
            <p>La reserva está reservada a los clientes..</p>
        <?php } ?>
    </div>
</main>

<footer class="container">
    <p>&copy; Company 2023-2024</p>
</footer>
<script src="js/utilityFunctions.js"></script>

<script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>

<script defer src="https://use.fontawesome.com/releases/v5.0.10/js/all.js"
        integrity="sha384-slN8GvtUJGnv6ca26v8EzVaR9DC58QEwsIk9q1QXdCU8Yu8ck/tL/5szYlBbqmS+"
        crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/select/1.2.5/js/dataTables.select.min.js"></script>
<script src="js/animatejscx.js"></script>
<script src="js/form-submission.js"></script>
<script>
    $(document).ready(function () {
      // check-in policies popover
      $('[data-toggle="popover"]').popover();
    });
</script>
<script src="js/multiStepsRsvn.js"></script>
</body>
</html>