<?php
session_start();
require_once "../models/Usuario.php";

// REGISTRO
if ($_POST['accion'] == "registro") {

    $emailSinEspacios = trim($_POST['email']);

    if (Usuario::correoExiste($emailSinEspacios)) {
        header("Location: ../views/registro.php?error=correo_existe");
        exit();
    }

    $registrado = Usuario::registrar($_POST['nombre'], $_POST['primer_apellido'], $_POST['segundo_apellido'], $_POST['email'], $_POST['password1']);

    if ($registrado) {
        $usuario = Usuario::login($_POST['email'], $_POST['password1']);
        $_SESSION['usuario'] = $usuario;
        
        header("Location: ../views/dashboard.php?registro=ok");
        exit(); 
    } else {
        header("Location: ../../public/index.php?error=registro");
        exit(); 
    }
}

// LOGIN
if ($_POST['accion'] == "login") {

    $user = Usuario::login($_POST['email'], $_POST['password']);

    if ($user) {
        $_SESSION['usuario'] = $user; 

    header("Location: ../views/dashboard.php?login=ok");

        exit(); 
    } else {
        header("Location: ../../public/index.php?error=login");

        exit();

    }
}

// LOGOUT
if (isset($_GET['logout'])) {

    session_unset(); 
    session_destroy(); 

    header("Location: ../../public/index.php");
    exit();
}