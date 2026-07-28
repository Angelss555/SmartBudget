<?php
require_once "../../config/database.php";

class TipoNotificacion {

    public static function guardar($nombre, $id_estado) {
        $db = Database::conectar();
        $sql = "INSERT INTO tipos_notificacion (nombre, id_estado)
                VALUES (?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("si", $nombre, $id_estado);

        return $stmt->execute();
    }

    public static function actualizar($id_tipo, $nombre, $id_estado){
        $db = Database::conectar();
        $sql = "UPDATE tipos_notificacion
                SET nombre = ?, id_estado = ?
                WHERE id_tipo = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("sii", $nombre, $id_estado, $id_tipo);

        return $stmt->execute();
    }

    public static function obtenerTodos() {
        $db = Database::conectar();
        $sql = "SELECT * FROM tipos_notificacion";
        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $stmt->get_result();
    }

    public static function obtenerPorEstado($id_estado) {
        $db = Database::conectar();
        $sql = "SELECT * FROM tipos_notificacion WHERE id_estado = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id_estado);
        $stmt->execute();

        return $stmt->get_result();
    }

    public static function eliminar($id_tipo) {
        $db = Database::conectar();
        $sql = "DELETE FROM tipos_notificacion WHERE id_tipo = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id_tipo);

        return $stmt->execute();
    }

    public static function cambiarEstado($id_tipo, $nuevo_estado) {
        $db = Database::conectar();
        $sql = "UPDATE tipos_notificacion SET id_estado = ? WHERE id_tipo = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ii", $nuevo_estado, $id_tipo);

        return $stmt->execute();
    }
}