Feature: Autenticacion y registro

  Scenario: CP-001 Registro exitoso con todos los campos validos
    Given el usuario esta en la pagina de registro
    When registra un nuevo usuario de prueba con nombre "QA Registro Valido"
    Then el sistema muestra la pantalla de espera de aprobacion

  Scenario: CP-004 Registro fallido por usuario ya existente en el sistema
    Given el usuario esta en la pagina de registro
    When intenta registrarse con usuario existente "qa_waiter"
    Then el sistema muestra error de usuario existente

  Scenario: CP-007 Login exitoso con credenciales validas de Camarero
    Given el usuario esta en la pagina de login
    When inicia sesion como "waiter"
    Then debe ver el panel de pedidos del camarero

  Scenario: CP-008 Login exitoso con credenciales validas de Cocinero
    Given el usuario esta en la pagina de login
    When inicia sesion como "cook"
    Then debe ver el panel del cocinero

  Scenario: CP-009 Login exitoso de Administrador y redireccion correcta
    Given el usuario esta en la pagina de login
    When inicia sesion como "admin"
    Then debe ver el panel de administrador

  Scenario: CP-010 Login fallido con contrasena incorrecta
    Given el usuario esta en la pagina de login
    When intenta iniciar sesion con usuario "qa_admin" y contrasena "incorrecta"
    Then el sistema muestra error de credenciales invalidas

  Scenario: CP-011 Login fallido con campos de usuario y contrasena vacios
    Given el usuario esta en la pagina de login
    When intenta iniciar sesion sin completar credenciales
    Then el navegador mantiene requerido el formulario de login

  Scenario: CP-012 Login fallido con usuario no registrado en el sistema
    Given el usuario esta en la pagina de login
    When intenta iniciar sesion con usuario "qa_no_existe" y contrasena "clave123"
    Then el sistema muestra error de credenciales invalidas

