<?php
session_start();
require_once "../models/Gasto.php";

if (!isset($_SESSION['usuario'])) {
    header("Location: ../../public/index.php");
    exit();
}

$id_usuario = (int) $_SESSION['usuario']['id_usuario'];

// ELIMINAR
if (isset($_POST['accion']) && $_POST['accion'] === 'eliminarGasto') {

    $id_gasto = (int) ($_POST['id_gasto'] ?? 0);

    if ($id_gasto > 0) {
        Gasto::eliminar($id_usuario, $id_gasto);
    }

    header("Location: ../views/gastos.php");
    exit(); 
}

// GUARDAR
if (isset($_POST['accion']) && $_POST['accion'] === 'crearGasto') {

    $nombre = trim($_POST['nombre']);
    $monto = (float) $_POST['monto'];
    $fecha = $_POST['fecha'];
    $descripcion = trim($_POST['descripcion'] ?? '');
    $id_categoria = (int) $_POST['id_categoria'];
    $id_estado = (int) $_POST['id_estado'];


    Gasto::guardar($nombre, $monto, $fecha, $descripcion, $id_categoria, $id_usuario, $id_estado);

    header("Location: ../views/gastos.php");
    exit();
}
?>


