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

        $sql = "SELECT g.id_gasto, g.nombre, g.monto, g.fecha, g.descripcion, g.id_categoria, g.id_estado,
                       cg.nombre AS categoria
                FROM gastos g
                INNER JOIN categorias_gasto cg
                    ON cg.id_usuario = g.id_usuario
                   AND cg.id_categoria = g.id_categoria
                WHERE g.id_usuario = ?
                ORDER BY g.fecha DESC, g.id_gasto DESC";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();

        return $stmt->get_result();
    }

    public static function obtenerTotalesUltimosSeisMeses($id_usuario) {
        $db = Database::conectar();

        $sql = "SELECT
                    YEAR(fecha) AS anio,
                    MONTH(fecha) AS numero_mes,
                    DATE_FORMAT(fecha, '%Y-%m') AS mes_anio,
                    COALESCE(SUM(monto), 0) AS total
                FROM gastos
                WHERE id_usuario = ?
                  AND id_estado = 2
                  AND fecha >= DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 5 MONTH), '%Y-%m-01')
                  AND fecha < DATE_ADD(LAST_DAY(CURDATE()), INTERVAL 1 DAY)
                GROUP BY YEAR(fecha), MONTH(fecha), DATE_FORMAT(fecha, '%Y-%m')
                ORDER BY anio ASC, numero_mes ASC";

        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();

        return $stmt->get_result();
    }

    public static function obtenerTotalesPorCategoria($id_usuario) {
        $db = Database::conectar();

        $sql = "SELECT cg.nombre AS categoria,
                       COALESCE(SUM(g.monto), 0) AS total
                FROM categorias_gasto cg
                LEFT JOIN gastos g
                    ON g.id_usuario = cg.id_usuario
                   AND g.id_categoria = cg.id_categoria
                   AND g.id_estado = 2
                   AND YEAR(g.fecha) = YEAR(CURDATE())
                   AND MONTH(g.fecha) = MONTH(CURDATE())
                WHERE cg.id_usuario = ?
                GROUP BY cg.id_categoria, cg.nombre
                ORDER BY cg.nombre";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();

        return $stmt->get_result();
    }

    public static function obtenerTotalMesActual($id_usuario) {
        $db = Database::conectar();
        $sql = "SELECT COALESCE(SUM(monto), 0) AS total
                FROM gastos
                WHERE id_usuario = ?
                  AND id_estado = 2
                  AND YEAR(fecha) = YEAR(CURDATE())
                  AND MONTH(fecha) = MONTH(CURDATE())";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();

        return (float) $stmt->get_result()->fetch_assoc()['total'];
    }

    public static function obtenerPorPeriodo($id_usuario, $fechaInicio, $fechaFin, $id_categoria = null) {
        $db = Database::conectar();
        $sql = "SELECT g.fecha, g.nombre, g.descripcion, g.monto, cg.nombre AS categoria
                FROM gastos g
                INNER JOIN categorias_gasto cg
                    ON cg.id_usuario = g.id_usuario AND cg.id_categoria = g.id_categoria
                WHERE g.id_usuario = ? AND g.id_estado = 2
                  AND g.fecha BETWEEN ? AND ?";
        if ($id_categoria !== null) {
            $sql .= " AND g.id_categoria = ?";
        }
        $sql .= " ORDER BY g.fecha DESC, g.id_gasto DESC";
        $stmt = $db->prepare($sql);
        if ($id_categoria !== null) {
            $stmt->bind_param("issi", $id_usuario, $fechaInicio, $fechaFin, $id_categoria);
        } else {
            $stmt->bind_param("iss", $id_usuario, $fechaInicio, $fechaFin);
        }
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
