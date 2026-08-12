<?php
require_once "../../config/database.php";

class ConfiguracionNotificaciones {

    public static function guardar( $notificacion_app, $notificacion_correo, $id_usuario, $id_tipo, $id_estado) {
        $db = Database::conectar();
        $sql = "INSERT INTO configuraciones_notificaciones(notificacion_app, notificacion_correo, id_usuario, id_tipo, id_estado)
                VALUES ( ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("iiiii", $notificacion_app, $notificacion_correo, $id_usuario, $id_tipo, $id_estado);
        /*Notificacion app y correo son booleanos, por lo que se pasan como enteros (0 o 1)*/
        return $stmt->execute();
    }

    public static function actualizar($id_usuario, $id_tipo, $notificacion_app, $notificacion_correo, $id_estado) {
        $db = Database::conectar();
        $sql = "UPDATE configuraciones_notificaciones
                SET notificacion_app = ?, notificacion_correo = ?, id_estado = ?
                WHERE id_usuario = ? AND id_tipo = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("iiiii", $notificacion_app, $notificacion_correo, $id_estado, $id_usuario, $id_tipo);
        /*Notificacion app y correo son booleanos, por lo que se pasan como enteros (0 o 1)*/

        return $stmt->execute();
    }

    public static function obtenerTodos() {
        $db = Database::conectar();
        $sql = "SELECT * FROM configuraciones_notificaciones";
        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $stmt->get_result();
    }

    public static function obtenerPorEstado($id_estado) {
        $db = Database::conectar();
        $sql = "SELECT * FROM configuraciones_notificaciones WHERE id_estado = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id_estado);
        $stmt->execute();

        return $stmt->get_result();
    }

    public static function obtenerPorUsuario($id_usuario) {
        $db = Database::conectar();
        $sql = "SELECT * FROM configuraciones_notificaciones WHERE id_usuario = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();

        return $stmt->get_result();
    }

    public static function obtenerPorTipo($id_tipo) {
        $db = Database::conectar();
        $sql = "SELECT * FROM configuraciones_notificaciones WHERE id_tipo = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id_tipo);
        $stmt->execute();

        return $stmt->get_result();
    }



    public static function eliminar($id_usuario, $id_tipo) {
        $db = Database::conectar();
        $sql = "DELETE FROM configuraciones_notificaciones
                WHERE id_usuario = ? AND id_tipo = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ii", $id_usuario, $id_tipo);

        return $stmt->execute();
    }

    public static function cambiarEstado($id_usuario, $id_tipo, $nuevo_estado) {
        $db = Database::conectar();
        $sql = "UPDATE configuraciones_notificaciones
                SET id_estado = ?
                WHERE id_usuario = ? AND id_tipo = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("iii", $nuevo_estado, $id_usuario, $id_tipo);

        return $stmt->execute();
    }

    public static function guardarOActualizar($id_usuario, $id_tipo, $notificacion_app, $notificacion_correo, $id_estado = 2) {
        $db = Database::conectar();
        $sql = "INSERT INTO configuraciones_notificaciones(id_usuario, id_tipo, notificacion_app, notificacion_correo, id_estado)
                VALUES (?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                    notificacion_app = VALUES(notificacion_app),
                    notificacion_correo = VALUES(notificacion_correo),
                    id_estado = VALUES(id_estado)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("iiiii", $id_usuario, $id_tipo, $notificacion_app, $notificacion_correo, $id_estado);

        return $stmt->execute();
    }
}
