<?php
require_once "../../config/database.php";

class Ingreso {


    public static function actualizar($id_ingreso, $nombre, $monto, $fecha, $descripcion, $id_categoria, $id_estado) {
        $db = Database::conectar();

        $sql = "UPDATE ingresos 
        SET nombre=?, monto=?, fecha=?, descripcion=?, id_categoria=?, id_estado=?
        WHERE id_ingreso=?";

        $stmt = $db->prepare($sql);
        $stmt->bind_param("sdssiii", $nombre, $monto, $fecha, $descripcion, $id_categoria, $id_estado, $id_ingreso);

        return $stmt->execute();
    }



    public static function guardar($nombre, $monto, $fecha, $descripcion, $id_categoria, $id_usuario, $id_estado) {
        $db = Database::conectar();

        $sql = "INSERT INTO ingresos (nombre, monto, fecha, descripcion, id_categoria, id_usuario, id_estado)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $db->prepare($sql);
        $stmt->bind_param("sdssiii", $nombre, $monto, $fecha, $descripcion, $id_categoria, $id_usuario, $id_estado);

        return $stmt->execute();
    }

    public static function obtenerPorUsuario($id_usuario) {
        $db = Database::conectar();

        $sql = "SELECT * FROM ingresos WHERE id_usuario = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();

        return $stmt->get_result();
    }

    public static function eliminar($id_ingreso) {
        $db = Database::conectar();

        $sql = "DELETE FROM ingresos WHERE id_ingreso = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id_ingreso);
        return $stmt->execute();
    }
}