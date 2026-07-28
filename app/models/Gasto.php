<?php
require_once "../../config/database.php";

class Gasto {


    public static function actualizar($id_usuario, $id_gasto, $nombre, $monto, $fecha, $descripcion, $id_categoria, $id_estado) {
        $db = Database::conectar();

        $sql = "UPDATE gastos 
        SET nombre=?, monto=?, fecha=?, descripcion=?, id_categoria=?, id_estado=?
        WHERE id_usuario=?
        AND id_gasto=?";

        $stmt = $db->prepare($sql);
        $stmt->bind_param("sdssiiii", $nombre, $monto, $fecha, $descripcion, $id_categoria, $id_estado, $id_usuario, $id_gasto);

        return $stmt->execute();
    }



    public static function guardar($nombre, $monto, $fecha, $descripcion, $id_categoria, $id_usuario, $id_estado) {
        $db = Database::conectar();

        $sqlId = "SELECT COALESCE(MAX(id_gasto), 0) + 1 AS siguiente_id
            FROM gastos
            WHERE id_usuario = ?";

        $stmtId = $db->prepare($sqlId);
        $stmtId->bind_param("i", $id_usuario);
        $stmtId->execute();

        $resultId = $stmtId->get_result();
        $row = $resultId->fetch_assoc();
        $id_gasto = $row['siguiente_id'];

        $sql = "INSERT INTO gastos (id_usuario, id_gasto, nombre, monto, fecha, descripcion, id_categoria, id_estado)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $db->prepare($sql);
        $stmt->bind_param("iisdssii", $id_usuario, $id_gasto, $nombre, $monto, $fecha, $descripcion, $id_categoria, $id_estado);

        return $stmt->execute();
    }

    public static function obtenerPorUsuario($id_usuario) {
        $db = Database::conectar();

        $sql = "SELECT * FROM gastos WHERE id_usuario = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();

        return $stmt->get_result();
    }

    public static function eliminar($id_usuario, $id_gasto) {
        $db = Database::conectar();

        $sql = "DELETE FROM gastos WHERE id_usuario = ? AND id_gasto = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ii", $id_usuario, $id_gasto);
        return $stmt->execute();
    }

    public static function cambiarEstado($id_usuario, $id_gasto, $nuevo_estado) {
        $db = Database::conectar();
        $sql = "UPDATE gastos SET id_estado = ? WHERE id_usuario = ? AND id_gasto = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("iii", $nuevo_estado, $id_usuario, $id_gasto);

        return $stmt->execute();
    }
}