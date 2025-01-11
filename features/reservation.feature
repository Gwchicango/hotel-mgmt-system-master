Feature: Realizar Reserva

  Scenario: Usuario realiza una reserva con éxito
    Given que estoy en la página de reservas
    And soy un cliente autenticado
    When selecciono "2023-12-25" en el campo "fecha"
    And selecciono "2" en el campo "personas"
    And hago clic en el botón "Reservar"
    Then debería ser redirigido a la página de reservas completadas
    And debería ver el texto "Reserva realizada con éxito"
    And debería ver una tabla con las reservas

  Scenario: Usuario intenta realizar una reserva sin autenticarse
    Given que estoy en la página de reservas
    When selecciono "2023-12-25" en el campo "fecha"
    And selecciono "2" en el campo "personas"
    And hago clic en el botón "Reservar"
    Then debería ser redirigido a la página de inicio de sesión

  Scenario: Usuario realiza una reserva sin seleccionar fecha
    Given que estoy en la página de reservas
    And soy un cliente autenticado
    When selecciono "" en el campo "fecha"
    And selecciono "2" en el campo "personas"
    And hago clic en el botón "Reservar"
    Then debería ver el texto "Por favor, seleccione una fecha"

  Scenario: Usuario realiza una reserva sin seleccionar número de personas
    Given que estoy en la página de reservas
    And soy un cliente autenticado
    When selecciono "2023-12-25" en el campo "fecha"
    And selecciono "" en el campo "personas"
    And hago clic en el botón "Reservar"
    Then debería ver el texto "Por favor, seleccione el número de personas"