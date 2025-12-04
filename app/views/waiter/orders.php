<div class="card">
    <h2>Pedidos</h2>
    <div class="card-header" style="display:flex; justify-content:center; align-items:center; gap:20px;">
        <a href="<?php echo $this->config['base_url']; ?>/?route=waiter/create" 
           class="btn"
           style="padding:10px 20px; margin-bottom:15px; background:#723f3f; color:#fff; text-decoration:none; border-radius:5px;">
            Crear pedido
        </a>
    </div>

    <?php $today = date('Y-m-d'); ?>

    <!-- 1. PEDIDOS ACTUALES -->
    <section class="orders">
        <h3>Pedidos Actuales</h3>
        <table>
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>

            <?php foreach($orders as $o): ?>
                <?php 
                    $orderDate = substr($o['created_at'], 0, 10);
                    if ($orderDate !== $today) continue; 
                    if (!in_array($o['status'], ['pending','preparing','delivered'])) continue;
                ?>

                <tr>
                    <td><?php echo $o['id']; ?></td>
                    <td><?php echo number_format($o['total'],2); ?></td>
                    <td><?php echo $o['status']; ?></td>

                    <td class="table-actions">

                        <!-- SI ESTÁ PENDING → MOSTRAR EDITAR -->
                        <?php if ($o['status'] === 'pending'): ?>
                            <a href="<?php echo $this->config['base_url']; ?>/?route=waiter/edit&order_id=<?php echo $o['id']; ?>" 
                               class="btn edit-btn">Editar</a>
                        <?php endif; ?>

                        <!-- CANCELAR → SIEMPRE -->
                        <a href="<?php echo $this->config['base_url']; ?>/?route=waiter/showCancelForm&order_id=<?php echo $o['id']; ?>" 
                           class="btn cancel-btn">Cancelar</a>

                        <!-- SI ESTÁ DELIVERED → MOSTRAR PRE BOLETA -->
                        <?php if ($o['status'] === 'delivered'): ?>
                            <a href="<?php echo $this->config['base_url']; ?>/?route=waiter/pay&order_id=<?php echo $o['id']; ?>" 
                               class="btn pay-btn">Pre-Boleta</a>
                        <?php endif; ?>

                    </td>
                </tr>

            <?php endforeach; ?>

            </tbody>
        </table>
    </section>




    <!-- 2. PEDIDOS COMPLETADOS Y CANCELADOS -->
    <div style="display:flex; gap:30px; margin-top:20px;">

        <!-- COMPLETADOS -->
        <section class="orders" style="flex:1;">
            <h3>Pedidos Completados</h3>
            <table>
                <thead><tr><th>ID Pedido</th><th>Total</th></tr></thead>
                <tbody>
                <?php foreach($orders as $o): ?>
                    <?php if(substr($o['created_at'],0,10) === $today && $o['status'] === 'completed'): ?>
                        <tr>
                            <td><?php echo $o['id']; ?></td>
                            <td><?php echo number_format($o['total'],2); ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <!-- CANCELADOS -->
        <section class="orders" style="flex:1;">
            <h3>Pedidos Cancelados</h3>
            <table>
                <thead><tr><th>ID Pedido</th><th>Total</th></tr></thead>
                <tbody>
                <?php foreach($orders as $o): ?>
                    <?php if(substr($o['created_at'],0,10) === $today && $o['status'] === 'cancelled'): ?>
                        <tr>
                            <td><?php echo $o['id']; ?></td>
                            <td><?php echo number_format($o['total'],2); ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
                </tbody>
            </table>
        </section>

    </div>
</div>



