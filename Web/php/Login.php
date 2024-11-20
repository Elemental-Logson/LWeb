<?php
session_start(); // Inicia la sesión

// Usuarios hardcodeados (puedes cambiarlo por una base de datos real)
$users = [
    'admin' => ['password' => 'admin', 'role' => 'admin'],
    'user' => ['password' => 'user', 'role' => 'user']
];

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['usuario'];
    $password = $_POST['contraseña'];

    // Verificar si el usuario existe
    if (isset($users[$username]) && $users[$username]['password'] === $password) {
        // Crear un token único de sesión
        $token = bin2hex(random_bytes(16)); // Genera un token aleatorio
        $_SESSION['authToken'] = $token;   // Guardar el token en la sesión
        $_SESSION['userRole'] = $users[$username]['role']; // Guardar el rol del usuario
        $_SESSION['username'] = $username;
        // Redirigir al panel de control dependiendo del rol
        if ($users[$username]['role'] === 'admin') {
            header('Location: dashboard.php');
        } else {
            header('Location: dashboard.php');
        }
        exit;
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
            <form action="login.php" method="POST">
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
            <button type="button" class="btn btn-secondary w-100 mt-3" id="sso-login-btn">
                Iniciar sesión con SSO
            </button>
        </div>
    </div>
    <!-- Enlace a Bootstrap JS (opcional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

