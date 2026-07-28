<?php
require_once "../../config/database.php";

class Ingreso {



    public static function actualizar($id_usuario, $id_ingreso, $nombre, $monto, $fecha, $descripcion, $id_categoria, $id_estado) {
        $db = Database::conectar();

        $sql = "UPDATE ingresos 
                SET nombre=?, monto=?, fecha=?, descripcion=?, id_categoria=?, id_estado=?
                WHERE id_usuario=?
                AND id_ingreso=?";

        $stmt = $db->prepare($sql);
        $stmt->bind_param("sdssiiii", $nombre, $monto, $fecha, $descripcion, $id_categoria, $id_estado, $id_usuario, $id_ingreso);

        return $stmt->execute();
    }



    public static function guardar($nombre, $monto, $fecha, $descripcion, $id_categoria, $id_usuario, $id_estado) {
        $db = Database::conectar();


        $sqlId= "SELECT COALESCE(MAX(id_ingreso), 0) + 1 AS siguiente_id
                FROM ingresos
                WHERE id_usuario = ?";

        $stmtId = $db->prepare($sqlId);
        $stmtId->bind_param("i", $id_usuario);
        $stmtId->execute();

        $resultId = $stmtId->get_result();
        $row = $resultId->fetch_assoc(); /*Extrae la fila del resultado como un array asociativo*/
        $id_ingreso = $row['siguiente_id'];


        $sql = "INSERT INTO ingresos (id_usuario, id_ingreso, nombre, monto, fecha, descripcion, id_categoria, id_estado)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $db->prepare($sql);
        $stmt->bind_param("iisdssii", $id_usuario, $id_ingreso, $nombre, $monto, $fecha, $descripcion, $id_categoria, $id_estado);

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

    public static function eliminar($id_usuario, $id_ingreso) {
        $db = Database::conectar();

        $sql = "DELETE FROM ingresos WHERE id_usuario = ? AND id_ingreso = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ii", $id_usuario, $id_ingreso);
        return $stmt->execute();
    }

    public static function cambiarEstado($id_usuario, $id_ingreso, $nuevo_estado) {
        $db = Database::conectar();
        $sql = "UPDATE ingresos SET id_estado = ? WHERE id_usuario = ? AND id_ingreso = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("iii", $nuevo_estado, $id_usuario, $id_ingreso);

        return $stmt->execute();
    }
}