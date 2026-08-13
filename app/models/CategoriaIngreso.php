<?php
require_once "../../config/database.php";

class CategoriaIngreso {

    public static function obtenerTodos() {
        $db = Database::conectar();
        $sql = "SELECT *
                 FROM categorias_ingreso
                 WHERE id_estado = 2
                 ORDER BY nombre";
        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $stmt->get_result();
    }
}