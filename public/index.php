<?php

date_default_timezone_set('America/Lima');

// Front controller simple
$configPath = __DIR__ . '/../app/config.php';

if (!file_exists($configPath)) {
    die("❌ NO SE ENCUENTRA EL ARCHIVO DE CONFIGURACIÓN: $configPath");
}

$config = include $configPath;

if (!$config || !isset($config['db'])) {
    die("❌ CONFIG.PHP CARGADO PERO VACÍO O MAL FORMADO");
}


// carga automática simple
spl_autoload_register(function($class) {
    $paths = [
        __DIR__ . '/../app/core/' . $class . '.php',
        __DIR__ . '/../app/controllers/' . $class . '.php',
        __DIR__ . '/../app/models/' . $class . '.php'
    ];
    foreach ($paths as $p) {
        if (file_exists($p)) require_once $p;
    }
});

$dbInstance = Database::getInstance($config);
$db = $dbInstance->getConnection();

// router básico por param route
$route = $_GET['route'] ?? 'auth/login';
$parts = explode('/', $route);
$controller = $parts[0];
$action = $parts[1] ?? 'index';

// mapeo manual
switch ($controller) {
    case 'auth':
        $c = new AuthController($config, $db);
        if ($action === 'login') $c->login();
        elseif ($action === 'register') $c->register();
        elseif ($action === 'logout') $c->logout();
        else $c->login();
        break;
    case 'admin':
        $c = new AdminController($config, $db);
        if ($action === 'dashboard') $c->dashboard();
        elseif ($action === 'users') $c->users();
        elseif ($action === 'products') $c->products();
        elseif ($action === 'order_detail') $c->orderDetail(); 
        else $c->dashboard();
        break;
    case 'waiter':
        $c = new WaiterController($config, $db);
        if ($action === 'dashboard' || $action === 'orders') $c->dashboard();
        elseif ($action === 'create') $c->create();
        elseif ($action === 'edit') $c->edit();
        elseif ($action === 'cancel') $c->cancel();
        elseif ($action === 'pay') $c->pay();
        elseif ($action === 'showCancelForm') $c->showCancelForm();
        elseif ($action === 'orderDetail') $c->orderDetail();
        elseif ($action === 'payComplete') $c->payComplete(); 
        else $c->dashboard();
        break;
    case 'cook':
        $c = new CookController($config, $db);
        if ($action === 'dashboard') $c->dashboard();
        elseif ($action === 'createProduct') $c->createProduct();
        elseif ($action === 'disableProduct') $c->disableProduct();
        elseif ($action === 'markPreparing') $c->markPreparing();
        elseif ($action === 'markDelivered') $c->markDelivered();
        elseif ($action === 'showCreateProductForm') $c->showCreateProductForm();
        elseif ($action === 'showDisableProduct') $c->showDisableProduct();
        else $c->dashboard();
        break;
    case 'api':
        $c = new ApiController($config, $db);
        if ($action === 'realtimeOrders') $c->realtimeOrders();
        elseif ($action === 'orderDetails') $c->orderDetails();
        else $c->realtimeOrders();
        break;
    default:
        header("Location: {$config['base_url']}/?route=auth/login");
        break;
}
