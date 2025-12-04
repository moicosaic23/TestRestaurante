<div class="card">
    <div class="card-header_detail">
        <h2>Usuarios</h2>
        <a href="<?php echo $this->config['base_url']; ?>/?route=admin/dashboard" class="btn btn-back">Volver</a>
    </div>
    <table>
        <thead><tr><th>ID</th><th>Usuario</th><th>Nombre</th><th>Rol</th><th>Aprobado</th><th>Acciones</th></tr></thead>
        <tbody>
        <?php foreach($users as $u): ?>
            <tr>
                <td><?php echo $u['id']; ?></td>
                <td><?php echo htmlspecialchars($u['username']); ?></td>
                <td><?php echo htmlspecialchars($u['name']); ?></td>
                <td><?php echo $u['role']; ?></td>
                <td><?php echo $u['approved'] ? 'Sí' : 'No'; ?></td>
                <td>
                    <form method="post" style="display:inline-block;">
                        <input type="hidden" name="id" value="<?php echo $u['id']; ?>">
                        <select name="role">
                            <option value="">-- sin rol --</option>
                            <option value="waiter">Camarero</option>
                            <option value="cook">Cocinero</option>
                            <option value="admin">Admin</option>
                        </select>
                        <label class ="approved-label"><input type="checkbox" name="approved"> Aprobado</label>
                        <input type="hidden" name="action" value="update_role">
                        <button type="submit">Guardar</button>
                    </form>
                    <details style="display:inline-block;margin-left:8px;">
                        <summary>Creds</summary>
                        <form method="post">
                            <input type="hidden" name="id" value="<?php echo $u['id']; ?>">
                            <input name="username" value="<?php echo htmlspecialchars($u['username']); ?>">
                            <input name="password" placeholder="Nueva contraseña (opcional)">
                            <input type="hidden" name="action" value="update_credentials">
                            <button>Actualizar</button>
                        </form>
                    </details>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
