<?php
require_once __DIR__ . '/../core/Model.php';
class Order extends Model {
    public function create($waiter_id, $items) {
        // items = array of ['product_id'=>..., 'qty'=>..., 'price'=>...]
        $this->db->beginTransaction();
        $stmt = $this->db->prepare("INSERT INTO orders (waiter_id, total) VALUES (?,0)");
        $stmt->execute([$waiter_id]);
        $orderId = $this->db->lastInsertId();
        $total = 0;
        $stmtItem = $this->db->prepare("INSERT INTO order_items (order_id, product_id, qty, price) VALUES (?,?,?,?)");
        foreach ($items as $it) {
            $price = $it['price'];
            $qty = (int)$it['qty'];
            $stmtItem->execute([$orderId, $it['product_id'], $qty, $price]);
            $total += $price * $qty;
        }
        $stmtUpdate = $this->db->prepare("UPDATE orders SET total = ? WHERE id = ?");
        $stmtUpdate->execute([$total, $orderId]);
        $this->db->commit();
        return $orderId;
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT o.*, u.username as waiter FROM orders o JOIN users u ON u.id = o.waiter_id WHERE o.id = ?");
        $stmt->execute([$id]);
        $order = $stmt->fetch();
        if ($order) {
            $stmtItems = $this->db->prepare("SELECT oi.*, p.name FROM order_items oi JOIN products p ON p.id = oi.product_id WHERE oi.order_id = ?");
            $stmtItems->execute([$id]);
            $order['items'] = $stmtItems->fetchAll();
        }
        return $order;
    }

    public function listAll($filter = []) {
        $sql = "SELECT o.*, u.username as waiter FROM orders o JOIN users u ON u.id = o.waiter_id";
        $conds = [];
        $params = [];

        if (!empty($filter['status'])) {
            $conds[] = "LOWER(o.status) = LOWER(?)";
            $params[] = $filter['status'];
        }

        if (!empty($filter['date'])) {
            $conds[] = "DATE(o.created_at) = ?";
            $params[] = $filter['date'];
        }

        if (!empty($filter['month'])) {
            $month = (int)$filter['month'];
            $year = date('Y');
            $conds[] = "MONTH(o.created_at) = ? AND YEAR(o.created_at) = ?";
            $params[] = $month;
            $params[] = $year;
        }

        if (!empty($filter['waiter_id'])) { // filtro opcional para mozo
            $conds[] = "o.waiter_id = ?";
            $params[] = $filter['waiter_id'];
        }   

        if ($conds) $sql .= " WHERE " . implode(" AND ", $conds);
        $sql .= " ORDER BY o.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    public function updateStatus($id, $status, $comment = null) {
        $stmt = $this->db->prepare("UPDATE orders SET status = ?, comment = ? WHERE id = ?");
        return $stmt->execute([$status, $comment, $id]);
    }

    public function cancel($id, $comment) {
        return $this->updateStatus($id, 'cancelled', $comment);
    }

    public function updateItems($orderId, $items) {
        // simple implementation: delete old and insert new (could be optimized)
        $this->db->beginTransaction();
        $this->db->prepare("DELETE FROM order_items WHERE order_id = ?")->execute([$orderId]);
        $total = 0;
        $stmtItem = $this->db->prepare("INSERT INTO order_items (order_id, product_id, qty, price) VALUES (?,?,?,?)");
        foreach ($items as $it) {
            $stmtItem->execute([$orderId, $it['product_id'], $it['qty'], $it['price']]);
            $total += $it['qty'] * $it['price'];
        }
        $this->db->prepare("UPDATE orders SET total = ? WHERE id = ?")->execute([$total, $orderId]);
        $this->db->commit();
        return true;
    }    

    public function getItems($orderId) {
        $stmt = $this->db->prepare("
            SELECT oi.product_id, p.name AS product_name, oi.qty, oi.price
            FROM order_items oi
            INNER JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = :order_id
        ");
        $stmt->execute(['order_id' => $orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
