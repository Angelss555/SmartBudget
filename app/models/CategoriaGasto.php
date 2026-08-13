<?php
require_once "../../config/database.php";

class CategoriaGasto {

    public static function obtenerTodos() {
        $db = Database::conectar();
        $sql = "SELECT *
                 FROM categorias_gasto
                 WHERE id_estado = 2
                 ORDER BY nombre";
        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $stmt->get_result();
    }
}