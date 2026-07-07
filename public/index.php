<?php
session_start();
?>

<!DOCTYPE html>
<!-- Proyecto: SmartBudget
     Página: Inicio de sesión 
 -->
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartBudget | Iniciar sesión</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="auth-page">

    <main class="container">

        <header class="auth-header">
            <h1 class="logo">
                <img src="../../public/img/logo.png" alt="Logotipo de SmartBudget" class="logo">
            </h1>
            <p class="subtitle">Organiza y controla tus finanzas personales</p>
        </header>

        <section class="auth-card" aria-labelledby="login-title">
            <h2 id="login-title">Iniciar sesión</h2>

            <!-- El "action" apunta al futuro controlador PHP de autenticación -->
            <form id="form-login" method="POST" action="../app/controllers/AuthController.php" novalidate>

                <div class="campo">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email" placeholder="ejemplo@correo.com" required>
                </div>

                <div class="campo">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="Escribe tu contraseña" required>
                </div>

                <p class="mensaje-error" id="mensaje-error" hidden>Correo o contraseña incorrectos.</p>

                <button type="submit" name="accion" value="login" class="btn-primario">Ingresar</button>
            </form>

            <div class="enlaces-auth">
                <a href="../app/views/recuperar.php">¿Olvidaste tu contraseña?</a>
                <a href="../app/views/registro.php">Crear una cuenta nueva</a>
            </div>
        </section>

    </main>

    <footer class="footer-auth">
        <p>&copy; 2026 SmartBudget - Grupo 4</p>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
