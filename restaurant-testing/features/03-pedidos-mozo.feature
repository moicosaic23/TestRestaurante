Feature: Gestion de pedidos del camarero

  Background:
    Given el usuario ha iniciado sesion como "waiter"

  Scenario: CP-019 Crear pedido exitosamente con un producto seleccionado
    When crea un pedido con el producto "QA Ceviche" y cantidad 1
    Then el pedido debe aparecer en Pedidos Actuales

  Scenario: CP-020 Crear pedido con multiples productos y verificar total
    When crea un pedido con los productos:
      | producto            | cantidad |
      | QA Ceviche          | 1        |
      | QA Aji de gallina   | 2        |
    Then el pedido debe aparecer en Pedidos Actuales

  Scenario: CP-024 Solo se muestran productos habilitados al crear pedido
    When abre el formulario de crear pedido
    Then el producto "QA Ceviche" debe estar disponible para pedido
    And el producto "QA Producto deshabilitado" no debe estar disponible para pedido

  Scenario: CP-025 Modificar pedido pendiente agregando un producto
    Given existe un pedido pendiente visible para el camarero
    When edita el primer pedido pendiente y agrega el producto "QA Arroz con pollo" con cantidad 1
    Then el pedido editado debe guardar los cambios correctamente

  Scenario: CP-026 Modificar pedido pendiente eliminando un producto
    Given existe un pedido pendiente visible para el camarero
    When edita el primer pedido pendiente y elimina un producto
    Then el pedido editado debe guardar los cambios correctamente

  Scenario: CP-028 Cancelar pedido en estado Pendiente con motivo valido
    Given existe un pedido pendiente visible para el camarero
    When cancela el primer pedido pendiente con motivo "QA cancelacion automatizada"
    Then el pedido cancelado aparece en la seccion Pedidos Cancelados

  Scenario: CP-029 Intento de cancelar pedido sin ingresar motivo de cancelacion
    Given existe un pedido pendiente visible para el camarero
    When intenta cancelar el primer pedido pendiente sin motivo
    Then el navegador impide enviar la cancelacion sin motivo

