<?php
session_start(); // Iniciar la sesión

// Comprobar si hay un token de autenticación en la sesión
if (!isset($_SESSION['authToken'])) {
    // Si no hay token o el rol no es admin, destruir la sesión y redirigir al login
    session_unset();  // Elimina todas las variables de sesión
    session_destroy(); // Destruye la sesión
    header('Location: login.php'); // Redirige al login
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <!-- Bootstrap y LineIcons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/panelAdministracion.css">
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div id="sidebar" class="bg-dark text-white p-3 d-flex flex-column justify-content-between">
            <div>
                <h3 class="mb-4">Panel Admin</h3>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="#" class="nav-link text-white" id="dashboard-link"><i class="bi bi-speedometer"></i>
                            Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link text-white" id="usuarios-link"><i class="bi bi-people"></i>
                            Usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link text-white" id="transacciones-link"><i class="bi bi-wallet2"></i>
                            Transacciones</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link text-white" id="escaner-link"><i class="bi bi-bug"></i>
                            Escaner</a>
                    </li>
                </ul>
            </div>
            <!-- Perfil del usuario -->
            <div id="user-profile" class="mt-4 border-top pt-3">
                <!-- Nombre del usuario -->
                <h3 class="mb-4"><?php echo $_SESSION['username']; ?></h3>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="#" class="nav-link text-white" id="perfil-link"><i class="bi bi-person"></i>
                            Ver Perfil</a>
                    </li>
                    <li class="nav-item">
                        <a href="./logout.php" class="nav-link text-white">
                            <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                        </a>
                    </li>
                </ul>
            </div>
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