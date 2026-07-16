<?php
require_once "../../config/database.php";

class Gasto {


    public static function actualizar($id_gasto, $nombre, $monto, $fecha, $descripcion, $id_categoria, $id_estado) {
        $db = Database::conectar();

        $sql = "UPDATE gastos 
        SET nombre=?, monto=?, fecha=?, descripcion=?, id_categoria=?, id_estado=?
        WHERE id_gasto=?";

        $stmt = $db->prepare($sql);
        $stmt->bind_param("sdssiii", $nombre, $monto, $fecha, $descripcion, $id_categoria, $id_estado, $id_gasto);

        return $stmt->execute();
    }



    public static function guardar($nombre, $monto, $fecha, $descripcion, $id_categoria, $id_usuario, $id_estado) {
        $db = Database::conectar();

        $sql = "INSERT INTO gastos (nombre, monto, fecha, descripcion, id_categoria, id_usuario, id_estado)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $db->prepare($sql);
        $stmt->bind_param("sdssiii", $nombre, $monto, $fecha, $descripcion, $id_categoria, $id_usuario, $id_estado);

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

    public static function eliminar($id_gasto) {
        $db = Database::conectar();

        $sql = "DELETE FROM gastos WHERE id_gasto = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id_gasto);
        return $stmt->execute();
    }
}