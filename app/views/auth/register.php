<?php
// register.php - sin header/footer
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Registro - El por Venir</title>
    <link rel="stylesheet" href="<?php echo $this->config['base_url']; ?>/assets/css/auth.css">
    <style>
        .btn-back {
            font-size: 12px;
            color: #eaeaeaff;
            text-decoration: none !important;
        }

        .btn-back:hover {
            color: #5e5e5eff;
            text-decoration: underline !important;
        }
    </style>
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
                <h2 style="display: flex; justify-content: space-between; align-items: center;">
                    Registro
                    <a href="<?php echo $this->config['base_url']; ?>/?route=auth/login" class="btn-back">
                        Volver
                    </a>
                </h2>

                <?php if(!empty($error)) echo "<p class='error'>$error</p>"; ?>

                <form method="post">
                    <label>Nombre completo</label>
                    <input name="name">
                    <label>Usuario</label>
                    <input name="username" required>
                    <label>Contraseña</label>
                    <input type="password" name="password" required>
                    <button type="submit">Registrar</button>
                </form>
            </div>
    </div>
</body>
</html>

