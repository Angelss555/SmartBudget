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
  Página: Dashboard principal
  Corresponde a la función 7, Dashboard
  resumen mensual de balance, ingresos, gastos, gráficos y alertas
-->
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartBudget | Dashboard</title>
    <link rel="stylesheet" href="/smartbudget/public/css/styles.css">
</head>
<body>

    <header class="topbar">
        <div class="marca">
            
            <img src="../../public/img/logo.png" alt="Logotipo de SmartBudget" class="logo-small">
            <span>SmartBudget</span>
        </div>

        <nav class="menu-principal" aria-label="Navegación principal">
            <ul>
                <li><a href="/app/views/dashboard.php" class="activo">Dashboard</a></li>
                <li><a href="/app/views/ingresos.php">Ingresos</a></li>
                <li><a href="/app/views/gastos.php">Gastos</a></li>
                <li><a href="/app/views/reportes.php">Reportes</a></li>
                <li><a href="/app/views/metas.php">Metas de ahorro</a></li>
                <li><a href="/app/views/configuracion.php">Configuración</a></li>
            </ul>
        </nav>

        <div class="usuario-sesion">
            <span id="nombre-usuario">Hola, Usuario</span>
            <a href="../../public/index.php" class="btn-salir">Cerrar sesión</a>
        </div>
    </header>

    <main class="contenido">

        <h1>Resumen del mes</h1>

        <section class="tarjetas-resumen" aria-label="Resumen financiero">
            <article class="tarjeta">
                <h2>Balance disponible</h2>
                <p class="monto" id="balance-disponible">₡0.00</p>
            </article>

            <article class="tarjeta">
                <h2>Total de ingresos</h2>
                <p class="monto ingreso" id="total-ingresos">₡0.00</p>
            </article>

            <article class="tarjeta">
                <h2>Total de gastos</h2>
                <p class="monto gasto" id="total-gastos">₡0.00</p>
            </article>
        </section>

        <section class="alertas" aria-live="polite">
            <div class="alerta alerta-advertencia" hidden id="alerta-categoria">
                ⚠️ Te acercas al monto máximo planificado en la categoría <strong>Alimentación</strong>.
            </div>
        </section>

        <section class="graficos">
            <div class="tarjeta-grafico">
                <h2>Gastos por categoría</h2>
                <div class="placeholder-grafico" id="grafico-categorias">Gráfico de gastos por categoría</div>
            </div>

            <div class="tarjeta-grafico">
                <h2>Ingresos vs. gastos (últimos 6 meses)</h2>
                <div class="placeholder-grafico" id="grafico-tendencia">Gráfico de tendencia mensual</div>
            </div>
        </section>

        <section class="accesos-rapidos">
            <h2>Accesos rápidos</h2>
            <div class="botones-rapidos">
                <a href="ingresos.php" class="btn-secundario">+ Nuevo ingreso</a>
                <a href="gastos.php" class="btn-secundario">+ Nuevo gasto</a>
                <a href="metas.php" class="btn-secundario">+ Nueva meta de ahorro</a>
            </div>
        </section>

    </main>

    <footer class="footer-app">
        <p>&copy; 2026 SmartBudget - Grupo 4</p>
    </footer>

    <script src="../../public/js/script.js"></script>
</body>
</html>
