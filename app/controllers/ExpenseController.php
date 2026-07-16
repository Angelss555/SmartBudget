<?php
session_start();
require_once "../models/Gasto.php";
require_once "../../config/database.php";

if (!isset($_SESSION['usuario'])) {
    header("Location: ../../public/index.php");
    exit();
}

// ELIMINAR
if (isset($_GET['delete'])) {

    Gasto::eliminar($_GET['delete']);

    header("Location: ../views/gastos.php");
    exit(); 
}

// ACTUALIZAR (PRIMERO)
if (isset($_POST['actualizar'])) {

    $id_gasto = $_POST['id_gasto'];
    $nombre = $_POST['nombre'];
    $monto = $_POST['monto'];
    $fecha = $_POST['fecha'];
    $descripcion = $_POST['descripcion'];
    $id_categoria = $_POST['id_categoria'];
    $id_estado = $_POST['id_estado'];

    Gasto::actualizar($id_gasto, $nombre, $monto, $fecha, $descripcion, $id_categoria, $id_estado);

    header("Location: ../views/gastos.php");
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


    Gasto::guardar($nombre, $monto, $fecha, $descripcion, $id_categoria, $id_usuario, $id_estado);

    header("Location: ../views/gastos.php");
    exit();
}
?>


