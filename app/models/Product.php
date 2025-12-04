<?php
require_once __DIR__ . '/../core/Model.php';
class Product extends Model {
    public function create($name, $description, $price, $created_by) {
        $stmt = $this->db->prepare("INSERT INTO products (name,description,price,created_by,enabled) VALUES (?,?,?,?,0)");
        $stmt->execute([$name,$description,$price,$created_by]);
        return $this->db->lastInsertId();
    }

    public function enable($id, $price = null) {
        // Obtener el producto actual
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            return false; // No existe
        }

        // Si NO ingresan precio, usamos el precio actual
        if ($price === null || $price === '' || !is_numeric($price)) {
            $price = $product['price'];
        }

        // Actualizar registro
        $stmt = $this->db->prepare("
            UPDATE products 
            SET enabled = 1, disabled_reason = NULL, price = ? 
            WHERE id = ?
        ");

        return $stmt->execute([$price, $id]);
    }


    public function disable($id, $reason) {
        $stmt = $this->db->prepare("UPDATE products SET enabled = 0, disabled_reason = ? WHERE id = ?");
        return $stmt->execute([$reason,$id]);
    }

    public function findAllEnabled() {
        $stmt = $this->db->query("SELECT * FROM products WHERE enabled = 1 ORDER BY name");
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function listAll() {
        $stmt = $this->db->query("SELECT p.*, u.username as created_by_user FROM products p LEFT JOIN users u ON u.id = p.created_by ORDER BY p.created_at DESC");
        return $stmt->fetchAll();
    }
}
