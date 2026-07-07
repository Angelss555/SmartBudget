<!DOCTYPE html>
<!--
  Proyecto: SmartBudget
  Página: Reportes financieros
  Corresponde a la función 8 Reportes financieros
-->
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartBudget | Reportes financieros</title>
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
                <li><a href="reportes.php" class="activo">Reportes</a></li>
                <li><a href="metas.php">Metas de ahorro</a></li>
                <li><a href="configuracion.php">Configuración</a></li>
            </ul>
        </nav>

        <div class="usuario-sesion">
            <span id="nombre-usuario">Hola, Usuario</span>
            <a href="../../public/index.php" class="btn-salir">Cerrar sesión</a>
        </div>
    </header>

    <main class="contenido">

        <h1>Reportes financieros</h1>

        <section class="tarjeta-formulario">
            <h2>Filtrar reporte</h2>

            <form id="form-reporte" method="GET" action="controllers/ReporteController.php">

                <div class="campo">
                    <label for="periodo-reporte">Periodo</label>
                    <select id="periodo-reporte" name="periodo">
                        <option value="actual">Mes actual</option>
                        <option value="mes-1">Hace 1 mes</option>
                        <option value="mes-2">Hace 2 meses</option>
                        <option value="mes-3">Hace 3 meses</option>
                        <option value="mes-4">Hace 4 meses</option>
                        <option value="mes-5">Hace 5 meses</option>
                        <option value="mes-6">Hace 6 meses</option>
                    </select>
                </div>

                <div class="campo">
                    <label for="categoria-reporte">Categoría</label>
                    <select id="categoria-reporte" name="categoria">
                        <option value="todas">Todas las categorías</option>
                        <option value="alimentacion">Alimentación</option>
                        <option value="transporte">Transporte</option>
                        <option value="salud">Salud</option>
                        <option value="educacion">Educación</option>
                        <option value="ocio">Ocio</option>
                    </select>
                </div>

                <button type="submit" class="btn-primario">Consultar</button>
                <button type="button" class="btn-secundario" id="btn-descargar" >Descargar reporte</button>
            </form>
        </section>

        <section class="graficos">
            <div class="tarjeta-grafico">
                <h2>Distribución de gastos</h2>
                <div class="placeholder-grafico" id="grafico-distribucion">Gráfico de distribución de gastos</div>
            </div>

            <div class="tarjeta-grafico">
                <h2>Comportamiento de ingresos</h2>
                <div class="placeholder-grafico" id="grafico-ingresos">Gráfico de ingresos</div>
            </div>

            <div class="tarjeta-grafico">
                <h2>Balance financiero</h2>
                <div class="placeholder-grafico" id="grafico-balance">Gráfico de balance</div>
            </div>
        </section>

        <section class="tarjeta-tabla">
            <h2>Detalle del periodo seleccionado</h2>

            <table class="tabla-datos" id="tabla-reporte">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Categoría</th>
                        <th>Descripción</th>
                        <th>Monto</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="fila-vacia">
                        <td colspan="5">Selecciona un periodo para ver el detalle.</td>
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
