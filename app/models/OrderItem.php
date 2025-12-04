<?php
require_once __DIR__ . '/../core/Model.php';
class OrderItem extends Model {
    public function getByOrder($orderId) {
        $stmt = $this->db->prepare("SELECT oi.*, p.name FROM order_items oi JOIN products p ON p.id = oi.product_id WHERE oi.order_id = ?");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

    public function countByProductAndDate($productId, $date) {
        $stmt = $this->db->prepare("SELECT SUM(qty) as total_qty FROM order_items oi JOIN orders o ON o.id = oi.order_id WHERE oi.product_id = ? AND DATE(o.created_at) = ?");
        $stmt->execute([$productId, $date]);
        return $stmt->fetchColumn();
    }
}
