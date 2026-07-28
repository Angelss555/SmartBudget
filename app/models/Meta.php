<?php
require_once "../../config/database.php";

class Meta {


    public static function actualizar($id_usuario, $id_meta, $nombre, $monto_actual, $monto_objetivo, $fecha_inicio, $fecha_cumplimiento, $descripcion, $id_estado) {
        $db = Database::conectar();


        $sql = "UPDATE metas_ahorro
        SET nombre=?, monto_actual=?, monto_objetivo=?, fecha_inicio=?, fecha_cumplimiento=?, descripcion=?, id_estado=?
        WHERE id_usuario=? AND id_meta=?";

        $stmt = $db->prepare($sql);
        $stmt->bind_param("sddsssiii", $nombre, $monto_actual, $monto_objetivo, $fecha_inicio, $fecha_cumplimiento, $descripcion, $id_estado, $id_usuario, $id_meta);

        return $stmt->execute();
    }

    public static function guardar($nombre, $monto_actual, $monto_objetivo, $fecha_inicio, $fecha_cumplimiento, $descripcion, $id_usuario,  $id_estado) {
        $db = Database::conectar();

        $db->begin_transaction();

        try {
            $sqlId = "SELECT COALESCE(MAX(id_meta), 0) + 1 AS siguiente_id
                      FROM metas_ahorro
                      WHERE id_usuario = ?
                      FOR UPDATE";
            $stmtId = $db->prepare($sqlId);
            $stmtId->bind_param("i", $id_usuario);
            $stmtId->execute();
            $id_meta = $stmtId->get_result()->fetch_assoc()['siguiente_id'];

            $sql = "INSERT INTO metas_ahorro
                    (id_usuario, id_meta, nombre, monto_actual, monto_objetivo, fecha_inicio, fecha_cumplimiento, descripcion, id_estado)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $db->prepare($sql);
            $stmt->bind_param("iisddsssi", $id_usuario, $id_meta, $nombre, $monto_actual, $monto_objetivo, $fecha_inicio, $fecha_cumplimiento, $descripcion, $id_estado);
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

        $sql = "SELECT * FROM metas_ahorro WHERE id_usuario = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();

        return $stmt->get_result();
    }

    public static function eliminar($id_usuario, $id_meta) {
        $db = Database::conectar();

        $sql = "DELETE FROM metas_ahorro
                WHERE id_usuario = ? AND id_meta = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ii", $id_usuario, $id_meta);
        return $stmt->execute();
    }

    public static function cambiarEstado($id_usuario, $id_meta, $nuevo_estado) {
        $db = Database::conectar();
        $sql = "UPDATE metas_ahorro
                SET id_estado = ?
                WHERE id_usuario = ? AND id_meta = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("iii", $nuevo_estado, $id_usuario, $id_meta);

        return $stmt->execute();
    }
}
