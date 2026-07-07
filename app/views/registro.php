<!DOCTYPE html>
<!--
  Proyecto: SmartBudget
  Página: Registro de nuevos usuarios
  Corresponde a la función 1, Registro de Usuarios
-->
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartBudget | Crear cuenta</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body class="auth-page">

    <main class="container">

        <header class="auth-header">
            <h1 class="logo">
                <img src="../../public/img/logo.png" alt="Logotipo de SmartBudget" class="logo">
            </h1>
            <p class="subtitle">Crea tu cuenta para empezar a organizar tus finanzas</p>
        </header>

        <section class="auth-card" aria-labelledby="registro-title">
            <h2 id="registro-title">Registro de usuario</h2>

            <form id="form-registro" method="POST" action="../controllers/AuthController.php" novalidate>

                <div class="campo">
                    <label for="usuario">Nombre de usuario</label>
                    <input type="text" id="usuario" name="usuario" placeholder="Nombre de usuario único" required>
                </div>

                <div class="campo">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Primer nombre" required>
                </div>

                <div class="campo">
                    <label for="primer_apellido">Primer apellido</label>
                    <input type="text" id="primer_apellido" name="primer_apellido" placeholder="Primer apellido" required>
                </div>
                <div class="campo">
                    <label for="segundo_apellido">Segundo apellido</label>
                    <input type="text" id="segundo_apellido" name="segundo_apellido" placeholder="Segundo apellido" required>
                </div>

                <div class="campo">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email" placeholder="ejemplo@correo.com" required>
                </div>

                <div class="campo">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="Crea una contraseña" required minlength="8">
                </div>

                <div class="campo">
                    <label for="password2">Confirmar contraseña</label>
                    <input type="password" id="password2" name="password2" placeholder="Repite tu contraseña" required minlength="8">
                </div>

                <p class="mensaje-error" id="mensaje-error" hidden>Las contraseñas no coinciden.</p>

                <button type="submit" name="accion" value="registro" class="btn-primario">Registrarme</button>
            </form>

            <div class="enlaces-auth">
                <a href="../../public/index.php">Ya tengo una cuenta, iniciar sesión</a>
            </div>
        </section>

    </main>

    <footer class="footer-auth">
        <p>&copy; 2026 SmartBudget - Grupo 4
    </footer>

    <script src="../../public/js/script.js"></script>
</body>
</html>
