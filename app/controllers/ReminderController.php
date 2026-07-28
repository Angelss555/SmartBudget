<?php
session_start();
require_once "../models/Recordatorio.php";
require_once "../../config/database.php";

if (!isset($_SESSION['usuario'])) {
    header("Location: ../../public/index.php");
    exit();
}

$id_usuario = $_SESSION['usuario']['id_usuario'];


//**** RECORDATORIOS NO TIENE IMPLEMENTADA UNA VIEW*****************

// ELIMINAR
if (isset($_GET['delete'])) {

    Recordatorio::eliminar($id_usuario, $_GET['delete']);

    header("Location: ../views/recordatorios.php");
    exit(); 
}

// ACTUALIZAR (PRIMERO)
if (isset($_POST['actualizar'])) {

    $id_recordatorio= $_POST['id_recordatorio'];
    $nombre  = $_POST['nombre'];
    $monto = $_POST['monto'];
    $fecha_pago = $_POST['fecha_pago'];
    $descripcion = $_POST['descripcion'];
    $id_categoria = $_POST['id_categoria'];
    $id_estado = $_POST['id_estado'];

    Recordatorio::actualizar($id_usuario, $id_recordatorio, $nombre, $monto, $fecha_pago, $descripcion, $id_categoria, $id_estado);

    header("Location: ../views/recordatorios.php");
    exit();
}

// GUARDAR (SOLO SI NO ES ACTUALIZAR)
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['actualizar'])) {

    $nombre = $_POST['nombre'];
    $monto = $_POST['monto'];
    $fecha_pago = $_POST['fecha_pago'];
    $descripcion = $_POST['descripcion'];
    $id_categoria = $_POST['id_categoria'];
    $id_estado = $_POST['id_estado'];


    Recordatorio::guardar($nombre, $monto, $fecha_pago, $descripcion, $id_categoria, $id_usuario, $id_estado);

    header("Location: ../views/recordatorios.php");
    exit();
}
?>


