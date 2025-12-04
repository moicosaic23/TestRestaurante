<div class="card">
    <div class="card-header_detail">
        <h2>Añadir Plato</h2>
        <a class="btn" href="<?php echo $this->config['base_url']; ?>/?route=cook/dashboard">Volver</a>
    </div>

    <!-- Crear plato -->
    <section class="create-product-section">
        <h3 class="section_h3">Crear plato</h3>
        <form method="post" action="<?php echo $this->config['base_url']; ?>/?route=cook/createProduct">
            <label>Nombre</label>
            <input name="name" required>
            
            <label>Descripción</label>
            <textarea name="description"></textarea>
            
            <label>Precio sugerido</label>
            <input name="price" type="number" step="0.01" required>
            
            <div style="display:flex; justify-content:center;">
                <button class="btn complete-btn">Crear</button>
            </div>
        </form>
    </section>
</div>
