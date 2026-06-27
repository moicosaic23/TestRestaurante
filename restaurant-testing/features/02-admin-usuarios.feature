Feature: Gestion de usuarios y accesos

  Background:
    Given el usuario ha iniciado sesion como "admin"

  Scenario: CP-013 Administrador aprueba usuario pendiente y asigna rol Camarero
    Given existe un usuario nuevo pendiente para rol "waiter"
    When el administrador aprueba al usuario pendiente como "waiter"
    Then el usuario debe figurar como aprobado en Gestionar Usuarios

  Scenario: CP-014 Administrador asigna rol Cocinero a usuario aprobado
    Given existe un usuario nuevo pendiente para rol "cook"
    When el administrador aprueba al usuario pendiente como "cook"
    Then el usuario debe figurar con rol "cook" en Gestionar Usuarios

  Scenario: CP-015 Administrador cambia rol de Camarero a Cocinero
    When el administrador cambia el rol del usuario "qa_role_change" a "cook"
    Then el usuario "qa_role_change" debe figurar con rol "cook" en Gestionar Usuarios

  Scenario: CP-016 Usuario no aprobado no puede iniciar sesion normalmente
    When cierra sesion
    And intenta iniciar sesion con usuario "qa_pending" y contrasena "qa_pending123"
    Then el sistema muestra la pantalla de espera de aprobacion

  Scenario: CP-061 Camarero no puede acceder a rutas del Administrador via URL directa
    When cierra sesion
    And inicia sesion como "waiter"
    And navega directamente a la ruta "admin/dashboard"
    Then debe ser redirigido al login

  Scenario: CP-062 Cocinero no puede acceder a panel de Camarero
    When cierra sesion
    And inicia sesion como "cook"
    And navega directamente a la ruta "waiter/dashboard"
    Then debe ser redirigido al login

  Scenario: CP-076 Usuario sin sesion activa es redirigido al Login al acceder a ruta protegida
    When cierra sesion
    And navega directamente a la ruta "admin/dashboard"
    Then debe ser redirigido al login

  Scenario: CP-077 Administrador tiene acceso exclusivo a Gestionar Usuarios
    When abre la gestion de usuarios
    Then debe ver la tabla de usuarios

  Scenario: CP-085 Tabla Gestionar Usuarios muestra correctamente estado de aprobacion
    When abre la gestion de usuarios
    Then la tabla de usuarios muestra estados de aprobacion

  Scenario: CP-090 Rol Administrador visualiza opciones que no existen para Camarero ni Cocinero
    When abre el panel de administrador
    Then debe ver opciones exclusivas de administrador

