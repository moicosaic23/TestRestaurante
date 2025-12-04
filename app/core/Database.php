<?php
class Database {
    private static $instance = null;
    private $pdo;

    private function __construct($config) {

        $host     = $config['db']['host'];
        $port     = $config['db']['port'];      // <-- puerto agregado
        $dbname   = $config['db']['database'];
        $user     = $config['db']['user'];
        $password = $config['db']['password'];
        $charset  = $config['db']['charset'];

        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=$charset";

        try {
            $this->pdo = new PDO($dsn, $user, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            die("❌ Error de conexión: " . $e->getMessage());
        }
    }

    public static function getInstance($config) {
        if (self::$instance === null) {
            self::$instance = new Database($config);
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}




