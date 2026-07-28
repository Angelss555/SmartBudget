<?php
require_once "../../config/database.php";

class Usuario {

    public static function registrar($nombre, $primer_apellido, $segundo_apellido, $email, $password) {
        $db = Database::conectar();
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (nombre, primer_apellido, segundo_apellido, email, password, id_estado) VALUES (?, ?, ?, ?, ?, 2)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("sssss", $nombre, $primer_apellido, $segundo_apellido, $email, $hash);

        return $stmt->execute();
    }

    public static function login($email, $password) {
        $db = Database::conectar();

        $stmt = $db->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $usuario = $stmt->get_result()->fetch_assoc();

        if ($usuario && password_verify($password, $usuario['password'])) {
            return $usuario;
        }

        return false;
    }

    public static function obtenerTodos() {
        $db = Database::conectar();

        $sql = "SELECT * FROM usuarios";
        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $stmt->get_result();
    }

    public static function obtenerPorEstado($id_estado) {
        $db = Database::conectar();

        $sql = "SELECT * FROM usuarios WHERE id_estado = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id_estado);
        $stmt->execute();

        return $stmt->get_result();
    }

    public static function actualizar($id_usuario, $nombre, $primer_apellido, $segundo_apellido, $email, $password, $id_estado) {
        $db = Database::conectar();
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "UPDATE usuarios 
                SET nombre=?, primer_apellido=?, segundo_apellido=?, email=?, password=?, id_estado=?
                WHERE id_usuario=?";

        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssssssi", $nombre, $primer_apellido, $segundo_apellido, $email, $hash, $id_estado, $id_usuario);

        return $stmt->execute();
    }

    public static function cambiarEstado($id_usuario, $id_estado) {
        $db = Database::conectar();

        $sql = "UPDATE usuarios SET id_estado = ? WHERE id_usuario = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ii", $id_estado, $id_usuario);

        return $stmt->execute();
    }
}
