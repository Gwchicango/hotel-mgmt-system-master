Feature: Registro de usuario

  Scenario: Usuario se registra con éxito
    Given que estoy en la página de registro
    When ingreso "John Doe" en el campo "Nombre"
    And ingreso "1234567890" en el campo "Celular"
    And ingreso "john.doe@example.com" en el campo "Email"
    And ingreso "password123" en el campo "Contraseña"
    And ingreso "password123" en el campo "Confirmar contraseña"
    And hago clic en el botón "Submit"
    Then debería ver el mensaje "You have successfully registered! You can now login."

  Scenario: Usuario intenta registrarse con un email ya registrado
    Given que estoy en la página de registro
    When ingreso "Jane Doe" en el campo "Nombre"
    And ingreso "0987654321" en el campo "Celular"
    And ingreso "john.doe@example.com" en el campo "Email"
    And ingreso "password123" en el campo "Contraseña"
    And ingreso "password123" en el campo "Confirmar contraseña"
    And hago clic en el botón "Submit"
    Then debería ver el mensaje "This email is already registered."

  Scenario: Usuario intenta registrarse con contraseñas que no coinciden
    Given que estoy en la página de registro
    When ingreso "Alice Smith" en el campo "Nombre"
    And ingreso "1122334455" en el campo "Celular"
    And ingreso "alice.smith@example.com" en el campo "Email"
    And ingreso "password123" en el campo "Contraseña"
    And ingreso "password321" en el campo "Confirmar contraseña"
    And hago clic en el botón "Submit"
    Then debería ver el mensaje "Passwords do not match."