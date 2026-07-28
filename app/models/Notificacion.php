<?php
require_once "../../config/database.php";

class Notificacion {



    public static function actualizar($id_usuario, $id_notificacion, $titulo, $mensaje, $fecha_creacion, $leida, $enviada_correo, $id_tipo, $id_estado) {
        $db = Database::conectar();

        $sql = "UPDATE notificaciones
        SET titulo=?, mensaje=?, fecha_creacion=?, leida=?, enviada_correo=?, id_tipo=?, id_estado=?
        WHERE id_usuario=? AND id_notificacion=?";

        $stmt = $db->prepare($sql);
        $stmt->bind_param("sssiiiiii", $titulo, $mensaje, $fecha_creacion, $leida, $enviada_correo, $id_tipo, $id_estado, $id_usuario, $id_notificacion);
        
        return $stmt->execute();
    }




    public static function guardar($id_usuario, $titulo, $mensaje, $fecha_creacion, $leida, $enviada_correo, $id_tipo, $id_estado) {
        $db = Database::conectar();

        $sqlId = "SELECT COALESCE(MAX(id_notificacion), 0) + 1 AS siguiente_id
            FROM notificaciones
            WHERE id_usuario = ?";

        $stmtId = $db->prepare($sqlId);
        $stmtId->bind_param("i", $id_usuario);
        $stmtId->execute();

        $resultId = $stmtId->get_result();
        $row = $resultId->fetch_assoc();
        $id_notificacion = $row['siguiente_id'];

        $sql = "INSERT INTO notificaciones (id_usuario, id_notificacion, titulo, mensaje, fecha_creacion, leida, enviada_correo, id_tipo, id_estado)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $db->prepare($sql);
        $stmt->bind_param("iisssiiii", $id_usuario, $id_notificacion, $titulo, $mensaje, $fecha_creacion, $leida, $enviada_correo, $id_tipo, $id_estado);

        return $stmt->execute();
    }

    public static function obtenerPorUsuario($id_usuario) {
        $db = Database::conectar();

        $sql = "SELECT * FROM notificaciones WHERE id_usuario = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();

        return $stmt->get_result();
    }

    public static function eliminar($id_usuario, $id_notificacion) {
        $db = Database::conectar();

        $sql = "DELETE FROM notificaciones WHERE id_usuario = ? AND id_notificacion = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ii", $id_usuario, $id_notificacion);

        return $stmt->execute();
    }

    public static function cambiarEstado($id_usuario, $id_notificacion, $nuevo_estado) {
        $db = Database::conectar();
        $sql = "UPDATE notificaciones SET id_estado = ? WHERE id_usuario = ? AND id_notificacion = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("iii", $nuevo_estado, $id_usuario, $id_notificacion);

        return $stmt->execute();
    }
}