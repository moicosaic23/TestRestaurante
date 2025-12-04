<div class="card">
    <div class="card-header_detail">
        <h2>Detalle del Pedido #<?php echo $order['id']; ?></h2>
        <a class="btn" href="<?php echo $this->config['base_url']; ?>/?route=waiter/orders">Volver</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($orderItems)): ?>
                <?php foreach ($orderItems as $item): ?>
                    <tr>
                        <td><?php echo $item['name'] ?? $item['product_name']; ?></td>
                        <td><?php echo $item['qty']; ?></td>
                        <td><?php echo number_format($item['price'],2); ?></td>
                        <td><?php echo number_format($item['qty'] * $item['price'],2); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align:center;">No hay productos en este pedido</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php 
        $total = $order['total'];
        $subtotal = $total * 0.82;
        $igv = $total * 0.18;
    ?>

    <div style="text-align:center; margin-top:20px; font-size:16px;">
        <p>Total Importe: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;S/. <?php echo number_format($subtotal,2); ?></p>
        <p>IGV (18%): &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;S/. <?php echo number_format($igv,2); ?></p>
        <p><strong>Total: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;S/. <?php echo number_format($total,2); ?></strong></p>
    </div>

    <div style="text-align:center; margin-top:20px;" class="complete_payment">
        <form action="<?php echo $this->config['base_url']; ?>/?route=waiter/payComplete" method="post">
            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
            <button type="submit" class="btn complete-btn">Completar Pago</button>
        </form>
    </div>
</div>


