Feature: Realizar Reserva

  Scenario: Usuario realiza una reserva con éxito
    Given que estoy en la página de reservas
    And soy un cliente autenticado
    And ingreso "2" en el campo "personas"
    And hago clic en el botón "Reservar"
    Then debería ser redirigido a la página de reservas completadas
    And debería ver el texto "Reserva realizada con éxito"
    And debería ver una tabla con las reservas

  Scenario: Usuario intenta realizar una reserva sin autenticarse
    Given que estoy en la página de reservas
    And ingreso "2" en el campo "personas"
    And hago clic en el botón "Reservar"
    Then debería ser redirigido a la página de inicio de sesión
