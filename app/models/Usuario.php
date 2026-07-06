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
}
