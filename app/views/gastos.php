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
  Página: Gestión de gastos y categorías de gastos
  Corresponde a las funciones 5, Registro de categorías de gastos
  y 6 Registro de gastos 
-->
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartBudget | Gastos</title>
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
                <li><a href="gastos.php" class="activo">Gastos</a></li>
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

        <h1>Gestión de gastos</h1>

        <section class="tarjeta-formulario">
            <h2>Registrar nuevo gasto</h2>

            <!-- El "action" apunta al futuro controlador PHP de gastos -->
            <form id="form-gasto" method="POST" action="controllers/GastoController.php" novalidate>

                <div class="campo">
                    <label for="nombre-gasto">Nombre del gasto</label>
                    <input type="text" id="nombre-gasto" name="nombre" placeholder="Ej. Supermercado, Netflix" required>
                </div>

                <div class="campo">
                    <label for="monto-gasto">Monto</label>
                    <input type="number" id="monto-gasto" name="monto" min="0" step="0.01" placeholder="0.00" required>
                </div>

                <div class="campo">
                    <label for="categoria-gasto">Categoría</label>
                    <select id="categoria-gasto" name="categoria" required>
                        <option value="">Selecciona una categoría</option>
                        <option value="alimentacion">Alimentación</option>
                        <option value="transporte">Transporte</option>
                        <option value="salud">Salud</option>
                        <option value="educacion">Educación</option>
                        <option value="ocio">Ocio</option>
                        <option value="pagos-fijos">Pagos fijos mensuales</option>
                        <option value="pagos-trimestrales">Pagos trimestrales</option>
                        <option value="nueva">+ Crear categoría nueva</option>
                    </select>
                </div>

                <div class="campo" id="campo-categoria-nueva" hidden>
                    <label for="categoria-nueva">Nombre de la nueva categoría</label>
                    <input type="text" id="categoria-nueva" name="categoria_nueva" placeholder="Ej. Mascotas">
                </div>

                <div class="campo">
                    <label for="frecuencia-gasto">Frecuencia</label>
                    <select id="frecuencia-gasto" name="frecuencia" required>
                        <option value="diario">Diario</option>
                        <option value="semanal">Semanal</option>
                        <option value="mensual">Mensual</option>
                        <option value="trimestral">Trimestral</option>
                    </select>
                </div>

                <div class="campo">
                    <label for="fecha-gasto">Fecha</label>
                    <input type="date" id="fecha-gasto" name="fecha" required>
                </div>

                <div class="campo">
                    <label for="descripcion-gasto">Descripción</label>
                    <textarea id="descripcion-gasto" name="descripcion" placeholder="Detalle adicional (opcional)"></textarea>
                </div>

                <button type="submit" class="btn-primario">Guardar gasto</button>
            </form>
        </section>

        <section class="tarjeta-tabla">
            <div class="encabezado-tabla">
                <h2>Historial de gastos</h2>

                <div class="filtros">
                    <label for="filtro-categoria">Categoría</label>
                    <select id="filtro-categoria" name="filtro_categoria">
                        <option value="todas">Todas</option>
                        <option value="alimentacion">Alimentación</option>
                        <option value="transporte">Transporte</option>
                        <option value="salud">Salud</option>
                        <option value="educacion">Educación</option>
                        <option value="ocio">Ocio</option>
                    </select>
                </div>
            </div>

            <table class="tabla-datos" id="tabla-gastos">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Frecuencia</th>
                        <th>Monto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Las filas se llenarán dinámicamente desde la base de datos -->
                    <tr class="fila-vacia">
                        <td colspan="6">Todavía no hay gastos registrados.</td>
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
