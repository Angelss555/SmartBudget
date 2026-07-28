<?php
require_once "../../config/database.php";

class Recordatorio {


    public static function actualizar($id_usuario, $id_recordatorio, $nombre, $monto, $fecha_pago, $descripcion, $id_categoria, $id_estado) {
        $db = Database::conectar();

        $sql = "UPDATE recordatorios_pago 
        SET nombre=?, monto=?, fecha_pago=?, descripcion=?, id_categoria=?, id_estado=?
        WHERE id_usuario=? AND id_recordatorio=?";

        $stmt = $db->prepare($sql);
        $stmt->bind_param("sdssiiii", $nombre, $monto, $fecha_pago, $descripcion, $id_categoria, $id_estado, $id_usuario, $id_recordatorio);

        return $stmt->execute();
    }



    public static function guardar($nombre, $monto, $fecha_pago, $descripcion, $id_categoria, $id_usuario, $id_estado) {
        $db = Database::conectar();

        $db->begin_transaction();

        try {
            $sqlId = "SELECT COALESCE(MAX(id_recordatorio), 0) + 1 AS siguiente_id
                      FROM recordatorios_pago
                      WHERE id_usuario = ?
                      FOR UPDATE";
            $stmtId = $db->prepare($sqlId);
            $stmtId->bind_param("i", $id_usuario);
            $stmtId->execute();
            $id_recordatorio = $stmtId->get_result()->fetch_assoc()['siguiente_id'];

            $sql = "INSERT INTO recordatorios_pago
                    (id_usuario, id_recordatorio, nombre, monto, fecha_pago, descripcion, id_categoria, id_estado)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $db->prepare($sql);
            $stmt->bind_param("iisdssii", $id_usuario, $id_recordatorio, $nombre, $monto, $fecha_pago, $descripcion, $id_categoria, $id_estado);
            $resultado = $stmt->execute();

            $db->commit();
            return $resultado;
        } catch (Throwable $e) {
            $db->rollback();
            throw $e;
        }
    }

    public static function obtenerPorUsuario($id_usuario) {
        $db = Database::conectar();

        $sql = "SELECT * FROM recordatorios_pago WHERE id_usuario = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();

        return $stmt->get_result();
    }

    public static function eliminar($id_usuario, $id_recordatorio) {
        $db = Database::conectar();

        $sql = "DELETE FROM recordatorios_pago
                WHERE id_usuario = ? AND id_recordatorio = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ii", $id_usuario, $id_recordatorio);
        return $stmt->execute();
    }

    public static function cambiarEstado($id_usuario, $id_recordatorio, $nuevo_estado) {
        $db = Database::conectar();
        $sql = "UPDATE recordatorios_pago
                SET id_estado = ?
                WHERE id_usuario = ? AND id_recordatorio = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("iii", $nuevo_estado, $id_usuario, $id_recordatorio);

        return $stmt->execute();
    }
}
