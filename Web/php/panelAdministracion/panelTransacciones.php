<div class="card p-4 mb-4">
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
        <!-- Botón con el atributo onclick para ejecutar la función -->
        <button type="button" id="btnSubirFactura" class="btn btn-success" onclick="addExpense()">Añadir</button>
    </form>
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

<script>
    loadExpenses();

    function loadExpenses() {
        console.log("ejecuta");
        fetch('../php/CRUD/gastos/ver_facturaUsuario.php') // Ajusta la ruta si es necesario
            .then(response => response.json()) // Convertir la respuesta en JSON
            .then(data => {
                const expenseTableBody = document.querySelector('#expense-table tbody');
                expenseTableBody.innerHTML = ''; // Limpiar la tabla existente

                // Iterar sobre las transacciones y agregarlas como filas a la tabla
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
    // Función que se ejecuta cuando se hace clic en el botón
    function addExpense() {
        const form = document.getElementById('expense-form');
        const formData = new FormData(form);

        fetch('../php/CRUD/gastos/insertar_factura.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log(data); // Muestra la respuesta del servidor
                alert("Factura añadida");
                loadExpenses();
                // Opcional: Puedes agregar lógica aquí para actualizar la lista de transacciones
            })
            .catch(error => console.error('Error al añadir el gasto:', error));
    }
</script>