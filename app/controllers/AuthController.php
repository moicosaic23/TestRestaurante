<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';

class AuthController extends Controller {
    private $userModel;

    public function __construct($config, $db) {
        parent::__construct($config, $db);
        $this->userModel = new User($db);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $user = $this->userModel->findByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'role' => $user['role'],
                    'approved' => $user['approved']
                ];

                if ($user['role'] === 'admin') {
                    $this->redirect($this->config['base_url'] . '/?route=admin/dashboard');
                }

                if (!$user['approved']) {
                    $this->view('auth/register_wait.php', ['message' => 'Esperando aprobación del administrador.'], 'templates/layout_auth.php');
                    return;
                }

                if (empty($user['role'])) {
                    $this->view('auth/register_wait.php', ['message' => 'Aún no se le ha asignado un rol.'], 'templates/layout_auth.php');
                    return;
                }

                switch ($user['role']) {
                    case 'waiter':
                        $this->redirect($this->config['base_url'] . '/?route=waiter/dashboard');
                    case 'cook':
                        $this->redirect($this->config['base_url'] . '/?route=cook/dashboard');
                    default:
                        $this->redirect($this->config['base_url']);
                }
            } else {
                $error = "Usuario o contraseña inválidos";
                $this->view('auth/login.php', ['error' => $error], 'templates/layout_auth.php');
            }
        } else {
            $this->view('auth/login.php', [], 'templates/layout_auth.php');
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $password = $_POST['password'];
            $name = $_POST['name'] ?? null;

            if ($this->userModel->findByUsername($username)) {
                $this->view('auth/register.php', ['error' => 'Usuario ya existe.'], 'templates/layout_auth.php');
                return;
            }

            $this->userModel->create($username, $password, $name);
            $this->view('auth/register_wait.php', ['message' => 'Registro recibido. Espera aprobación del administrador.'], 'templates/layout_auth.php');
        } else {
            $this->view('auth/register.php', [], 'templates/layout_auth.php');
        }
    }

    public function logout() {
        session_destroy();
        $this->redirect($this->config['base_url'] . '/?route=auth/login');
    }
}

