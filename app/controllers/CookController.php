<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/OrderItem.php';

class CookController extends Controller {
    private $productModel;
    private $orderModel;
    private $orderItemModel;
    public function __construct($config, $db) {
        parent::__construct($config, $db);
        $this->productModel = new Product($db);
        $this->orderModel = new Order($db);
        $this->orderItemModel = new OrderItem($db);
    }

    private function ensureCook() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'cook') {
            $this->redirect($this->config['base_url'] . '/?route=auth/login');
        }
    }

    public function dashboard() {
        $this->ensureCook();

        // Pedidos pendientes
        $pendingOrders = $this->orderModel->listAll(['status' => 'pending']);
        foreach ($pendingOrders as &$order) {
            $order['items'] = $this->orderItemModel->getByOrder($order['id']);
        }

        // Pedidos en preparación
        $preparingOrders = $this->orderModel->listAll(['status' => 'preparing']);
        foreach ($preparingOrders as &$order) {
            $order['items'] = $this->orderItemModel->getByOrder($order['id']);
        }

        // Productos
        $products = $this->productModel->listAll();

        $this->view('cook/dashboard.php', [
            'pendingOrders' => $pendingOrders,
            'preparingOrders' => $preparingOrders,
            'products' => $products
        ]);
    }

    public function createProduct() {
        $this->ensureCook();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $desc = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? 0;
            $created_by = $_SESSION['user']['id'];
            $this->productModel->create($name, $desc, $price, $created_by);
            $this->redirect($this->config['base_url'] . '/?route=cook/dashboard');
        }
    }

    public function markPreparing() {
        $this->ensureCook();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
            $orderId = (int)$_POST['order_id'];
            $this->orderModel->updateStatus($orderId, 'preparing');
        }
        $this->redirect($this->config['base_url'] . '/?route=cook/dashboard');
    }

    public function markDelivered() {
        $this->ensureCook();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
            $orderId = (int)$_POST['order_id'];
            $this->orderModel->updateStatus($orderId, 'delivered');
        }
        $this->redirect($this->config['base_url'] . '/?route=cook/dashboard');
    }

    public function showCreateProductForm() {
        $this->ensureCook();
        $this->view('cook/create_product.php');
    }

}
