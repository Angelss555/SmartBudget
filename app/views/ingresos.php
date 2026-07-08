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
  Página: Gestión de ingresos
  Corresponde a la función 4, Registro de ingresos
-->
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartBudget | Ingresos</title>
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
                <li><a href="ingresos.php" class="activo">Ingresos</a></li>
                <li><a href="gastos.php">Gastos</a></li>
                <li><a href="reportes.php">Reportes</a></li>
                <li><a href="metas.php">Metas de ahorro</a></li>
                <li><a href="configuracion.php">Configuración</a></li>
            </ul>
        </nav>

        <div class="usuario-sesion">
            <span id="nombre-usuario">Hola, <?php echo htmlspecialchars($_SESSION['usuario']['nombre']); ?></span>
            <a href="../../public/index.php" class="btn-salir">Cerrar sesión</a>
        </div>
    </header>

    <main class="contenido">

        <h1>Gestión de ingresos</h1>

        <section class="tarjeta-formulario">
            <h2>Registrar nuevo ingreso</h2>

            <!-- El "action" apunta al futuro controlador PHP de ingresos -->
            <form id="form-ingreso" method="POST" action="controllers/IngresoController.php" novalidate>

                <div class="campo">
                    <label for="nombre-ingreso">Nombre del ingreso</label>
                    <input type="text" id="nombre-ingreso" name="nombre" placeholder="Ej. Salario, Freelance" required>
                </div>

                <div class="campo">
                    <label for="monto-ingreso">Monto</label>
                    <input type="number" id="monto-ingreso" name="monto" min="0" step="0.01" placeholder="0.00" required>
                </div>

                <div class="campo">
                    <label for="tipo-ingreso">Tipo de ingreso</label>
                    <select id="tipo-ingreso" name="tipo" required>
                        <option value="">Selecciona una opción</option>
                        <option value="permanente">Permanente</option>
                        <option value="extraordinario">Extraordinario</option>
                    </select>
                </div>

                <div class="campo">
                    <label for="categoria-ingreso">Categoría</label>
                    <select id="categoria-ingreso" name="categoria" required>
                        <option value="">Selecciona una categoría</option>
                        <option value="salario">Salario</option>
                        <option value="freelance">Freelance</option>
                        <option value="inversiones">Inversiones</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>

                <div class="campo">
                    <label for="fecha-ingreso">Fecha</label>
                    <input type="date" id="fecha-ingreso" name="fecha" required>
                </div>

                <div class="campo">
                    <label for="descripcion-ingreso">Descripción</label>
                    <textarea id="descripcion-ingreso" name="descripcion" placeholder="Detalle adicional (opcional)"></textarea>
                </div>

                <button type="submit" class="btn-primario">Guardar ingreso</button>
            </form>
        </section>

        <section class="tarjeta-tabla">
            <div class="encabezado-tabla">
                <h2>Historial de ingresos</h2>

                <div class="filtros">
                    <label for="filtro-periodo">Periodo</label>
                    <select id="filtro-periodo" name="periodo">
                        <option value="mes-actual">Mes actual</option>
                        <option value="ultimos-3">Últimos 3 meses</option>
                        <option value="ultimos-6">Últimos 6 meses</option>
                    </select>
                </div>
            </div>

            <table class="tabla-datos" id="tabla-ingresos">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Tipo</th>
                        <th>Monto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Las filas se llenarán dinámicamente desde la base de datos -->
                    <tr class="fila-vacia">
                        <td colspan="6">Todavía no hay ingresos registrados.</td>
                    </tr>
                </tbody>
            </table>
        </section>

    </main>

    <footer class="footer-app">
        <p>&copy; 2026 SmartBudget - Grupo 4</p>
    </footer>

    <script src="../../public/js/script.js"></script>
</body>
</html>
