<?php
// register_wait.php
// No incluir header/footer, usar layout_auth.php
?>
<style>
    .btn-back {
        font-size: 12px;
        color: #eaeaeaff;
        text-decoration: none !important;
        transition: color 0.3s ease;
    }

    .btn-back:hover {
        color: #5e5e5eff;
        text-decoration: underline !important;
    }
</style>
<div class="login-container">
    <!-- Izquierda: imagen del restaurante -->
    <div class="login-left">
        <img src="<?php echo $this->config['base_url']; ?>/assets/images/restaurante.jpg" alt="Restaurante" class="left-bg">
        <div class="login-left-logo">
            <img src="<?php echo $this->config['base_url']; ?>/assets/images/logo.webp" alt="Logo El por Venir">
        </div>
    </div>

    <!-- Derecha: mensaje -->
    <div class="login-right">
        <h1>El por Venir</h1>
        <div class="card">
            <h2 style="display: flex; justify-content: space-between; align-items: center;">
                Registro completado
                <a href="<?php echo $this->config['base_url']; ?>/?route=auth/login" class="btn-back">
                    Volver
                </a>
            </h2>
            <p><?php echo $message ?? "Tu registro fue exitoso. Espera a que el administrador habilite tu cuenta y te asigne un rol."; ?></p>
        </div>
    </div>
</div>


