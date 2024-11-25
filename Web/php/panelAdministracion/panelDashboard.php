<div class="container my-5">
    <h1 class="mb-4">Gestión de Gastos</h1>

    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-md-4">
            <label for="filter-description" class="form-label">Descripción</label>
            <input type="text" id="filter-description" class="form-control" placeholder="Filtrar por descripción">
        </div>
        <div class="col-md-4">
            <label for="filter-month" class="form-label">Mes</label>
            <input type="month" id="filter-month" class="form-control">
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button id="apply-filters" class="btn btn-primary w-100">Aplicar Filtros</button>
        </div>
    </div>

    <!-- Tabla -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th>Monto (€)</th>
                    <th>Fecha</th>
                    <th>Factura</th>
                </tr>
            </thead>
            <tbody id="expense-table-body">
                <!-- Filas dinámicas -->
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <nav>
        <ul class="pagination justify-content-center" id="pagination">
            <!-- Botones dinámicos -->
        </ul>
    </nav>

    <!-- Gráfico -->
    <div class="my-5">
        <canvas id="expense-chart" height="100"></canvas>
    </div>
</div>

<script>
    let expenses = []; // Array para almacenar los datos
    let filteredExpenses = []; // Datos filtrados
    const rowsPerPage = 10;
    let currentPage = 1;

    async function fetchExpenses() {
        try {
            const response = await fetch('../php/CRUD/gastos/ver_facturaUsuario.php');
            const data = await response.json();

            if (data.error) {
                console.error(data.error);
                return;
            }

            expenses = data;
            filteredExpenses = data; // Inicialmente, no hay filtros
            renderTable();
            renderPagination();
            renderChart(); // Carga el gráfico global
        } catch (error) {
            console.error('Error al cargar los gastos:', error);
        }
    }

    function renderTable() {
        const tableBody = document.getElementById('expense-table-body');
        tableBody.innerHTML = '';

        const startIndex = (currentPage - 1) * rowsPerPage;
        const endIndex = startIndex + rowsPerPage;
        const currentPageData = filteredExpenses.slice(startIndex, endIndex);

        currentPageData.forEach(expense => {
            const row = `
            <tr>
                <td>${expense.descripcion}</td>
                <td>${expense.monto}</td>
                <td>${new Date(expense.fecha).toLocaleDateString()}</td>
                <td><a href="../uploads/${expense.factura}" target="_blank">Ver Factura</a></td>
            </tr>
        `;
            tableBody.insertAdjacentHTML('beforeend', row);
        });
    }

    function renderPagination() {
        const totalPages = Math.ceil(filteredExpenses.length / rowsPerPage);
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
    }

    // Aplicar filtros
    document.getElementById('apply-filters').addEventListener('click', () => {
        const descriptionFilter = document.getElementById('filter-description').value.toLowerCase();
        const monthFilter = document.getElementById('filter-month').value;

        filteredExpenses = expenses.filter(expense => {
            const matchesDescription = expense.descripcion.toLowerCase().includes(descriptionFilter);
            const matchesMonth = monthFilter ?
                new Date(expense.fecha).toISOString().slice(0, 7) === monthFilter :
                true;
            return matchesDescription && matchesMonth;
        });

        currentPage = 1; // Resetear a la primera página
        renderTable();
        renderPagination();
        renderChart(monthFilter); // Carga el gráfico específico
    });

    // Renderizar gráfico
    function renderChart(selectedMonth = null) {
        const ctx = document.getElementById('expense-chart').getContext('2d');

        const groupedData = {};
        if (selectedMonth) {
            // Mostrar datos día a día dentro del mes seleccionado
            filteredExpenses.forEach(expense => {
                const date = new Date(expense.fecha);
                const month = date.toISOString().slice(0, 7);
                const day = date.toISOString().slice(0, 10);

                if (month === selectedMonth) {
                    if (!groupedData[day]) {
                        groupedData[day] = 0;
                    }
                    groupedData[day] += parseFloat(expense.monto);
                }
            });
        } else {
            // Mostrar datos por mes globalmente
            filteredExpenses.forEach(expense => {
                const month = new Date(expense.fecha).toISOString().slice(0, 7);
                if (!groupedData[month]) {
                    groupedData[month] = 0;
                }
                groupedData[month] += parseFloat(expense.monto);
            });
        }

        const labels = Object.keys(groupedData).sort();
        const data = labels.map(label => groupedData[label]);

        // Destruir gráfico anterior si existe
        if (window.expenseChart) {
            window.expenseChart.destroy();
        }

        window.expenseChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: selectedMonth ? 'Gastos por Día (€)' : 'Gastos por Mes (€)',
                    data,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: (context) => `€${context.raw.toFixed(2)}`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    // Cargar datos al iniciar
    fetchExpenses();
</script>