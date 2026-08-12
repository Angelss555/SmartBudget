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

        $sql = "SELECT
                    i.id_ingreso,
                    i.nombre,
                    i.monto,
                    i.fecha,
                    i.descripcion,
                    i.id_categoria,
                    i.id_estado,
                    ci.nombre AS categoria
                FROM ingresos AS i
                INNER JOIN categorias_ingreso AS ci ON i.id_usuario = ci.id_usuario AND i.id_categoria = ci.id_categoria
                WHERE i.id_usuario = ?
                ORDER BY i.fecha DESC, i.id_ingreso DESC";
        
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
                FROM ingresos
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

    public static function obtenerTotalMesActual($id_usuario) {
        $db = Database::conectar();
        $sql = "SELECT COALESCE(SUM(monto), 0) AS total
                FROM ingresos
                WHERE id_usuario = ?
                  AND id_estado = 2
                  AND YEAR(fecha) = YEAR(CURDATE())
                  AND MONTH(fecha) = MONTH(CURDATE())";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();

        return (float) $stmt->get_result()->fetch_assoc()['total'];
    }

    public static function obtenerPorPeriodo($id_usuario, $fechaInicio, $fechaFin) {
        $db = Database::conectar();
        $sql = "SELECT i.fecha, i.nombre, i.descripcion, i.monto, ci.nombre AS categoria
                FROM ingresos i
                INNER JOIN categorias_ingreso ci
                    ON ci.id_usuario = i.id_usuario AND ci.id_categoria = i.id_categoria
                WHERE i.id_usuario = ? AND i.id_estado = 2
                  AND i.fecha BETWEEN ? AND ?
                ORDER BY i.fecha DESC, i.id_ingreso DESC";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("iss", $id_usuario, $fechaInicio, $fechaFin);
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
