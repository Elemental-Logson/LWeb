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
<header class="custom-gray text-dark py-3 shadow-sm">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <!-- Logo o nombre de la aplicación -->
            <a class="navbar-brand custom-text text-decoration-none" href="#">Logson</a>

            <!-- Botón hamburguesa -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Barra de navegación -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link custom-text text-decoration-none" href="#">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link custom-text text-decoration-none" href="panelAdministracion.php">Panel
                            Administración</a>
                    </li>
                    <!-- Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle custom-text text-decoration-none" href="#" id="userDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <!-- Nombre del usuario -->
                            <?php echo $_SESSION['username']; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item" href="perfil.php">Ver Perfil</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="logout.php">Cerrar Sesión</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>