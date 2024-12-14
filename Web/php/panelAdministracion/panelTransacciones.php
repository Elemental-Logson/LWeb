<?php
if (!defined('ACCESO_PERMITIDO')) {
    // header('HTTP/1.0 403 Forbidden');
    // exit('No tienes permiso para acceder directamente a este archivo.');
    header("Location: /LWeb/Web/html/forbidden.html");
    exit();
}
$username = htmlspecialchars($_SESSION['username'] ?? 'Usuario');
$role = $_SESSION['role'];
?>
<style>
    #expense-table tbody {
        min-height: calc(10px * 10);
        /* 40px por fila, ajusta según tu diseño */
        display: block;
    }

    #expense-table tr {
        height: 40px;
        /* Altura fija por fila */
        display: flex;
    }

    #expense-table th,
    #expense-table td {
        flex: 1;
        /* Distribuye las columnas proporcionalmente */
        text-align: center;
    }

    /* Estilo para filas vacías */
    #expense-table tbody .empty-row {
        visibility: hidden;
        /* Las filas estarán ocultas pero ocuparán espacio */
    }
</style>
<!-- Botón para abrir el modal -->


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
                        <input type="file" id="receipt" name="factura" class="form-control" accept="image/*,application/pdf">
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
<div class="container my-5">
    <!-- Últimas transacciones -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0">Transacciones</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#expenseModal">
            Añadir Gasto
        </button>
    </div>
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
    <nav aria-label="Paginación de transacciones" class="pagination justify-content-center">
        <ul class="pagination" id="pagination"></ul>
    </nav>
</div>
<!-- Incluir Bootstrap JS y sus dependencias -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    var username = "<?php echo $username; ?>";
    var role = "<?php echo $role; ?>";
    var currentPage = 1;
    var itemsPerPage = 10;
    var transactions = [];
    loadExpenses();

    function loadExpenses() {
        fetch('../php/CRUD/gastos/ver_facturaUsuario.php')
            .then(response => response.json())
            .then(data => {
                transactions = data;
                renderTable();
                renderPagination();
            })
            .catch(error => console.error('Error al cargar las transacciones:', error));
    }

    function renderTable() {
        const expenseTableBody = document.querySelector('#expense-table tbody');
        expenseTableBody.innerHTML = '';

        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = Math.min(startIndex + itemsPerPage, transactions.length);

        // Añadir filas con datos reales
        for (let i = startIndex; i < endIndex; i++) {
            const transaction = transactions[i];
            const facturaRuta = transaction.factura ?
                (role === 'Admin' ?
                    `../../img/${transaction.nombre_usuario}/${transaction.factura}` :
                    `../../img/${username}/${transaction.factura}`) :
                null;

            const row = `
            <tr>
                <td>${transaction.descripcion}</td>
                <td>${transaction.monto}</td>
                <td>${new Date(transaction.fecha).toLocaleDateString()}</td>
                <td>${facturaRuta ? `<a href="${facturaRuta}" target="_blank">Ver Factura</a>` : 'Sin ticket'}</td>
            </tr>
        `;
            expenseTableBody.insertAdjacentHTML('beforeend', row);
        }

        // Añadir filas vacías para completar hasta 10 registros si es necesario
        const totalRows = endIndex - startIndex;
        for (let i = totalRows; i < itemsPerPage; i++) {
            const emptyRow = `
            <tr class="empty-row">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        `;
            expenseTableBody.insertAdjacentHTML('beforeend', emptyRow);
        }
    }

    function renderPagination() {
        const totalPages = Math.ceil(transactions.length / itemsPerPage);
        const pagination = document.getElementById('pagination');
        pagination.innerHTML = '';

        for (let i = 1; i <= totalPages; i++) {
            const pageItem = `
            <li class="page-item ${i === currentPage ? 'active' : ''}">
                <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
            </li>
        `;
            pagination.insertAdjacentHTML('beforeend', pageItem);
        }
    }

    function changePage(page) {
        currentPage = page;
        renderTable();
        renderPagination();
    }

    function addExpense() {
        if (!validateForm()) {
            return; // No enviar el formulario si hay errores
        }

        const form = document.getElementById('expense-form');
        const formData = new FormData(form);

        fetch('../php/CRUD/gastos/insertar_factura.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Factura añadida correctamente.");
                    loadExpenses();
                    const expenseModal = bootstrap.Modal.getInstance(document.getElementById('expenseModal'));
                    expenseModal.hide();
                } else {
                    alert("Error: " + data.error);
                }
            })
            .catch(error => console.error('Error al añadir el gasto:', error));
    }

    function validateForm() {
        const description = document.getElementById("description");
        const amount = document.getElementById("amount");
        const date = document.getElementById("date");
        const receipt = document.getElementById("receipt");

        let isValid = true;

        // Clear previous errors
        document.querySelectorAll(".error-message").forEach(el => el.remove());

        const allowedCharacters = /^[a-zA-Z0-9\sáéíóúÁÉÍÓÚñÑ.,-]*$/;

        // Validate description
        if (!description.value.trim()) {
            showError(description, "La descripción es obligatoria.");
            isValid = false;
        } else if (!allowedCharacters.test(description.value)) {
            showError(description, "La descripción no debe contener comillas ni símbolos especiales.");
            isValid = false;
        } else if (description.value.length > 100) {
            showError(description, "La descripción no puede tener más de 100 caracteres.");
            isValid = false;
        }

        // Validate amount
        if (!amount.value.trim()) {
            showError(amount, "El monto es obligatorio.");
            isValid = false;
        } else if (isNaN(amount.value) || amount.value <= 0) {
            showError(amount, "El monto debe ser un número positivo.");
            isValid = false;
        }

        // Validate date
        if (!date.value.trim()) {
            showError(date, "La fecha es obligatoria.");
            isValid = false;
        }

        // Validate receipt (file)
        if (receipt.files.length > 0) {
            const file = receipt.files[0];
            const allowedTypes = ["image/jpeg", "image/png", "application/pdf"];
            if (!allowedTypes.includes(file.type)) {
                showError(receipt, "Solo se permiten archivos JPEG, PNG o PDF.");
                isValid = false;
            } else if (file.size > 5 * 1024 * 1024) { // Límite de 5 MB
                showError(receipt, "El archivo no puede superar los 5 MB.");
                isValid = false;
            }
        }

        return isValid;
    }

    function showError(input, message) {
        const error = document.createElement("div");
        error.className = "error-message text-danger mt-1";
        error.textContent = message;
        input.parentElement.appendChild(error);
    }
</script>