<div class="container mt-4">
    <h1>Panel de Gastos</h1>

    <!-- Formulario para añadir gasto -->
    <div class="card p-4 mb-4">
        <form enctype="multipart/form-data">
            <div class="form-floating mb-3">
                <input type="text" id="description" class="form-control" placeholder="Descripción" required>
                <label for="description">Descripción</label>
            </div>
            <div class="form-floating mb-3">
                <input type="number" id="amount" class="form-control" placeholder="Monto" required>
                <label for="amount">Monto</label>
            </div>
            <div class="form-floating mb-3">
                <input type="date" id="date" class="form-control" placeholder="Fecha" required>
                <label for="date">Fecha</label>
            </div>
            <div class="mb-3">
                <label for="receipt" class="form-label">Foto de la Factura:</label>
                <input type="file" id="receipt" class="form-control" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-success">Añadir</button>
        </form>
    </div>

    <!-- Últimas transacciones -->
    <h2>Últimas 5 Transacciones</h2>
    <ul id="expense-list" class="list-group">
        <!-- Lista de transacciones -->
    </ul>
</div>
