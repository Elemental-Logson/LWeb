<?php
session_start();

if (!isset($_SESSION['username'])) {
    echo 'Error: Usuario no autenticado.';
    exit();
}

// Recuperar los datos de la sesión
$username = $_SESSION['username'] ?? '';
$email = $_SESSION['email'] ?? '';
$firstName = $_SESSION['given_name'] ?? '';
$lastName = $_SESSION['family_name'] ?? '';
$auth_time = $_SESSION['auth_time'] ?? '';
$role = $_SESSION['role'] ?? 'No asignado'; // Rol asignado o valor predeterminado
?>
<div class="container-fluid d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow" style="max-width: 800px; width: 100%;">
        <div class="row g-0">
            <!-- Columna para la información del usuario -->
            <div class="col-md-8 p-4">
                <h2 class="card-title">Perfil de Usuario</h2>
                <form id="userProfileForm" action="updateProfile.php" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($firstName) ?>" readonly>
                                <label for="name">Nombre</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="lastName" name="lastName" value="<?= htmlspecialchars($lastName) ?>" readonly>
                                <label for="lastName">Apellido</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email) ?>" readonly>
                        <label for="email">Correo Electrónico</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="auth_time" name="auth_time" value="<?= htmlspecialchars($auth_time) ?>" readonly>
                        <label for="auth_time">Último inicio de sesión</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="role" name="role" value="<?= htmlspecialchars($role) ?>" readonly>
                        <label for="role">Rol</label>
                    </div>
                </form>
            </div>

            <!-- Columna para el bloque gris -->
            <div class="col-md-4 d-flex align-items-center justify-content-center p-4">
                <div class="bg-secondary rounded-circle" style="width: 150px; height: 150px;"></div>
            </div>
        </div>
    </div>
</div>