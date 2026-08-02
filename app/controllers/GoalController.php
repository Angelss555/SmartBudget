<?php
session_start();
require_once "../models/Meta.php";

if (!isset($_SESSION['usuario'])) {
    header("Location: ../../public/index.php");
    exit();
}

$id_usuario = (int) $_SESSION['usuario']['id_usuario'];


// ELIMINAR
if (isset($_POST['accion']) && $_POST['accion'] === 'eliminarMeta') {

    $id_meta = (int) ($_POST['id_meta'] ?? 0);

    if ($id_meta > 0) {
        Meta::eliminar($id_usuario, $id_meta);
    }

    header("Location: ../views/metas.php");
    exit(); 
}

// GUARDAR
if (isset($_POST['accion']) && $_POST['accion'] === 'crearMeta') {

    $nombre = $_POST['nombre'];
    $monto_inicial = (float) $_POST['monto_inicial'];
    $monto_objetivo = (float) $_POST['monto_objetivo'];
    $cuota = (float) $_POST['cuota'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_cumplimiento = $_POST['fecha_cumplimiento'];
    $descripcion = trim($_POST['descripcion'] ?? '');
    $id_estado = (int) $_POST['id_estado'];

    Meta::guardar($nombre, $monto_inicial, $monto_objetivo, $cuota, $fecha_inicio, $fecha_cumplimiento, $descripcion, $id_usuario, $id_estado);

    header("Location: ../views/metas.php");
    exit();
}
?>


