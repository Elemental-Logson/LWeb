<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Si es un login por SSO, redirige a loginsso.php
    if (isset($_POST['sso_login'])) {
        header('Location: ' . "loginsso.php");
        exit;
    }
    // Si es un login por otra plataforma, redirige a otro archivo
    if (isset($_POST['other_platform_login'])) {
        header('Location: ' . "loginotherplatform.php");
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
        <div class="card p-4 shadow" style="width: 24rem;">
            <h3 class="text-center mb-4">Iniciar sesión</h3>
            <!-- Botón de inicio de sesión SSO -->
            <form method="POST">
                <input type="hidden" name="sso_login" value="1">
                <button type="submit" class="btn btn-secondary btn-lg w-100 mb-3">
                    Iniciar sesión con SSO
                </button>
            </form>
            <!-- Botón de inicio de sesión para otra plataforma -->
            <form method="POST">
                <input type="hidden" name="other_platform_login" value="1">
                <button type="submit" class="btn btn-primary btn-lg w-100">
                    Login otra plataforma
                </button>
            </form>
        </div>
    </div>
    <!-- Enlace a Bootstrap JS (opcional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>