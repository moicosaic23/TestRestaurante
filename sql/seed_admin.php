<?php
// Ejecutar desde CLI o navegador: php seed_admin.php

$host = '127.0.0.1';
$port = 3307; // ← PUERTO CORRECTO
$db   = 'restaurant_mvc';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    echo "Conexión OK<br>";

    $username = 'admin';
    $password = password_hash('admin', PASSWORD_DEFAULT);

    // verificar si existe
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);

    if ($stmt->fetch()) {
        echo "⚠️ Admin ya existe.<br>";
    } else {
        $insert = $pdo->prepare("
            INSERT INTO users (username, password, name, role, approved)
            VALUES (?, ?, ?, ?, 1)
        ");
        $insert->execute([$username, $password, 'Administrador', 'admin']);

        echo "✅ Admin creado: usuario = 'admin', contraseña = 'admin'.<br>";
        echo "⚠️ Cámbiala luego por seguridad.<br>";
    }

} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}

