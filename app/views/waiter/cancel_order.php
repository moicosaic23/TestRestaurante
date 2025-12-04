<div class="card cancel_order">
    <div class="card-header_detail">
        <h2>Cancelar Pedido #<?php echo $order['id']; ?></h2>
        <a class="btn" href="<?php echo $this->config['base_url']; ?>/?route=waiter/orders">Volver</a>
    </div>

    <?php if (!empty($_SESSION['error'])): ?>
        <p class="error-message"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>

    <form method="post" action="<?php echo $this->config['base_url']; ?>/?route=waiter/cancel">
        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">

        <label class="items-table" for="comment">Motivo de cancelación:</label>
        <textarea id="comment" name="comment" required placeholder="Escriba la razón de cancelación"></textarea>

        <button type="submit" class="btn cancel-btn">Cancelar pedido</button>
    </form>
</div>
