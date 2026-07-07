<!DOCTYPE html>
<!--
  Proyecto: SmartBudget
  Página: Metas de ahorro
  Corresponde a la función 9Metas de ahorro
-->
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartBudget | Metas de ahorro</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>

    <header class="topbar">
        <div class="marca">  
            <img src="../../public/img/icon.png" alt="Icono de Smartbudget"  class="icon-small">
            <span>SmartBudget</span>
        </div>

        <nav class="menu-principal" aria-label="Navegación principal">
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="ingresos.php">Ingresos</a></li>
                <li><a href="gastos.php">Gastos</a></li>
                <li><a href="reportes.php">Reportes</a></li>
                <li><a href="metas.php" class="activo">Metas de ahorro</a></li>
                <li><a href="configuracion.php">Configuración</a></li>
            </ul>
        </nav>

        <div class="usuario-sesion">
            <span id="nombre-usuario">Hola, Usuario</span>
            <a href="../../public/index.php" class="btn-salir">Cerrar sesión</a>
        </div>
    </header>

    <main class="contenido">

        <h1>Metas de ahorro</h1>

        <section class="tarjeta-formulario">
            <h2>Crear nueva meta</h2>

            <!-- El "action" apunta al futuro controlador PHP de metas de ahorro -->
            <form id="form-meta" method="POST" action="controllers/MetaController.php" novalidate>

                <div class="campo">
                    <label for="nombre-meta">Nombre de la meta</label>
                    <input type="text" id="nombre-meta" name="nombre" placeholder="Ej. Viaje, Fondo de emergencia" required>
                </div>

                <div class="campo">
                    <label for="monto-objetivo">Monto objetivo</label>
                    <input type="number" id="monto-objetivo" name="monto_objetivo" min="0" step="0.01" placeholder="0.00" required>
                </div>

                <div class="campo">
                    <label for="fecha-estimada">Fecha estimada de cumplimiento</label>
                    <input type="date" id="fecha-estimada" name="fecha_estimada" required>
                </div>

                <button type="submit" class="btn-primario">Crear meta</button>
            </form>
        </section>

        <section class="lista-metas" id="lista-metas" aria-label="Metas de ahorro registradas">
            <h2>Mis metas</h2>

            <!-- Ejemplo de tarjeta de meta; se generarán dinámicamente desde la base de datos -->
            <article class="tarjeta-meta">
                <div class="encabezado-meta">
                    <h3>Fondo de emergencia</h3>
                    <span class="fecha-meta">Meta: 30/12/2026</span>
                </div>

                <div class="barra-progreso" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                    <div class="progreso" style="width: 0%;"></div>
                </div>

                <p class="detalle-meta"><span id="ahorrado-0">₡0.00</span> ahorrados de <span id="objetivo-0">₡0.00</span></p>
            </article>

            <p class="fila-vacia" id="mensaje-sin-metas">Todavía no has creado ninguna meta de ahorro.</p>
        </section>

    </main>

    <footer class="footer-app">
        <p>&copy; 2026 SmartBudget - Grupo 4</p>
    </footer>

    <script src="../../public/js/script.js"></script>
</body>
</html>
