<?php

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Mink\Exception\ElementNotFoundException;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    // Inicio de sesión
    /**
     * @Given que estoy en la página de inicio
     */
    public function queEstoyEnLaPaginaDeInicio()
    {
        $this->visit('/');
    }

    /**
     * @Given que estoy en la página de inicio de sesión
     */
    public function queEstoyEnLaPaginaDeInicioDeSesion()
    {
        $this->visit('/sign-in.php');
    }

    /**
     * @When ingreso :valor en el campo :campo
     */
    public function ingresoEnElCampo($valor, $campo)
    {
        $this->fillField($campo, $valor);
    }

    /**
     * @When hago clic en el botón :boton
     */
    public function hagoClicEnElBoton($boton)
    {
        $this->pressButton($boton);
    }

    /**
     * @When hago clic en el botón de menú
     */
    public function hagoClicEnElBotonDeMenu()
    {
        $this->getSession()->getPage()->find('css', '.navbar-toggler')->click();
    }

    /**
     * @Then debería ser redirigido a la página de inicio
     */
    public function deberiaSerRedirigidoALaPaginaDeInicio()
    {
        $this->assertPageAddress('/index.php');
    }

    // Registro
    /**
     * @Given que estoy en la página de registro
     */
    public function queEstoyEnLaPaginaDeRegistro()
    {
        $this->visit('/register.php');
    }

    /**
     * @When ingreso :valor en el campo de registro :campo
     */
    public function ingresoEnElCampoDeRegistro($valor, $campo)
    {
        $this->fillField($campo, $valor);
    }

    /**
     * @When hago clic en el botón de registro :boton
     */
    public function hagoClicEnElBotonDeRegistro($boton)
    {
        $this->pressButton($boton);
    }

    /**
     * @Then debería ver el mensaje :mensaje
     */
    public function deberiaVerElMensaje($mensaje)
    {
        $this->assertPageContainsText($mensaje);
    }

    // Reserva
    /**
     * @Given que estoy en la página de reservas
     */
    public function queEstoyEnLaPaginaDeReservas()
    {
        $this->visit('/reservation.php');
    }

    /**
     * @Given soy un cliente autenticado
     */
    public function soyUnClienteAutenticado()
    {
        // Autenticar al cliente
        $this->visit('/sign-in.php');
        $this->fillField('Email', 'geor@gmail.com');
        $this->fillField('Contraseña', 'admin');
        $this->pressButton('Sign in');
    
        // Redirigir a la página de reservas
        $this->visit('/reservation.php');
    }

    /**
     * @When selecciono :valor en el campo :campo
     */
    public function seleccionoEnElCampo($valor, $campo)
    {
        $this->selectOption($campo, $valor);
    }

    /**
     * @Then debería ser redirigido a la página de reservas completadas
     */
    public function deberiaSerRedirigidoALaPaginaDeReservasCompletadas()
    {
        $this->assertPageAddress('/completed-reservations.php');
    }

    /**
     * @Then debería ver el texto :texto
     */
    public function deberiaVerElTexto($texto)
    {
        $this->assertPageContainsText($texto);
    }

    /**
     * @Then debería ver una tabla con las reservas
     */
    public function deberiaVerUnaTablaConLasReservas()
    {
        $this->assertElementOnPage('table#myReservationsTbl');
    }

    /**
     * @When hago clic en el enlace :enlace
     */
    public function hagoClicEnElEnlace($enlace)
    {
        $page = $this->getSession()->getPage();
        $link = $page->findLink($enlace);

        if (null === $link) {
            // Imprime el contenido de la página para depuración
            //echo $page->getContent();
            throw new ElementNotFoundException($this->getSession(), 'link', 'text', $enlace);
        }

        $link->click();
    }
}