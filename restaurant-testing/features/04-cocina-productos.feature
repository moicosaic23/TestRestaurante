Feature: Cocina y productos

  Scenario: CP-031 Panel Cocinero muestra pedidos Pendientes al ingresar
    Given el usuario ha iniciado sesion como "cook"
    When abre el panel del cocinero
    Then debe ver la seccion de pedidos pendientes

  Scenario: CP-032 Panel Cocinero distingue Pendientes de En Preparacion
    Given el usuario ha iniciado sesion como "cook"
    When abre el panel del cocinero
    Then debe ver secciones de pendientes y preparacion

  Scenario: CP-034 Cocinero cambia estado de pedido a En Preparacion exitosamente
    Given el usuario ha iniciado sesion como "cook"
    When marca el primer pedido pendiente como en preparacion
    Then el pedido debe aparecer en la seccion En Preparacion

  Scenario: CP-040 Administrador deshabilita plato del catalogo con razon documentada
    Given el usuario ha iniciado sesion como "admin"
    When deshabilita el plato "QA Plato temporal" con razon "QA sin stock temporal"
    Then el plato "QA Plato temporal" debe figurar deshabilitado

  Scenario: CP-042 Plato deshabilitado no aparece en productos disponibles para Camarero
    Given el usuario ha iniciado sesion como "waiter"
    When abre el formulario de crear pedido
    Then el producto "QA Producto deshabilitado" no debe estar disponible para pedido
