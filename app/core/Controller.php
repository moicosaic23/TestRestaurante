<?php
class Controller {
    protected $config;
    protected $db;

    public function __construct($config, $db) {
        $this->config = $config;
        $this->db = $db;
        
        // Solo iniciar sesión si no hay una activa
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }


    /**
     * Vista con soporte opcional para layout personalizado
     */
    protected function view($path, $data = [], $layout = null) {
        extract($data);
        $file = __DIR__ . '/../views/' . $path;

        // Si se especifica un layout, lo cargamos sin header/footer
        if ($layout !== null) {
            $layoutPath = __DIR__ . '/../views/' . $layout;
            require $layoutPath;
            return;
        }

        // Layout por defecto con header y footer
        require __DIR__ . '/../views/templates/header.php';
        require $file;
        require __DIR__ . '/../views/templates/footer.php';
    }

    protected function redirect($url) {
        header("Location: {$url}");
        exit;
    }
}

