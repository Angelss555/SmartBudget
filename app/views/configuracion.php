<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../../public/index.php");
    exit();
}
?>

<!DOCTYPE html>
<!--
  Proyecto: SmartBudget
  Página: Configuración de cuenta y notificaciones
  Corresponde a la función 10, Notificaciones por correo
-->
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartBudget | Configuración</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>

    <header class="topbar">
        <div class="marca">  
            <img src="../../public/img/icon.png" alt="Icono de Smartbudget" class="icon-small">
            <span>SmartBudget</span>
        </div>

        <nav class="menu-principal" aria-label="Navegación principal">
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="ingresos.php">Ingresos</a></li>
                <li><a href="gastos.php">Gastos</a></li>
                <li><a href="reportes.php">Reportes</a></li>
                <li><a href="metas.php">Metas de ahorro</a></li>
                <li><a href="configuracion.php" class="activo">Configuración</a></li>
            </ul>
        </nav>

        <div class="usuario-sesion">
            <span id="nombre-usuario">Hola, <?php echo htmlspecialchars($_SESSION['usuario']['nombre']); ?></span>
            <a href="../../public/index.php" class="btn-salir">Cerrar sesión</a>
        </div>
    </header>

    <main class="contenido">

        <h1>Configuración</h1>

        <section class="tarjeta-formulario">
            <h2>Notificaciones</h2>

            <form id="form-notificaciones" method="POST" action="../controllers/ConfigurationController.php">
                <input type="hidden" name="guardar_configuracion" value="1">

                <div class="campo campo-switch">
                    <label for="reporte-app">Reporte financiero mensual</label>
                    <div>
                        <label><input type="checkbox" id="reporte-app" name="reporte_app" value="1" checked> App</label>
                        <label><input type="checkbox" id="reporte-correo" name="reporte_correo" value="1" checked> Correo</label>
                    </div>
                </div>

                <div class="campo campo-switch">
                    <label for="pagos-app">Alertas de próximos pagos</label>
                    <div>
                        <label><input type="checkbox" id="pagos-app" name="pagos_app" value="1" checked> App</label>
                        <label><input type="checkbox" id="pagos-correo" name="pagos_correo" value="1" checked> Correo</label>
                    </div>
                </div>

                <div class="campo campo-switch">
                    <label for="exceso-app">Aviso al sobrepasar el monto máximo de una categoría</label>
                    <div>
                        <label><input type="checkbox" id="exceso-app" name="exceso_app" value="1" checked> App</label>
                        <label><input type="checkbox" id="exceso-correo" name="exceso_correo" value="1" checked> Correo</label>
                    </div>
                </div>

                <button type="submit" class="btn-primario">Guardar cambios</button>
            </form>
        </section>

        <section class="tarjeta-formulario">
            <h2>Datos de la cuenta</h2>

            <form id="form-perfil" method="POST" action="../controllers/ConfigurationController.php">
                <input type="hidden" name="guardar_perfil" value="1">

                <div class="campo">
                    <label for="nombre-perfil">Nombre completo</label>
                    <input type="text" id="nombre-perfil" name="nombre" value="<?php echo htmlspecialchars($_SESSION['usuario']['nombre'] ?? ''); ?>">
                </div>

                <div class="campo">
                    <label for="email-perfil">Correo electrónico</label>
                    <input type="email" id="email-perfil" name="email" value="<?php echo htmlspecialchars($_SESSION['usuario']['email'] ?? ''); ?>">
                </div>

                <button type="submit" class="btn-secundario">Actualizar datos</button>
            </form>
        </section>

    </main>

    <footer class="footer-app">
        <p>&copy; 2026 SmartBudget - Grupo 4</p>
    </footer>

    <script src="../../public/js/script.js"></script>
</body>
</html>
