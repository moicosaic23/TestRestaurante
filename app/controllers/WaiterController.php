<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Order.php';


class WaiterController extends Controller {
    private $productModel;
    private $orderModel;

    public function __construct($config, $db) {
        parent::__construct($config, $db);
        $this->productModel = new Product($db);
        $this->orderModel = new Order($db);
    }

    private function ensureWaiter() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'waiter') {
            $this->redirect($this->config['base_url'] . '/?route=auth/login');
        }
    }

    public function dashboard() {
        $this->ensureWaiter();
        $waiterId = $_SESSION['user']['id'];
        $orders = $this->orderModel->listAll(['waiter_id' => $waiterId]);
        $this->view('waiter/orders.php', ['orders' => $orders]);
    }

    public function create() {
        $this->ensureWaiter();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $items = json_decode($_POST['items'], true); 
            $waiterId = $_SESSION['user']['id'];
            $this->orderModel->create($waiterId, $items);
            $this->redirect($this->config['base_url'] . '/?route=waiter/orders');
        } else {
            $products = $this->productModel->findAllEnabled();
            $this->view('waiter/create_order.php', ['products' => $products]);
        }
    }


    // Solo procesa POST de cancelación
    public function cancel() {
        $this->ensureWaiter();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = $_POST['order_id'];
            $comment = trim($_POST['comment'] ?? '');
            if (empty($comment)) {
                $_SESSION['error'] = "Debe ingresar un motivo de cancelación.";
                $this->redirect($this->config['base_url'] . "/?route=waiter/showCancelForm&order_id=$orderId");
            }
            $this->orderModel->cancel($orderId, $comment);
            $this->redirect($this->config['base_url'] . '/?route=waiter/orders');
        }
    }

    public function edit() {
        $this->ensureWaiter();
        $orderId = $_GET['order_id'] ?? null;
        if (!$orderId) {
            $this->redirect($this->config['base_url'] . '/?route=waiter/orders');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $quantities = $_POST['qty'] ?? [];
            $items = [];
            foreach ($quantities as $productId => $qty) {
                $product = $this->productModel->findById($productId);
                if ($product && $qty > 0) {
                    $items[] = [
                        'product_id' => $productId,
                        'qty' => (int)$qty,
                        'price' => $product['price']
                    ];
                }
            }
            $this->orderModel->updateItems($orderId, $items);
            $_SESSION['success'] = "Pedido actualizado correctamente.";
            $this->redirect($this->config['base_url'] . '/?route=waiter/orders');
        } else {
            $order = $this->orderModel->getById($orderId);
            $orderItems = $this->orderModel->getItems($orderId);
            $products = $this->productModel->findAllEnabled();

            $this->view('waiter/edit_order.php', [
                'order' => $order,
                'orderItems' => $orderItems,
                'products' => $products
            ]);
        }
    }


    public function pay() {
        $this->ensureWaiter();

        if (!empty($_GET['order_id'])) {
            $orderId = $_GET['order_id'];
            $order = $this->orderModel->getById($orderId);

            if ($order) {
                // Pasamos los items directamente desde $order['items']
                $this->view('waiter/order_detail.php', [
                    'order' => $order,
                    'orderItems' => $order['items'] ?? []
                ]);
                return;
            }
        }

        // Si no hay order_id o no existe la orden
        $this->redirect($this->config['base_url'] . '/?route=waiter/orders');
    }

    public function showCancelForm() {
        $this->ensureWaiter();
        $orderId = $_GET['order_id'] ?? null;
        if (!$orderId) {
            $this->redirect($this->config['base_url'] . '/?route=waiter/orders');
        }

        $order = $this->orderModel->getById($orderId);
        if (!$order) {
            $this->redirect($this->config['base_url'] . '/?route=waiter/orders');
        }

        $this->view('waiter/cancel_order.php', ['order' => $order]);
    }

    public function orderDetail() {
        $this->ensureWaiter();

        if (empty($_GET['order_id'])) {
            $this->redirect($this->config['base_url'] . '/?route=waiter/orders');
        }

        $orderId = $_GET['order_id'];

        $order = $this->orderModel->getById($orderId);

        if (!$order) {
            $this->redirect($this->config['base_url'] . '/?route=waiter/orders');
        }

        // Obtener los items del pedido
        $orderItems = $this->orderModel->getItems($orderId);

        // Cargar la vista
        $this->view('waiter/order_detail.php', [
            'order' => $order,
            'orderItems' => $orderItems
        ]);
    }

    public function payComplete() {
        $this->ensureWaiter();
        $orderId = $_POST['order_id'] ?? null;
        if (!$orderId) {
            $_SESSION['error'] = "Pedido no encontrado.";
            $this->redirect($this->config['base_url'] . '/?route=waiter/orders');
        }
        // Actualizamos solo el status a 'completed', sin comentario
        $updated = $this->orderModel->updateStatus($orderId, 'completed');
        if ($updated) {
            $_SESSION['success'] = "Pedido marcado como completado.";
        } else {
            $_SESSION['error'] = "No se pudo actualizar el estado.";
        }
        $this->redirect($this->config['base_url'] . '/?route=waiter/orders');
    }
}

