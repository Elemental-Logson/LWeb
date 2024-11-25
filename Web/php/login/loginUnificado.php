<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Si es un login por sso abrimos otro archivo php para que se ocupe de la gestión.
    if (isset($_POST['sso_login'])) {
        header('Location: ' . "loginsso.php");
        exit;
    } else {
        // Manejo de inicio de sesión normal estoy tendremos que cambiarlo
        $users = [
            'admin' => ['password' => 'admin', 'role' => 'admin'],
            'user' => ['password' => 'user', 'role' => 'user']
        ];

        $username = $_POST['usuario'];
        $password = $_POST['contraseña'];

        if (isset($users[$username]) && $users[$username]['password'] === $password) {
            $_SESSION['authToken'] = bin2hex(random_bytes(16));
            $_SESSION['userRole'] = $users[$username]['role'];
            $_SESSION['username'] = $username;

            // Redirección con Meta Refresh
            echo '<meta http-equiv="refresh" content="0;url=/lweb/Web/php/dashboard.php">';
            exit;
        } else {
            echo 'Usuario o contraseña incorrectos.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Enlace correcto a Bootstrap desde CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Enlace correcto a Font Awesome desde CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow" style="width: 22rem;">
            <h3 class="text-center mb-4">Iniciar sesión</h3>
            <!-- Formulario de inicio de sesión normal -->
            <form method="POST">
                <div class="mb-3 form-floating">
                    <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Usuario" required>
                    <label for="usuario">Usuario</label>
                </div>
                <div class="mb-3 form-floating">
                    <input type="password" class="form-control" id="contraseña" name="contraseña" placeholder="Contraseña" required>
                    <label for="contraseña">Contraseña</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">Entrar</button>
            </form>
            <!-- Botón de inicio de sesión SSO -->
            <form method="POST">
                <input type="hidden" name="sso_login" value="1">
                <button type="submit" class="btn btn-secondary w-100 mt-3">
                    Iniciar sesión con SSO
                </button>
            </form>
        </div>
    </div>
    <!-- Enlace a Bootstrap JS (opcional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>