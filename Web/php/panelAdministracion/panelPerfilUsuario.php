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
?>
<div class="d-flex" style="height: 100vh;">
    <div class="flex-grow-1 p-4">
        <h2>Perfil de Usuario</h2>
        <form id="userProfileForm" action="updateProfile.php" method="POST">
            <div class="row mb-3">
                <label for="name" class="col-sm-2 col-form-label">Nombre</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="name" name="name" value="<?= $firstName ?>" readonly>
                </div>
            </div>
            <div class="row mb-3">
                <label for="email" class="col-sm-2 col-form-label">Correo Electrónico</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email) ?>" readonly>
                </div>
            </div>
            <div class="row mb-3">
                <label for="lastName" class="col-sm-2 col-form-label">Apellido</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="lastName" name="lastName" value="<?= htmlspecialchars($lastName) ?>" readonly>
                </div>
            </div>
            <div class="row mb-3">
                <label for="lastName" class="col-sm-2 col-form-label">Ultimo inicio de sesión</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="auth_time" name="auth_time" value="<?= htmlspecialchars($auth_time) ?>" readonly>
                </div>
            </div>
            <button type="button" class="btn btn-primary" id="editButton">Editar</button>
            <button type="submit" class="btn btn-success d-none" id="saveButton">Guardar</button>
        </form>
    </div>
</div>
<script>
    const editButton = document.getElementById('editButton');
    const saveButton = document.getElementById('saveButton');
    const formFields = document.querySelectorAll('#userProfileForm input');

    editButton.addEventListener('click', () => {
        formFields.forEach(field => field.removeAttribute('readonly'));
        saveButton.classList.remove('d-none');
        editButton.classList.add('d-none');
    });
</script>