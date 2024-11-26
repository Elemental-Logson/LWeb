<!-- Botón para abrir el modal -->
<button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#expenseModal">
    Añadir Gasto
</button>

<!-- Modal -->
<div class="modal fade" id="expenseModal" tabindex="-1" aria-labelledby="expenseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Encabezado del Modal -->
            <div class="modal-header">
                <h5 class="modal-title" id="expenseModalLabel">Añadir Gasto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <!-- Cuerpo del Modal con el formulario -->
            <div class="modal-body">
                <form id="expense-form" enctype="multipart/form-data" method="post">
                    <div class="form-floating mb-3">
                        <input type="text" id="description" name="descripcion" class="form-control" placeholder="Descripción" required>
                        <label for="description">Descripción</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" id="amount" name="monto" class="form-control" placeholder="Monto" min="0" required>
                        <label for="amount">Monto</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="date" id="date" name="fecha" class="form-control" placeholder="Fecha" required>
                        <label for="date">Fecha</label>
                    </div>
                    <div class="mb-3">
                        <label for="receipt" class="form-label">Foto de la Factura:</label>
                        <input type="file" id="receipt" name="factura" class="form-control" accept="image/*">
                    </div>
                </form>
            </div>
            <!-- Pie del Modal con los botones -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" id="btnSubirFactura" class="btn btn-success" onclick="addExpense()">Añadir</button>
            </div>
        </div>
    </div>
</div>

<!-- Últimas transacciones -->
<h2>Últimas 5 Transacciones</h2>
<table class="table table-striped" id="expense-table">
    <thead>
        <tr>
            <th>Descripción</th>
            <th>Monto (€)</th>
            <th>Fecha</th>
            <th>Factura</th>
        </tr>
    </thead>
    <tbody>
        <!-- Filas dinámicas -->
    </tbody>
</table>

<!-- Incluir Bootstrap JS y sus dependencias -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    loadExpenses();

    function loadExpenses() {
        fetch('../php/CRUD/gastos/ver_facturaUsuario.php')
            .then(response => response.json())
            .then(data => {
                const expenseTableBody = document.querySelector('#expense-table tbody');
                expenseTableBody.innerHTML = '';

                data.forEach(transaction => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
            <td>${transaction.descripcion}</td>
            <td>${transaction.monto}</td>
            <td>${new Date(transaction.fecha).toLocaleDateString()}</td>
            <td>
              <a href="../uploads/${transaction.factura}" target="_blank">Ver Factura</a>
            </td>
          `;
                    expenseTableBody.appendChild(row);
                });
            })
            .catch(error => console.error('Error al cargar las transacciones:', error));
    }

    function addExpense() {
        const form = document.getElementById('expense-form');
        const formData = new FormData(form);

        fetch('../php/CRUD/gastos/insertar_factura.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log(data);
                alert("Factura añadida");
                loadExpenses();
                // Cerrar el modal después de añadir el gasto
                const expenseModal = bootstrap.Modal.getInstance(document.getElementById('expenseModal'));
                expenseModal.hide();
            })
            .catch(error => console.error('Error al añadir el gasto:', error));
    }
</script>