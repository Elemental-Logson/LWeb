<?php
session_start();
// Aquí puedes verificar si el usuario está logueado
if (!isset($_SESSION['access_token'])) {
    header("Location: /LWeb/Web/html/forbidden.html");
    exit();
}
define('ACCESO_PERMITIDO', true);

$view = $_GET['view'] ?? '';
$filePath = '';

switch ($view) {
    case 'dashboard':
        $filePath = __DIR__ . '/panelAdministracion/panelDashboard.php';
        break;
    case 'usuarios':
        $filePath = __DIR__ . '/panelAdministracion/panelUsuarios.php';
        break;
    case 'escaner':
        $filePath = __DIR__ . '/panelAdministracion/panelEscaner.php';
        break;
    case 'transacciones':
        $filePath = __DIR__ . '/panelAdministracion/panelTransacciones.php';
        break;
    case 'tarjetas':
        $filePath = __DIR__ . '/panelAdministracion/panelTarjetas.php';
        break;
    case 'perfil':
        $filePath = __DIR__ . '/panelAdministracion/panelPerfilUsuario.php';
        break;
    default:
        header("Location: /LWeb/Web/html/404.html");
        exit();
}

// Mostrar la ruta en la consola del navegador
// echo "<script>console.log('Cargando: " . addslashes($filePath) . "');</script>";

// Incluir el archivo
include $filePath;
