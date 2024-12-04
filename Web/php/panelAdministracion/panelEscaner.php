<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h3>Panel de Escáner de Red y Vulnerabilidades</h3>
            <button type="button" class="btn btn-primary m-2" data-bs-toggle="modal" data-bs-target="#scannerModal">Iniciar Escaneo</button>
        </div>
    </div>

    <!-- Modal Escáner -->
    <div class="modal fade" id="scannerModal" tabindex="-1" aria-labelledby="scannerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scannerModalLabel">Escáner de Red y Vulnerabilidades</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="targetRange" class="form-label">Introduce la IP o el rango a escanear</label>
                            <input type="text" class="form-control" id="targetRange" placeholder="Ej. 10.11.0.0/24">
                        </div>
                        <div class="mb-3">
                            <label for="scanName" class="form-label">Introduce un nombre para el escaneo</label>
                            <input type="text" class="form-control" id="scanName" placeholder="Ej. varias ips">
                        </div>
                        <div class="mb-3">
                            <label for="portRange" class="form-label">Introduce el rango de puertos (por defecto 1-1024)</label>
                            <input type="text" class="form-control" id="portRange" placeholder="Ej. 1-1024">
                        </div>
                        <div class="mb-3">
                            <label for="scanIntensity" class="form-label">Seleccione la intensidad del escaneo</label>
                            <select class="form-select" id="scanIntensity">
                                <option value="normal">Normal (por defecto)</option>
                                <option value="aggressive">Agresivo</option>
                                <option value="intense">Intenso</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="scanType" class="form-label">Seleccione el tipo de escaneo</label>
                            <select class="form-select" id="scanType">
                                <option value="ports_services">Puertos y servicios</option>
                                <option value="vulnerabilities">Vulnerabilidades</option>
                            </select>
                        </div>
                        <button type="button" class="btn btn-primary w-100">Ejecutar Escaneo</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Resultados Escaner -->
    <div id="scanTableContainer" class="container mt-5">
        <!-- Tabla Scan -->
        <div class="table-responsive mt-4">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Fecha del Escaneo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="scanTableBody">
                    <!-- Datos dinámicos insertados aquí -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Contenedor para los detalles del escaneo -->
    <div id="scanDetailsContainer" class="container mt-4 hidden">
        <!-- Aquí se mostrarán los detalles del escaneo seleccionado -->
    </div>

    <!-- Contenedor para las vulnerabilidades -->
    <div id="vulnerabilitiesContainer" class="container mt-4 hidden">
        <!-- Aquí se mostrarán las vulnerabilidades de la IP seleccionada -->
    </div>
</div>

