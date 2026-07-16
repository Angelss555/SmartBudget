<?php
require_once "../../config/database.php";

class CategoriaIngreso {

    public static function guardar($nombre, $id_estado) {
        $db = Database::conectar();
        $sql = "INSERT INTO categorias_ingreso (nombre, id_estado)
                VALUES (?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("si", $nombre, $id_estado);

        return $stmt->execute();
    }

    public static function actualizar($id_categoria, $nombre, $id_estado){
        $db = Database::conectar();
        $sql = "UPDATE categorias_ingreso
                SET nombre = ?, id_estado = ?
                WHERE id_categoria = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("sii", $nombre, $id_estado, $id_categoria);

        return $stmt->execute();
    }

    public static function obtener() {
        $db = Database::conectar();
        $sql = "SELECT * FROM categorias_ingreso WHERE id_estado = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id_estado);

        return $stmt->get_result();
    }

    public static function eliminar($id_categoria) {
        $db = Database::conectar();
        $sql = "DELETE FROM categorias_ingreso WHERE id_categoria = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id_categoria);

        return $stmt->execute();
    }
}