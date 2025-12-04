<div class="card">
    <div class="card-header_detail">
        <h2>Editar Pedido #<?php echo $order['id']; ?></h2>
        <a class="btn" href="<?php echo $this->config['base_url']; ?>/?route=waiter/orders">Volver</a>
    </div>

    <form id="edit-order-form" action="<?php echo $this->config['base_url']; ?>/?route=waiter/edit&order_id=<?php echo $order['id']; ?>" method="post">
        <div class="create-product-section">
            <label>Agregar producto</label>
            <select id="product-select">
                <option value="">-- seleccionar --</option>
                <?php foreach($products as $p): ?>
                    <option data-price="<?php echo $p['price']; ?>" value="<?php echo $p['id']; ?>">
                        <?php echo htmlspecialchars($p['name']); ?> - S/. <?php echo number_format($p['price'],2); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <input type="number" id="qty-add" value="1" min="1">
            <button type="button" id="add-item-btn">Agregar</button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Plato</th>
                    <th>Cantidad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="order-items-body">
                <?php foreach($orderItems as $item): ?>
                    <tr data-product-id="<?php echo $item['product_id']; ?>">
                        <td class="item-name"><?php echo $item['product_name']; ?></td>

                        <td>
                            <input type="number"
                                class="item-qty"
                                name="qty[<?php echo $item['product_id']; ?>]"
                                value="<?php echo $item['qty']; ?>"
                                min="1"
                                style="width:60px; text-align:center;">
                        </td>

                        <td class="center-btn">
                            <button type="button" class="btn btn_eliminar remove-btn">Eliminar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div style="margin-top:20px; text-align:center;" class="complete_payment">
            <button type="submit" class="btn complete-btn">
                Guardar
            </button>
        </div>
    </form>
</div>

<script>
    function removeRow(btn) {
        const row = btn.closest('tr');
        row.remove();
    }
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Eliminar fila existente
    document.querySelectorAll('.remove-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            this.closest('tr').remove();
        });
    });

    // Agregar nuevo item
    document.getElementById('add-item-btn').addEventListener('click', function() {
        const sel = document.getElementById('product-select');
        const id = sel.value;

        if (!id) return alert("Seleccione un producto");

        const name = sel.options[sel.selectedIndex].text.split(" - ")[0];
        const qty = parseInt(document.getElementById('qty-add').value);

        // evitar duplicados: si el producto ya existe, sumamos
        const existingRow = document.querySelector(`tr[data-product-id="${id}"]`);
        if (existingRow) {
            const input = existingRow.querySelector('.item-qty');
            input.value = parseInt(input.value) + qty;
            return;
        }

        // crear nueva fila
        const tr = document.createElement('tr');
        tr.dataset.productId = id;
        tr.innerHTML = `
            <td class="item-name">${name}</td>

            <td>
                <input type="number"
                       class="item-qty"
                       name="qty[${id}]"
                       value="${qty}"
                       min="1"
                       style="width:60px; text-align:center;">
            </td>

            <td class="center-btn">
                <button type="button" class="btn btn_eliminar remove-btn">Eliminar</button>
            </td>
        `;

        // agregar fila
        document.getElementById('order-items-body').appendChild(tr);

        // activar botón eliminar en la nueva fila
        tr.querySelector('.remove-btn').addEventListener('click', function(){
            tr.remove();
        });
    });
});
</script>
