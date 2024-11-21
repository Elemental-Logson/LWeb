<?php
session_start(); // Inicia la sesión

// Usuarios hardcodeados (puedes cambiarlo por una base de datos real)
$users = [
    'admin' => ['password' => 'admin', 'role' => 'admin'],
    'user' => ['password' => 'user', 'role' => 'user']
];

// Verificar si el formulario fue enviado (login tradicional)
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
    } else {
        $login_error = 'Usuario o contraseña incorrectos.';
    }
}

// Configuración SSO (Keycloak)
$realm = 'SSO';  // Nombre del realm
$client_id = 'SSO';  // ID del cliente registrado en Keycloak
$client_secret = 'RtrWyqSSa3lrCopGjjLh5NHWGKrHtMtY';  // Secreto del cliente
$redirect_uri = 'http://localhost/lweb/Web/php/callback.php';  // URL de callback

// URL de Keycloak para la autenticación
$keycloak_url = 'http://10.11.0.96:8080/realms/' . $realm . '/protocol/openid-connect/auth';

// Generar una URL de autenticación para SSO
$auth_url = $keycloak_url . '?response_type=code&client_id=' . $client_id . '&redirect_uri=' . urlencode($redirect_uri);

// Si no existe el token de acceso SSO, redirigir al servidor de autenticación
if (!isset($_SESSION['access_token']) && isset($_GET['sso']) && $_GET['sso'] == 'true') {
    header('Location: ' . $auth_url);
    exit;
}

// Si ya se ha autenticado via SSO (el callback ha sido procesado)
if (isset($_SESSION['access_token'])) {
    // Aquí podrías hacer algo como obtener el nombre de usuario y rol del usuario desde Keycloak
    // Suponemos que esto ya está hecho en el callback.php después de la autenticación

    // Crear un token único de sesión similar a la autenticación tradicional
    $token = bin2hex(random_bytes(16)); // Genera un token aleatorio
    $_SESSION['authToken'] = $token;   // Guardar el token en la sesión
    $_SESSION['userRole'] = 'admin';   // Aquí puedes poner el rol recuperado desde Keycloak
    $_SESSION['username'] = 'user';    // Aquí puedes poner el nombre de usuario recuperado desde Keycloak

    // Redirigir al panel de control dependiendo del rol
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow" style="width: 22rem;">
            <h3 class="text-center mb-4">Iniciar sesión</h3>

            <!-- Formulario de autenticación tradicional -->
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

            <!-- Si hay un error de login -->
            <?php if (isset($login_error)) : ?>
                <div class="alert alert-danger mt-3" role="alert">
                    <?= $login_error ?>
                </div>
            <?php endif; ?>

            <!-- Botón de inicio de sesión SSO -->
            <a href="login.php?sso=true" class="btn btn-secondary w-100 mt-3">Iniciar sesión con SSO</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