<script>
    loadScan();

    function loadScan() {
        fetch('../php/CRUD/escaner/ver_Escaner.php')
            .then(response => response.json())
            .then(data => {
                const expenseTableBody = document.querySelector('#scanTableBody');
                expenseTableBody.innerHTML = '';

                data.forEach(escaner => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                    <td>${escaner.id}</td>
                    <td>${escaner.name}</td>
                    <td>${escaner.scan_date}</td>
                    <td>
                        <a href="#" onclick="viewScanDetails(${escaner.id})">Ver Escaner</a> |
                        <a href="#" onclick="deleteScan(${escaner.id})">Eliminar</a>
                    </td>
                `;
                    expenseTableBody.appendChild(row);
                });
            })
            .catch(error => console.error('Error al cargar los escaneres:', error));
    }

    function viewScanDetails(scanId) {
        // Ocultar la tabla principal de escaneos
        const scanTableContainer = document.getElementById('scanTableContainer');
        const scanDetailsContainer = document.querySelector('#scanDetailsContainer');
        scanTableContainer.style.display = 'none';

        // Hacer la petición para obtener los detalles del escaneo
        fetch(`../php/CRUD/escaner/ver_ScannedIPs.php?scan_id=${scanId}`)
            .then(response => response.json())
            .then(data => {
                let detailsContent = `
                <h5>Detalles del Escaneo ID: ${scanId}</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Host</th>
                            <th>Hostname</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                `;

                data.forEach(ip => {
                    detailsContent += `
                    <tr>
                        <td>${ip.id}</td>
                        <td>${ip.host}</td>
                        <td>${ip.hostname}</td>
                        <td>${ip.state}</td>
                        <td>
                            <a href="#" onclick="viewVulnerabilities(${ip.id})">Ver Vulnerabilidades</a> |
                            <a href="#" onclick="deleteScannedIp(${ip.id})">Eliminar</a>
                        </td>
                    </tr>
                `;
                });

                detailsContent += `
                    </tbody>
                </table>
                <button class="btn btn-secondary mt-3" onclick="goBackToMainTable()">Volver</button>
                `;

                // Mostrar los detalles del escaneo
                scanDetailsContainer.innerHTML = detailsContent;
                scanDetailsContainer.style.display = 'block';
            })
            .catch(error => console.error('Error al cargar los detalles del escaneo:', error));
    }

    function viewVulnerabilities(scannedIpId) {
        // Ocultar los detalles previos de vulnerabilidades
        document.getElementById('vulnerabilitiesContainer').style.display = 'none';

        // Hacer la petición para obtener los detalles de las vulnerabilidades
        fetch(`../php/CRUD/escaner/ver_Vulnerabilidades.php?scanned_ip_id=${scannedIpId}`)
            .then(response => response.json())
            .then(data => {
                let vulnerabilitiesContent = `
                <h5>Vulnerabilidades para la IP ID: ${scannedIpId}</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Puerto</th>
                            <th>Protocolo</th>
                            <th>Servicio</th>
                            <th>Versión</th>
                            <th>Producto</th>
                            <th>Salida del Script</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                `;

                data.forEach(detail => {
                    vulnerabilitiesContent += `
                    <tr>
                        <td>${detail.id}</td>
                        <td>${detail.port}</td>
                        <td>${detail.protocol}</td>
                        <td>${detail.service}</td>
                        <td>${detail.version}</td>
                        <td>${detail.product}</td>
                        <td>${detail.script_output}</td>
                        <td>
                            <a href="#" onclick="deleteVulnerability(${detail.id})">Eliminar</a>
                        </td>
                    </tr>
                `;
                });

                vulnerabilitiesContent += `
                    </tbody>
                </table>
                <button class="btn btn-secondary mt-3" onclick="goBackToScanDetails()">Volver</button>
                `;

                // Mostrar las vulnerabilidades en su contenedor específico
                const vulnerabilitiesContainer = document.getElementById('vulnerabilitiesContainer');
                vulnerabilitiesContainer.innerHTML = vulnerabilitiesContent;
                document.getElementById('scanDetailsContainer').style.display = 'none';
                vulnerabilitiesContainer.style.display = 'block';
            })
            .catch(error => console.error('Error al cargar las vulnerabilidades:', error));
    }

    function goBackToMainTable() {
        // Mostrar la tabla principal y ocultar los contenedores de detalles y vulnerabilidades
        document.getElementById('scanTableContainer').style.display = 'block';
        document.getElementById('scanDetailsContainer').style.display = 'none';
        document.getElementById('vulnerabilitiesContainer').style.display = 'none';
    }

    function goBackToScanDetails() {
        // Mostrar los detalles del escaneo y ocultar el contenedor de vulnerabilidades
        document.getElementById('vulnerabilitiesContainer').style.display = 'none';
        document.getElementById('scanDetailsContainer').style.display = 'block';
    }

    function deleteScan(scanId) {
        // Confirmar antes de eliminar el registro
        if (confirm('¿Estás seguro de que deseas eliminar este escaneo?')) {
            fetch('../php/CRUD/escaner/eliminar_Escaner.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        scan_id: scanId
                    })
                })
                .then(response => {
                    if (response.ok) {
                        alert('Escaneo eliminado con éxito.');
                        loadScan(); // Recargar la lista de escaneos
                    } else {
                        alert('Error al eliminar el escaneo.');
                    }
                })
                .catch(error => console.error('Error al eliminar el escaneo:', error));
        }
    }

    function deleteScannedIp(ipId) {
        // Confirmar antes de eliminar el registro
        if (confirm('¿Estás seguro de que deseas eliminar este registro de IP escaneada?')) {
            fetch('../php/CRUD/escaner/eliminar_ScannedIP.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        ip_id: ipId
                    })
                })
                .then(response => {
                    if (response.ok) {
                        alert('Registro de IP escaneada eliminado con éxito.');
                        // Recargar los detalles del escaneo para reflejar el cambio
                        goBackToMainTable();
                        loadScan();
                    } else {
                        alert('Error al eliminar el registro de IP escaneada.');
                    }
                })
                .catch(error => console.error('Error al eliminar el registro de IP escaneada:', error));
        }
    }

    function deleteVulnerability(vulnerabilityId) {
        // Confirmar antes de eliminar el registro
        if (confirm('¿Estás seguro de que deseas eliminar esta vulnerabilidad?')) {
            fetch('../php/CRUD/escaner/eliminar_Vulnerabilidad.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        ip_id: vulnerabilityId
                    })
                })
                .then(response => {
                    if (response.ok) {
                        alert('Vulnerabilidad eliminada con éxito.');
                        // Recargar la lista de vulnerabilidades para reflejar el cambio
                        goBackToScanDetails();
                        loadScan();
                    } else {
                        alert('Error al eliminar la vulnerabilidad.');
                    }
                })
                .catch(error => console.error('Error al eliminar la vulnerabilidad:', error));
        }
    }
</script>