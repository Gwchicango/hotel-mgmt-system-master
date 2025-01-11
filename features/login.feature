Feature: Iniciar sesión

  Scenario: Usuario inicia sesión con éxito
    Given que estoy en la página de inicio de sesión
    When ingreso "geor@gmail.com" en el campo "Email"
    And ingreso "admin" en el campo "Contraseña"
    And hago clic en el botón "Sign in"
    Then debería ser redirigido a la página de inicio

  Scenario: Usuario intenta iniciar sesión con una contraseña incorrecta
    Given que estoy en la página de inicio de sesión
    When ingreso "geor@gmail.com" en el campo "Email"
    And ingreso "wrongpassword" en el campo "Contraseña"
    And hago clic en el botón "Sign in"
    Then debería ver el mensaje "Invalid credentials"

  Scenario: Usuario intenta iniciar sesión con un email no registrado
    Given que estoy en la página de inicio de sesión
    When ingreso "unknown@gmail.com" en el campo "Email"
    And ingreso "admin" en el campo "Contraseña"
    And hago clic en el botón "Sign in"
    Then debería ver el mensaje "Invalid credentials"