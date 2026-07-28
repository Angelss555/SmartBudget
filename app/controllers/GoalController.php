<?php
session_start();
require_once "../models/Meta.php";
require_once "../../config/database.php";

if (!isset($_SESSION['usuario'])) {
    header("Location: ../../public/index.php");
    exit();
}

$id_usuario = $_SESSION['usuario']['id_usuario'];


// ELIMINAR
if (isset($_GET['delete'])) {

    Meta::eliminar($id_usuario, $_GET['delete']);

    header("Location: ../views/metas.php");
    exit(); 
}

// ACTUALIZAR (PRIMERO)
if (isset($_POST['actualizar'])) {

    $id_meta= $_POST['id_meta'];
    $nombre  = $_POST['nombre'];
    $monto_actual = $_POST['monto_actual'];
    $monto_objetivo = $_POST['monto_objetivo'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_cumplimiento = $_POST['fecha_cumplimiento'];
    $descripcion = $_POST['descripcion'];
    $id_estado = $_POST['id_estado'];

    Meta::actualizar($id_usuario, $id_meta, $nombre, $monto_actual, $monto_objetivo, $fecha_inicio, $fecha_cumplimiento, $descripcion, $id_estado);

    header("Location: ../views/metas.php");
    exit();
}

// GUARDAR (SOLO SI NO ES ACTUALIZAR)
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['actualizar'])) {

    $nombre = $_POST['nombre'];
    $monto_actual = $_POST['monto_actual'];
    $monto_objetivo = $_POST['monto_objetivo'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_cumplimiento = $_POST['fecha_cumplimiento'];
    $descripcion = $_POST['descripcion'];
    $id_estado = $_POST['id_estado'];



    Meta::guardar($nombre, $monto_actual, $monto_objetivo, $fecha_inicio, $fecha_cumplimiento, $descripcion, $id_usuario, $id_estado);

    header("Location: ../views/metas.php");
    exit();
}
?>


