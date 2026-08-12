<?php
session_start();
require_once "../models/Usuario.php";
require_once "../models/ConfiguracionNotificaciones.php";
require_once "../../config/database.php";

if (!isset($_SESSION['usuario'])) {
    header("Location: ../../public/index.php");
    exit();
}

$id_usuario = (int) $_SESSION['usuario']['id_usuario'];

if (isset($_POST['guardar_perfil'])) {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($nombre !== '' && $email !== '') {
        Usuario::actualizarPerfil($id_usuario, $nombre, $email);
        $_SESSION['usuario']['nombre'] = $nombre;
        $_SESSION['usuario']['email'] = $email;
    }

    header("Location: ../views/configuracion.php");
    exit();
}

if (isset($_POST['guardar_configuracion'])) {
    $tipos = [
        'reporte' => 4,
        'pagos' => 1,
        'exceso' => 3
    ];

    foreach ($tipos as $clave => $id_tipo) {
        $appKey = $clave . '_app';
        $correoKey = $clave . '_correo';
        $notificacion_app = isset($_POST[$appKey]) ? 1 : 0;
        $notificacion_correo = isset($_POST[$correoKey]) ? 1 : 0;

        ConfiguracionNotificaciones::guardarOActualizar($id_usuario, $id_tipo, $notificacion_app, $notificacion_correo, 2);
    }

    header("Location: ../views/configuracion.php");
    exit();
}

// ELIMINAR
if (isset($_GET['delete'])) {

    $id_usuario = $_SESSION['usuario']['id_usuario'];
    $id_tipo = $_GET['delete'];
    ConfiguracionNotificaciones::cambiarEstado($id_usuario, $id_tipo, 1); // Cambiar estado a "inactivo"

    header("Location: ../views/configuracion.php");
    exit(); 
}

// ACTUALIZAR (PRIMERO)
if (isset($_POST['actualizar'])) {

    $notificacion_app = $_POST['notificacion_app'];
    $notificacion_correo = $_POST['notificacion_correo'];
    $id_tipo = $_POST['id_tipo'];
    $id_usuario = $_SESSION['usuario']['id_usuario'];
    $id_estado = $_POST['id_estado'];

    ConfiguracionNotificaciones::actualizar($id_usuario, $id_tipo, $notificacion_app, $notificacion_correo, $id_estado);

    header("Location: ../views/configuracion.php");
    exit();
}

// GUARDAR (SOLO SI NO ES ACTUALIZAR)
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['actualizar'])) {

    $notificacion_app = $_POST['notificacion_app'];
    $notificacion_correo = $_POST['notificacion_correo'];
    $id_tipo = $_POST['id_tipo'];
    $id_usuario = $_SESSION['usuario']['id_usuario'];
    $id_estado = $_POST['id_estado'];



    ConfiguracionNotificaciones::guardar($notificacion_app, $notificacion_correo, $id_usuario, $id_tipo, $id_estado);

    header("Location: ../views/configuracion.php");
    exit();
}
?>


