<?php
// login.php - sin header/footer
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Login - El por Venir</title>
    <link rel="stylesheet" href="<?php echo $this->config['base_url']; ?>/assets/css/auth.css">
</head>
<body>
    <div class="login-container">
        <!-- Izquierda -->
        <div class="login-left">
            <img src="<?php echo $this->config['base_url']; ?>/assets/images/restaurante.jpg" alt="Restaurante" class="left-bg">
            <div class="login-left-logo">
                <img src="<?php echo $this->config['base_url']; ?>/assets/images/logo.webp" alt="Logo El por Venir">
            </div>
        </div>

        <!-- Derecha -->
        <div class="login-right">
            <h1>El por Venir</h1>
            <div class="card">
                <?php if(!empty($error)) echo "<p class='error'>$error</p>"; ?>
                <form method="post" action="">
                    <label>Usuario</label>
                    <input name="username" required>
                    <label>Contraseña</label>
                    <input type="password" name="password" required>
                    <button type="submit">Ingresar</button>
                </form>
                <p>¿No tienes cuenta? <a href="<?php echo $this->config['base_url']; ?>/?route=auth/register">Regístrate</a></p>
            </div>
        </div>
    </div>
</body>
</html>




