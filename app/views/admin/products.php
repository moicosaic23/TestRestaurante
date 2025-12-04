<div class="card">
    <div class="card-header_detail">
        <h2>Productos</h2>
        <a href="<?php echo $this->config['base_url']; ?>/?route=admin/dashboard" class="btn btn-back">Volver</a>
    </div>
    <table>
        <thead><tr><th>ID</th><th>Nombre</th><th>Precio</th><th>Habilitado</th><th>Razón de la deshabilitación</th><th>Acciones</th></tr></thead>
        <tbody>
        <?php foreach($products as $p): ?>
            <tr>
                <td><?php echo $p['id']; ?></td>
                <td><?php echo htmlspecialchars($p['name']); ?></td>
                <td><?php echo number_format($p['price'],2); ?></td>
                <td><?php echo $p['enabled'] ? 'Sí' : 'No'; ?></td>
                <td><?php echo htmlspecialchars($p['disabled_reason']); ?></td>
                <td>
                    <?php if(!$p['enabled']): ?>
                    <!-- Habilitar producto (precio opcional) -->
                    <form method="post" style="display:inline-block;">
                        <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                        <input name="price" placeholder="Precio"> <!-- YA NO ES REQUIRED -->
                        <button name="enable">Habilitar</button>
                    </form>

                    <?php else: ?>
                    <!-- Deshabilitar producto (reason obligatorio) -->
                    <form method="post" style="display:inline-block;">
                        <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                        <input name="reason" placeholder="Razón de la deshabilitación" required>
                        <button name="disable">Deshabilitar</button>
                    </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
