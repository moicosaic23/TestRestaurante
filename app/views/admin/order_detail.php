<div class="card">
    <div class="card-header_detail">
        <h2>Detalle Pedido #<?php echo $order['id']; ?></h2>
        <a href="<?php echo $this->config['base_url']; ?>/?route=admin/dashboard" class="btn btn-back">Volver</a>
    </div>

    <section class="orders-table">
        <table class="orders-table_table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>

                    <?php if (strtolower($order['status']) === 'cancelled'): ?>
                        <th>Comentario de cancelación</th>
                    <?php endif; ?>

                </tr>
            </thead>

            <tbody>
                <?php foreach($order['items'] as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo $item['qty']; ?></td>
                        <td>S/. <?php echo number_format($item['price'],2); ?></td>
                        <td>S/. <?php echo number_format($item['qty'] * $item['price'],2); ?></td>

                        <?php if (strtolower($order['status']) === 'cancelled'): ?>
                            <td rowspan="<?php echo count($order['items']); ?>" style="vertical-align: top;">
                                <?php echo htmlspecialchars($order['comment']); ?>
                            </td>
                            <?php break; ?>
                        <?php endif; ?>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p><strong>Total Pedido: S/. <?php echo number_format($order['total'],2); ?></strong></p>
    </section>
</div>

