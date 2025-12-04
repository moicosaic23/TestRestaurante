<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Order.php';

class ApiController extends Controller {
    private $orderModel;
    public function __construct($config, $db) {
        parent::__construct($config, $db);
        $this->orderModel = new Order($db);
    }

    public function realtimeOrders() {
        header('Content-Type: application/json');
        $orders = $this->orderModel->listAll();
        echo json_encode($orders);
        exit;
    }

    public function orderDetails() {
        header('Content-Type: application/json');
        $id = $_GET['id'] ?? null;
        if (!$id) { echo json_encode(['error'=>'missing id']); exit; }
        $order = $this->orderModel->getById($id);
        echo json_encode($order);
        exit;
    }
}
