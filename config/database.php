<?php
class Database {
    public static function conectar(){
        $conexion = new mysqli('localhost', 'root', '', 'smart_budget');

        if($conexion->connect_error){
            die("Error de conexión: " . $conexion->connect_error);
        }

        return $conexion;
    } 
} 