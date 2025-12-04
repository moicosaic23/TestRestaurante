<div class="card">
    <div class="card-header">
        <h2>Panel Cocinero</h2>
        <div class="admin-buttons">
            <a class="btn" href="<?php echo $this->config['base_url']; ?>/?route=cook/showCreateProductForm">Añadir Plato</a>
        </div>
    </div>

    <?php $today = date('Y-m-d'); ?>
    <?php
    // Filtrar solo pedidos del día
    $pendingOrdersToday = array_filter($pendingOrders, fn($o) => substr($o['created_at'],0,10) === $today);
    $preparingOrdersToday = array_filter($preparingOrders, fn($o) => substr($o['created_at'],0,10) === $today);
    ?>

    <!-- Pedidos pendientes -->
    <section class="orders-pend">
        <h3>Pedidos Pendientes</h3>
        <?php if(!empty($pendingOrdersToday)): ?>
            <table>
                <thead>
                    <tr><th>ID</th><th>Mozo</th><th>Detalle</th><th>Acción</th></tr>
                </thead>
                <tbody>
                <?php foreach($pendingOrdersToday as $o): ?>
                    <tr>
                        <td><?php echo $o['id']; ?></td>
                        <td><?php echo htmlspecialchars($o['waiter']); ?></td>
                        <td>
                            <ul>
                                <?php foreach($o['items'] as $item): ?>
                                    <li><?php echo htmlspecialchars($item['name']); ?> x<?php echo $item['qty']; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                        <td>
                            <form method="post" action="<?php echo $this->config['base_url']; ?>/?route=cook/markPreparing">
                                <input type="hidden" name="order_id" value="<?php echo $o['id']; ?>">
                                <button>Comenzar Preparación</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay pedidos pendientes hoy.</p>
        <?php endif; ?>
    </section>

    <!-- Pedidos en preparación -->
    <section class="orders-start">
        <h3>Pedidos en Preparación</h3>
        <?php if(!empty($preparingOrdersToday)): ?>
            <table>
                <thead>
                    <tr><th>ID</th><th>Mozo</th><th>Detalle</th><th>Acción</th></tr>
                </thead>
                <tbody>
                <?php foreach($preparingOrdersToday as $o): ?>
                    <tr>
                        <td><?php echo $o['id']; ?></td>
                        <td><?php echo htmlspecialchars($o['waiter']); ?></td>
                        <td>
                            <ul>
                                <?php foreach($o['items'] as $item): ?>
                                    <li><?php echo htmlspecialchars($item['name']); ?> x<?php echo $item['qty']; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                        <td>
                            <form method="post" action="<?php echo $this->config['base_url']; ?>/?route=cook/markDelivered">
                                <input type="hidden" name="order_id" value="<?php echo $o['id']; ?>">
                                <button>Marcar como entregado</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay pedidos en preparación hoy.</p>
        <?php endif; ?>
    </section>
</div>



