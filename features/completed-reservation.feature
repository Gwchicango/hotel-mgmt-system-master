Feature: Ver Reservas Completadas

  Scenario: Usuario ve sus reservas completadas
    Given que estoy en la página de inicio
    When hago clic en el enlace "Iniciar Sesión"
    And ingreso "geor@gmail.com" en el campo "Email"
    And ingreso "admin" en el campo "Contraseña"
    And hago clic en el botón "Sign in"
    Then debería ser redirigido a la página de inicio
    When hago clic en el enlace "Mis Reservas"
    Then debería ser redirigido a la página de reservas completadas
    And debería ver el texto "Mis Reservas"
    And debería ver una tabla con las reservas

  Scenario: Usuario intenta ver reservas completadas sin iniciar sesión
    Given que estoy en la página de inicio
    When hago clic en el enlace "Mis Reservas"
    Then debería ser redirigido a la página de inicio de sesión

  Scenario: Usuario ve mensaje de "No hay reservas" cuando no tiene reservas completadas
    Given que estoy en la página de inicio
    When hago clic en el enlace "Iniciar Sesión"
    And ingreso "geor@gmail.com" en el campo "Email"
    And ingreso "admin" en el campo "Contraseña"
    And hago clic en el botón "Sign in"
    Then debería ser redirigido a la página de inicio
    When hago clic en el enlace "Mis Reservas"
    Then debería ser redirigido a la página de reservas completadas
    And debería ver el texto "No hay reservas"