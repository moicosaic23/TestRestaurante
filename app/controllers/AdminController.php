<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/OrderItem.php';

class AdminController extends Controller {
    private $userModel;
    private $productModel;
    private $orderModel;

    public function __construct($config, $db) {
        parent::__construct($config, $db);
        $this->userModel = new User($db);
        $this->productModel = new Product($db);
        $this->orderModel = new Order($db);
    }

    private function ensureAdmin() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $this->redirect($this->config['base_url'] . '/?route=auth/login');
        }
    }

    public function dashboard() {
        $this->ensureAdmin();

        // FILTROS desde GET
        $filter = [];                 // Filtro para la tabla completa
        $statsFilter = ['status' => 'completed']; // Filtro para estadísticas (solo completados)
        $selectedLabel = 'Hoy';       // Etiqueta que se mostrará en estadísticas

        // Prioridad: mes > día
        if (!empty($_GET['month'])) {
            $filter['month'] = $_GET['month'];
            $statsFilter['month'] = $_GET['month'];
            $selectedLabel = 'Mes de ' . date('F', mktime(0, 0, 0, $_GET['month'], 1));
        } elseif (!empty($_GET['date'])) {
            $filter['date'] = $_GET['date'];
            $statsFilter['date'] = $_GET['date'];
            $selectedLabel = 'Día ' . $_GET['date'];
        } else {
            // predeterminado: pedidos del día
            $today = date('Y-m-d');
            $filter['date'] = $today;
            $statsFilter['date'] = $today;
            $selectedLabel = 'Hoy';
        }

        // Filtrar por estado si se aplica
        if (!empty($_GET['status'])) {
            $filter['status'] = $_GET['status'];
        }

        // Obtener todos los pedidos según el filtro general
        $orders = $this->orderModel->listAll($filter);

        // Estadísticas: solo pedidos completados
        $allCompleted = $this->orderModel->listAll($statsFilter);
        $ordersToday = array_filter($allCompleted, fn($o) => strtolower($o['status']) === 'completed');
        $totalToday = array_sum(array_column($ordersToday, 'total'));

        // Renderizar vista
        $this->view('admin/dashboard.php', [
            'orders' => $orders,
            'ordersToday' => $ordersToday,
            'totalToday' => $totalToday,
            'filter' => $filter,
            'selectedLabel' => $selectedLabel
        ]);
    }

    public function products() {
        $this->ensureAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['enable'])) {
                $id = $_POST['product_id'];
                $price = $_POST['price'];
                $this->productModel->enable($id, $price);
            } elseif (isset($_POST['disable'])) {
                $id = $_POST['product_id'];
                $reason = $_POST['reason'];
                $this->productModel->disable($id, $reason);
            }
            $this->redirect($this->config['base_url'] . '/?route=admin/products');
        }
        $products = $this->productModel->listAll();
        $this->view('admin/products.php', ['products' => $products]);
    }

    public function orderDetail() {
        $this->ensureAdmin();

        if (empty($_GET['id'])) {
            $this->redirect($this->config['base_url'] . '/?route=admin/dashboard');
        }

        $orderId = $_GET['id'];

        $order = $this->orderModel->getById($orderId); // datos generales del pedido
        if (!$order) {
            $this->redirect($this->config['base_url'] . '/?route=admin/dashboard');
        }

        $orderItemModel = new OrderItem($this->db);
        $order['items'] = $orderItemModel->getByOrder($orderId); // obtiene items separados

        $this->view('admin/order_detail.php', ['order' => $order]);
    }

    public function users() {
        $this->ensureAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $action = $_POST['action'];
            if ($action === 'update_role') {
                $id = $_POST['id'];
                $role = $_POST['role'];
                $approved = isset($_POST['approved']) ? 1 : 0;
                $this->userModel->updateRoleAndApprove($id, $role, $approved);
            } elseif ($action === 'update_credentials') {
                $id = $_POST['id'];
                $username = $_POST['username'];
                $password = $_POST['password'] ?? null;
                $this->userModel->updateCredentials($id, $username, $password);
            }
            $this->redirect($this->config['base_url'] . '/?route=admin/users');
        }
        $users = $this->userModel->listAll();
        $this->view('admin/users.php', ['users' => $users]);
    }
}
