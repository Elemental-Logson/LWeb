<style>
    /* Contenedor de Slider */
    .range-container {
        position: relative;
        width: 100%;
        margin-top: 10px;
        height: 8px;
    }

    .range-track {
        position: absolute;
        top: 50%;
        left: 0;
        width: 100%;
        height: 8px;
        background: #ddd;
        border-radius: 5px;
        transform: translateY(-50%);
        z-index: 1;
    }

    input[type="range"] {
        -webkit-appearance: none;
        appearance: none;
        width: 100%;
        height: 8px;
        background: transparent;
        position: absolute;
        pointer-events: none;
    }

    input[type="range"]::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 20px;
        height: 20px;
        background: #007bff;
        border-radius: 50%;
        cursor: pointer;
        pointer-events: all;
        position: relative;
        z-index: 3;
    }

    input[type="range"]::-moz-range-thumb {
        width: 20px;
        height: 20px;
        background: #007bff;
        border-radius: 50%;
        cursor: pointer;
        pointer-events: all;
        position: relative;
        z-index: 3;
    }

    /* Ajuste para el input de rango máximo detrás del rango mínimo */
    input[type="range"]:nth-child(3) {
        z-index: 2;
    }

    .range-inputs {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 10px;
    }

    .range-inputs input {
        width: 100%;
    }

    /* Contenedor que agrupa el slider y los inputs */
    .range-wrapper {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    /* Media Queries para dispositivos pequeños */
    @media (min-width: 576px) {
        .range-wrapper {
            flex-direction: row;
            align-items: center;
        }

        .range-inputs {
            margin-top: 0;
            margin-left: 10px;
        }
    }
</style>

<div class="container my-5">
    <!-- Título y botones -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h1>Gestión de Gastos</h1>
        <div class="btn-group mt-3 mt-md-0">
            <button id="apply-filters" class="btn btn-primary"><i class="bi bi-search"></i></button>
            <button id="clear-filters" class="btn btn-danger"><i class="bi bi-x-circle"></i></button>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-12 col-md-3 mb-3 mb-md-0">
            <div class="form-floating">
                <input type="text" id="filter-description" class="form-control" placeholder="Filtrar por descripción">
                <label for="filter-description">Descripción</label>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-2 mb-3 mb-md-0">
            <div class="form-floating">
                <input type="date" id="filter-start-date" class="form-control" placeholder="Fecha Inicio">
                <label for="filter-start-date">Fecha Inicio</label>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-2 mb-3 mb-md-0">
            <div class="form-floating">
                <input type="date" id="filter-end-date" class="form-control" placeholder="Fecha Fin">
                <label for="filter-end-date">Fecha Fin</label>
            </div>
        </div>
        <div class="col-12 col-md-5">
            <label for="filter-price-range" class="form-label">Rango de Precios (€)</label>
            <div class="range-wrapper">
                <div class="range-container">
                    <!-- Fondo de la pista -->
                    <div class="range-track"></div>
                    <!-- Slider inferior (mínimo) -->
                    <input type="range" id="filter-price-range" min="0" max="1000" step="10" value="0" oninput="updatePriceLabels()">
                    <!-- Slider superior (máximo) -->
                    <input type="range" id="filter-price-range-max" min="0" max="1000" step="10" value="1000" oninput="updatePriceLabels()">
                </div>
                <div class="range-inputs">
                    <input type="number" id="price-min-input" class="form-control" value="0" min="0" max="1000" step="10" oninput="updateSliderFromInput()">
                    <input type="number" id="price-max-input" class="form-control" value="1000" min="0" max="1000" step="10" oninput="updateSliderFromInput()">
                </div>
            </div>
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
</div>

<script>
    var expenses = [];
    var filteredExpenses = [];
    var rowsPerPage = 10;
    var currentPage = 1;

    // Obtener elementos del DOM
    var priceMinSlider = document.getElementById('filter-price-range');
    var priceMaxSlider = document.getElementById('filter-price-range-max');
    var priceMinInput = document.getElementById('price-min-input');
    var priceMaxInput = document.getElementById('price-max-input');

    // Actualizar etiquetas del rango de precios
    function updatePriceLabels() {
        let minValue = parseInt(priceMinSlider.value);
        let maxValue = parseInt(priceMaxSlider.value);

        // Asegurarse de que los sliders no se crucen
        if (minValue > maxValue) {
            if (event.target === priceMinSlider) {
                priceMinSlider.value = maxValue;
                minValue = maxValue;
            } else {
                priceMaxSlider.value = minValue;
                maxValue = minValue;
            }
        }

        // Actualizar los valores de los inputs numéricos
        priceMinInput.value = minValue;
        priceMaxInput.value = maxValue;
    }

    // Función para actualizar sliders desde los inputs numéricos
    function updateSliderFromInput() {
        let minValue = parseInt(priceMinInput.value);
        let maxValue = parseInt(priceMaxInput.value);

        // Validar los límites y ajustar los valores si son inválidos
        if (minValue < parseInt(priceMinSlider.min)) {
            minValue = parseInt(priceMinSlider.min);
        } else if (minValue > parseInt(priceMaxSlider.max)) {
            minValue = parseInt(priceMaxSlider.max);
        }

        if (maxValue > parseInt(priceMaxSlider.max)) {
            maxValue = parseInt(priceMaxSlider.max);
        } else if (maxValue < parseInt(priceMinSlider.min)) {
            maxValue = parseInt(priceMinSlider.min);
        }

        // Asegurarse de que los valores no se crucen
        if (minValue > maxValue) {
            minValue = maxValue;
        }

        // Actualizar sliders
        priceMinSlider.value = minValue;
        priceMaxSlider.value = maxValue;
    }

    // Aplicar filtros
    document.getElementById('apply-filters').addEventListener('click', () => {
        const descriptionFilter = document.getElementById('filter-description').value.toLowerCase();
        const startDateFilter = document.getElementById('filter-start-date').value;
        const endDateFilter = document.getElementById('filter-end-date').value;
        const priceMinFilter = parseInt(priceMinSlider.value);
        const priceMaxFilter = parseInt(priceMaxSlider.value);

        filteredExpenses = expenses.filter(expense => {
            const matchesDescription = expense.descripcion.toLowerCase().includes(descriptionFilter);
            const matchesStartDate = startDateFilter ? new Date(expense.fecha) >= new Date(startDateFilter) : true;
            const matchesEndDate = endDateFilter ? new Date(expense.fecha) <= new Date(endDateFilter) : true;
            const matchesPrice = expense.monto >= priceMinFilter && expense.monto <= priceMaxFilter;

            return matchesDescription && matchesStartDate && matchesEndDate && matchesPrice;
        });

        currentPage = 1; // Resetear a la primera página
        renderTable();
        renderPagination();
    });

    // Limpiar filtros
    document.getElementById('clear-filters').addEventListener('click', () => {
        // Resetear todos los campos de filtro
        document.getElementById('filter-description').value = '';
        document.getElementById('filter-start-date').value = '';
        document.getElementById('filter-end-date').value = '';

        // Resetear sliders y inputs numéricos
        priceMinSlider.value = priceMinSlider.min;
        priceMaxSlider.value = priceMaxSlider.max;
        priceMinInput.value = priceMinSlider.min;
        priceMaxInput.value = priceMaxSlider.max;

        // Actualizar etiquetas del rango de precios
        updatePriceLabels();

        // Restablecer los gastos filtrados
        filteredExpenses = expenses.slice();
        currentPage = 1;
        renderTable();
        renderPagination();
    });

    // Función para cargar los gastos al iniciar
    async function fetchExpenses() {
        try {
            const response = await fetch('../php/CRUD/gastos/ver_facturaUsuario.php');
            const data = await response.json();

            if (data.error) {
                console.error(data.error);
                return;
            }

            expenses = data;
            filteredExpenses = expenses.slice(); // Inicialmente, no hay filtros
            renderTable();
            renderPagination();
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
                <td><a href="../../img/${expense.factura}" target="_blank">Ver Factura</a></td>
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

    // Inicialización de datos
    fetchExpenses();
</script>
