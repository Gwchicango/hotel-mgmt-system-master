<?php
// observer/sendEmails.php

require '../app/DB.php';
require '../app/dao/CustomerDAO.php';
require '../app/models/Customer.php';
require 'Observer.php';
require 'Subject.php';
require 'ConcreteSubject.php';
require 'ConcreteObserver.php';

header('Content-Type: application/json');

$response = array('status' => 'error', 'message' => 'Error al enviar los correos.');

try {
    $customerDAO = new CustomerDAO();
    $customers = $customerDAO->getAllCustomers();

    $subject = new ConcreteSubject();
    $message = 'Contenido del correo.';

    foreach ($customers as $customer) {
        $observer = new ConcreteObserver($customer->getEmail());
        $subject->attach($observer);
    }

    // Debugging: Verificar los observadores adjuntos
    echo 'Observadores adjuntos: ' . count($subject->observers);

    $subject->setMessage($message);

    $response['status'] = 'success';
    $response['message'] = 'Correos enviados con éxito.';
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

// Debugging: Verificar la respuesta antes de enviar
var_dump($response);

echo json_encode($response);
?>
