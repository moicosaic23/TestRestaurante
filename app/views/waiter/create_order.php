<div class="card">
    <div class="card-header_detail">
        <h2>Crear Pedido</h2>
        <a class="btn" href="<?php echo $this->config['base_url']; ?>/?route=waiter/orders">Volver</a>
    </div>
    <div class="create-product-section">
        <label>Productos disponibles</label>
        <select id="product-select">
            <option value="">-- seleccionar --</option>
            <?php foreach($products as $p): ?>
                <option data-price="<?php echo $p['price']; ?>" value="<?php echo $p['id']; ?>">
                    <?php echo htmlspecialchars($p['name']); ?> - S/. <?php echo number_format($p['price'],2); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="number" id="qty" value="1" min="1">
        <button id="add-item">Agregar</button>
    </div>
    <form id="order-form" method="post" action="">
        <input type="hidden" name="items" id="items-json">
        <table id="items-table">
            <thead><tr><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Eliminar</th></tr></thead>
            <tbody></tbody>
        </table>
        <p  class="items-table">Total: S/. <span id="order-total">0.00</span></p>
        <div style="margin-top:20px; text-align:center;" class="complete_payment">
            <button type="submit" class="btn complete-btn">
                Guardar Pedido
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const items = [];
    function render(){
        const tbody = document.querySelector('#items-table tbody');
        tbody.innerHTML = '';
        let total = 0;
        items.forEach((it, idx) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `<td>${it.name}</td><td>${it.qty}</td><td>${(it.price*it.qty).toFixed(2)}</td><td><button data-i="${idx}" class="del">X</button></td>`;
            tbody.appendChild(tr);
            total += it.price * it.qty;
        });
        document.getElementById('order-total').textContent = total.toFixed(2);
        document.getElementById('items-json').value = JSON.stringify(items);
        document.querySelectorAll('.del').forEach(btn => btn.addEventListener('click', function(e){
            const i = this.dataset.i;
            items.splice(i,1);
            render();
        }));
    }
    document.getElementById('add-item').addEventListener('click', function(){
        const sel = document.getElementById('product-select');
        const id = sel.value;
        if(!id) return alert('Selecciona un producto');
        const name = sel.options[sel.selectedIndex].text.split(' - ')[0];
        const price = parseFloat(sel.options[sel.selectedIndex].dataset.price);
        const qty = parseInt(document.getElementById('qty').value) || 1;
        items.push({product_id: id, name, qty, price});
        render();
    });
});
</script>
