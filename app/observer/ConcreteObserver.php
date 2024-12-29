<?php
// observer/ConcreteObserver.php
class ConcreteObserver implements Observer {
    private $email;

    public function __construct($email) {
        $this->email = $email;
    }

    public function update($message) {
        // Envía el correo
        $subject = 'Notificación de Sistema';
        $headers = 'From: no-reply@tudominio.com' . "\r\n" .
                   'Reply-To: no-reply@tudominio.com' . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();
        mail($this->email, $subject, $message, $headers);
    }
}
?>
