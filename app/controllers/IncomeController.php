<?php
session_start();
require_once "../models/Ingreso.php";
require_once "../../config/database.php";

if (!isset($_SESSION['usuario'])) {
    header("Location: ../../public/index.php");
    exit();
}

// ELIMINAR
if (isset($_GET['delete'])) {

    Ingreso::eliminar($_GET['delete']);

    header("Location: ../views/ingresos.php");
    exit(); 
}

// ACTUALIZAR (PRIMERO)
if (isset($_POST['actualizar'])) {

    $id_ingreso = $_POST['id_ingreso'];
    $nombre = $_POST['nombre'];
    $monto = $_POST['monto'];
    $fecha = $_POST['fecha'];
    $descripcion = $_POST['descripcion'];
    $id_categoria = $_POST['id_categoria'];
    $id_estado = $_POST['id_estado'];

    Ingreso::actualizar($id_ingreso, $nombre, $monto, $fecha, $descripcion, $id_categoria, $id_estado);

    header("Location: ../views/ingresos.php");
    exit();
}

// GUARDAR (SOLO SI NO ES ACTUALIZAR)
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['actualizar'])) {

    $nombre = $_POST['nombre'];
    $monto = $_POST['monto'];
    $fecha = $_POST['fecha'];
    $descripcion = $_POST['descripcion'];
    $id_categoria = $_POST['id_categoria'];
    $id_usuario = $_SESSION['usuario']['id_usuario'];
    $id_estado = $_POST['id_estado'];


    Ingreso::guardar($nombre, $monto, $fecha, $descripcion, $id_categoria, $id_usuario, $id_estado);

    header("Location: ../views/ingresos.php");
    exit();
}
?>


