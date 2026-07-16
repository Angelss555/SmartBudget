<?php
require_once "../../config/database.php";

class Recordatorio {


    public static function actualizar($id_recordatorio, $nombre, $monto, $fecha_pago, $descripcion, $id_categoria, $id_estado) {
        $db = Database::conectar();

        $sql = "UPDATE recordatorios_pago 
        SET nombre=?, monto=?, fecha_pago=?, descripcion=?, id_categoria=?, id_estado=?
        WHERE id_recordatorio=?";

        $stmt = $db->prepare($sql);
        $stmt->bind_param("sdssiii", $nombre, $monto, $fecha_pago, $descripcion, $id_categoria, $id_estado, $id_recordatorio);

        return $stmt->execute();
    }



    public static function guardar($nombre, $monto, $fecha_pago, $descripcion, $id_categoria, $id_usuario, $id_estado) {
        $db = Database::conectar();

        $sql = "INSERT INTO recordatorios_pago (nombre, monto, fecha_pago, descripcion, id_categoria, id_usuario, id_estado)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $db->prepare($sql);
        $stmt->bind_param("sdssiii", $nombre, $monto, $fecha_pago, $descripcion, $id_categoria, $id_usuario, $id_estado);

        return $stmt->execute();
    }

    public static function obtenerPorUsuario($id_usuario) {
        $db = Database::conectar();

        $sql = "SELECT * FROM recordatorios_pago WHERE id_usuario = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();

        return $stmt->get_result();
    }

    public static function eliminar($id_recordatorio) {
        $db = Database::conectar();

        $sql = "DELETE FROM recordatorios_pago WHERE id_recordatorio = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id_recordatorio);
        return $stmt->execute();
    }
}