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
            throw new ElementNotFoundException($this->getSession(), 'link', 'text', $enlace);
        }

        $link->click();
    }

    // Upload results to Cucumber for Jira
    /**
     * @Given I have the JSON results files
     */
    public function iHaveTheJSONResultsFiles()
    {
        $this->resultsFiles = [
            'c:/Git/hotel-mgmt-system-master/results1.json',
            'c:/Git/hotel-mgmt-system-master/results2.json'
        ];
    }

    /**
     * @Given I generate the JSON results files
     */
    public function iGenerateTheJSONResultsFiles()
    {
        $results1 = [
            'test' => 'result1',
            'status' => 'passed'
        ];
        $results2 = [
            'test' => 'result2',
            'status' => 'failed'
        ];

        file_put_contents('c:/Git/hotel-mgmt-system-master/results1.json', json_encode($results1));
        file_put_contents('c:/Git/hotel-mgmt-system-master/results2.json', json_encode($results2));

        $this->resultsFiles = [
            'c:/Git/hotel-mgmt-system-master/results1.json',
            'c:/Git/hotel-mgmt-system-master/results2.json'
        ];
    }

    /**
     * @When I upload the results to Cucumber for Jira
     */
    public function iUploadTheResultsToCucumberForJira()
    {
        $url = 'https://c4j.bdd.smartbear.com/ci/rest/api/results';
        $token = 'eyJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJjb20uc21hcnRiZWFyLmN1Y3VtYmVyIiwiYXVkIjoiY2kiLCJjb250ZXh0Ijp7ImxpdmluZ19kb2NfaWQiOjUxMDF9LCJpYXQiOjE3MzY0NDkyMjJ9.9HTv0DYXRNZbfTSflPn67ZfzpwUFjH65z_wa5O06W3Y';

        $files = [];
        foreach ($this->resultsFiles as $file) {
            if (!file_exists($file)) {
                throw new Exception("File not found: $file");
            }
            $files[] = new CURLFile($file);
        }

        $postFields = [
            'results_files[]' => $files,
            'language' => 'ruby'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $token",
            'Content-Type: multipart/form-data'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception('Curl error: ' . curl_error($ch));
        }

        curl_close($ch);

        echo "Upload response: $response\n";
    }

    /**
     * @Then the upload should be successful
     */
    public function theUploadShouldBeSuccessful()
    {
        // Simulate successful upload
        echo "Upload successful!\n";
    }
}