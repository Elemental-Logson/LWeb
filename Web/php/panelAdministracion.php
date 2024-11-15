<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <!-- Bootstrap y LineIcons -->
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/panelAdministracion.css">
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div id="sidebar" class="bg-dark text-white p-3">
            <h3 class="mb-4">Panel Admin</h3>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="#" class="nav-link text-white" id="dashboard-link"><i class="lni lni-dashboard"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link text-white" id="usuarios-link"><i class="lni lni-users"></i> Usuarios</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link text-white" id="escaner-link"><i class="lni lni-cog"></i> Escaner</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link text-white" id="configuracion-link"><i class="lni lni-cog"></i> Configuración</a>
                </li>
            </ul>
        </div>

        <!-- Botón de tirador -->
        <button id="toggle-sidebar" class="btn d-lg-none">
            <span id="toggle-icon" class="lni lni-chevron-left"></span>
        </button>

        <!-- Contenido Principal -->
        <div id="main-content" class="flex-grow-1 p-4">

        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/panelAdministracion.js"></script>
</body>
</html>
