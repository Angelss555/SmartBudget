<?php
session_start();
require_once "../models/Ingreso.php";

if (!isset($_SESSION['usuario'])) {
    header("Location: ../../public/index.php");
    exit();
}

$id_usuario =(int) $_SESSION['usuario']['id_usuario'];


// ELIMINAR
if (isset($_POST['accion']) && $_POST['accion'] === 'eliminarIngreso') {

    $id_ingreso = (int) ($_POST['id_ingreso'] ?? 0);

    if($id_ingreso > 0) {
        Ingreso::eliminar( $id_usuario, $id_ingreso);
    }

    header("Location: ../views/ingresos.php");
    exit(); 
}

// GUARDAR
if (isset($_POST['accion']) && $_POST['accion'] === 'crearIngreso') {

    $nombre = $_POST['nombre'];
    $monto = (float) $_POST['monto'];
    $fecha = $_POST['fecha-ingreso'];
    $descripcion = trim($_POST['descripcion'] ?? '');
    $id_categoria = (int) $_POST['id_categoria'];
    $id_estado = (int) $_POST['id_estado'];


    Ingreso::guardar($nombre, $monto, $fecha, $descripcion, $id_categoria, $id_usuario, $id_estado);

    header("Location: ../views/ingresos.php");
    exit();
}
?>


