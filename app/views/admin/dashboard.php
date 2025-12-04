<div class="card">
    <div class="card-header">
        <h2>Panel Administrador</h2>
        <div class="admin-buttons">
            <a class="btn" href="<?php echo $this->config['base_url']; ?>/?route=admin/users">Gestionar Usuarios</a>
            <a class="btn" href="<?php echo $this->config['base_url']; ?>/?route=admin/products">Gestionar Platos</a>
        </div>
    </div>

    <section class="orders-filters">
        <h3>Pedidos Hoy (<?php echo date('Y-m-d'); ?>)</h3>
        <div class="filter-container">
            <form method="get" action="" style="display:flex; flex-wrap:wrap; gap:15px; justify-content:center;">
                <input type="hidden" name="route" value="admin/dashboard">

                <div class="filter-item">
                    <label>Estado:</label>
                    <select name="status">
                        <option value="">Todos</option>
                        <option value="pending">Pendiente</option>
                        <option value="preparing">Preparando</option>
                        <option value="completed">Completado</option>
                        <option value="cancelled">Cancelado</option>
                        <option value="delivered">Preparado para el mozo</option>
                    </select>
                </div>

                <div class="filter-item">
                    <label>Fecha:</label>
                    <input type="date" name="date" 
                        value="<?php echo !empty($filter['month']) ? '' : ($filter['date'] ?? ''); ?>">
                </div>

                <div class="filter-item">
                    <label>Mes:</label>
                    <select name="month">
                        <option value="">--Mes--</option>
                        <?php for($m = 1; $m <= 12; $m++): ?>
                            <option value="<?php echo $m; ?>" 
                                <?php if(!empty($filter['month']) && $filter['month'] == $m) echo 'selected'; ?>>
                                <?php echo date('F', mktime(0, 0, 0, $m, 1)); ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <button type="submit" class="filter-submit">Filtrar</button>
            </form>
        </div>
    </section>

    <section class="orders-summary">
        <h3 class="h3">Estadísticas de <?php echo $selectedLabel; ?></h3>
        <p>Total Pedidos Completados: <?php echo count($ordersToday); ?></p>
        <p>Total Ganancias: S/. <?php echo number_format($totalToday, 2); ?></p>
    </section>

    <section class="orders-table">
        <table>
            <thead>
                <tr>
                    <th>ID_Mozo</th>
                    <th>Mozo</th>
                    <th>Total</th>
                    <th>Estado_pedido</th>
                    <th>Fecha</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($orders as $o): ?>
                    <tr>
                        <td><?php echo $o['id']; ?></td>
                        <td><?php echo htmlspecialchars($o['waiter']); ?></td>
                        <td><?php echo number_format($o['total'],2); ?></td>
                        <td><?php echo $o['status']; ?></td>
                        <td><?php echo $o['created_at']; ?></td>
                        <td>
                            <a class="btn btn-detail" href="<?php echo $this->config['base_url']; ?>/?route=admin/order_detail&id=<?php echo $o['id']; ?>">Ver detalle</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</div>

