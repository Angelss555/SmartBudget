<?php
require_once "../../config/database.php";

class CategoriaIngreso {

    public static function guardar($nombre, $id_usuario, $id_estado) {
        $db = Database::conectar();
        $sql = "INSERT INTO categorias_ingreso (nombre, id_usuario, id_estado)
                VALUES (?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("sii", $nombre, $id_usuario, $id_estado);

        return $stmt->execute();
    }

    public static function actualizar($id_categoria, $id_usuario, $nombre, $id_estado){
        $db = Database::conectar();
        $sql = "UPDATE categorias_ingreso
                SET nombre = ?, id_estado = ?
                WHERE id_categoria = ? AND id_usuario = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("siii", $nombre, $id_estado, $id_categoria, $id_usuario);

        return $stmt->execute();
    }

    public static function obtenerTodos() {
        $db = Database::conectar();
        $sql = "SELECT * FROM categorias_ingreso";
        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $stmt->get_result();
    }

    public static function obtenerPorUsuario($id_usuario) {
        $db = Database::conectar();
        $sql = "SELECT * FROM categorias_ingreso
                WHERE id_usuario = ? OR id_usuario IS NULL
                ORDER BY nombre";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();

        return $stmt->get_result();
    }

    public static function obtenerPorEstado($id_usuario, $id_estado) {
        $db = Database::conectar();
        $sql = "SELECT * FROM categorias_ingreso
                WHERE id_estado = ?
                  AND (id_usuario = ? OR id_usuario IS NULL)
                ORDER BY nombre";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ii", $id_estado, $id_usuario);
        $stmt->execute();

        return $stmt->get_result();
    }

    public static function eliminar($id_categoria, $id_usuario) {
        $db = Database::conectar();
        $sql = "DELETE FROM categorias_ingreso WHERE id_categoria = ? AND id_usuario = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ii", $id_categoria, $id_usuario);

        return $stmt->execute();
    }

    public static function cambiarEstado($id_categoria, $id_usuario, $nuevo_estado) {
        $db = Database::conectar();
        $sql = "UPDATE categorias_ingreso SET id_estado = ? WHERE id_categoria = ? AND id_usuario = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("iii", $nuevo_estado, $id_categoria, $id_usuario);

        return $stmt->execute();
    }
}