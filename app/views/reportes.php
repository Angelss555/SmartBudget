<?php
session_start();
require_once "../models/CategoriaGasto.php";
require_once "../models/Gasto.php";
require_once "../models/Ingreso.php";

if (!isset($_SESSION['usuario'])) {
    header("Location: ../../public/index.php");
    exit();
}

$id_usuario = (int) $_SESSION['usuario']['id_usuario'];
$categorias = CategoriaGasto::obtenerTodos();
$periodoSeleccionado = $_GET['periodo'] ?? 'actual';
$periodos = ['actual' => 0, 'mes-1' => 1, 'mes-2' => 2, 'mes-3' => 3, 'mes-4' => 4, 'mes-5' => 5, 'mes-6' => 6];
$mesesAtras = $periodos[$periodoSeleccionado] ?? 0;
$periodoSeleccionado = array_key_exists($periodoSeleccionado, $periodos) ? $periodoSeleccionado : 'actual';
$inicioPeriodo = (new DateTimeImmutable('first day of this month'))->modify("-{$mesesAtras} months");
$finPeriodo = $inicioPeriodo->modify('last day of this month');
$categoriaSeleccionada = ctype_digit((string) ($_GET['categoria'] ?? '')) ? (int) $_GET['categoria'] : null;
$gastos = Gasto::obtenerPorPeriodo($id_usuario, $inicioPeriodo->format('Y-m-d'), $finPeriodo->format('Y-m-d'), $categoriaSeleccionada);
$ingresos = $categoriaSeleccionada === null
    ? Ingreso::obtenerPorPeriodo($id_usuario, $inicioPeriodo->format('Y-m-d'), $finPeriodo->format('Y-m-d'))
    : null;
$movimientos = [];
$totalGastos = 0.0;
$totalIngresos = 0.0;
while ($gasto = $gastos->fetch_assoc()) {
    $totalGastos += (float) $gasto['monto'];
    $movimientos[] = array_merge($gasto, ['tipo' => 'Gasto']);
}
if ($ingresos !== null) {
    while ($ingreso = $ingresos->fetch_assoc()) {
        $totalIngresos += (float) $ingreso['monto'];
        $movimientos[] = array_merge($ingreso, ['tipo' => 'Ingreso']);
    }
}
usort($movimientos, fn($a, $b) => strcmp($b['fecha'], $a['fecha']));
?>

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
            <span id="nombre-usuario">Hola, <?php echo htmlspecialchars($_SESSION['usuario']['nombre']); ?></span>
            <a href="../../public/index.php" class="btn-salir">Cerrar sesión</a>
        </div>
    </header>

    <main class="contenido">

        <h1>Reportes financieros</h1>

        <section class="tarjeta-formulario">
            <h2>Filtrar reporte</h2>

            <form id="form-reporte" method="GET" action="reportes.php">

                <div class="campo">
                    <label for="periodo-reporte">Periodo</label>
                    <select id="periodo-reporte" name="periodo">
                        <option value="actual" <?php echo $periodoSeleccionado === 'actual' ? 'selected' : ''; ?>>Mes actual</option>
                        <?php for ($i = 1; $i <= 6; $i++): ?>
                            <option value="mes-<?php echo $i; ?>" <?php echo $periodoSeleccionado === "mes-{$i}" ? 'selected' : ''; ?>>Hace <?php echo $i; ?> mes<?php echo $i === 1 ? '' : 'es'; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="campo">
                    <label for="categoria-reporte">Categoría</label>
                    <select id="categoria-reporte" name="categoria">
                        <option value="todas">Todas las categorías</option>
                        <?php while ($categoria = $categorias->fetch_assoc()): ?>
                            <option value="<?php echo (int) $categoria['id_categoria']; ?>" <?php echo $categoriaSeleccionada === (int) $categoria['id_categoria'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($categoria['nombre']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <button type="submit" class="btn-primario">Consultar</button>
                <button type="button" class="btn-secundario" id="btn-descargar">Descargar reporte</button>
            </form>
        </section>

        <section class="graficos">
            <div class="tarjeta-grafico">
                <h2>Distribución de gastos</h2>
                <div class="placeholder-grafico" id="grafico-distribucion"><strong>Gastos</strong><span class="valor-reporte gasto">₡<?php echo number_format($totalGastos, 2); ?></span></div>
            </div>

            <div class="tarjeta-grafico">
                <h2>Comportamiento de ingresos</h2>
                <div class="placeholder-grafico" id="grafico-ingresos"><strong>Ingresos</strong><span class="valor-reporte ingreso">₡<?php echo number_format($totalIngresos, 2); ?></span></div>
            </div>

            <div class="tarjeta-grafico">
                <h2>Balance financiero</h2>
                <div class="placeholder-grafico" id="grafico-balance"><strong>Balance</strong><span class="valor-reporte">₡<?php echo number_format($totalIngresos - $totalGastos, 2); ?></span></div>
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
                    <?php if (empty($movimientos)): ?>
                        <tr class="fila-vacia"><td colspan="5">No hay movimientos para el filtro seleccionado.</td></tr>
                    <?php else: ?>
                        <?php foreach ($movimientos as $movimiento): ?>
                            <?php $claseFila = $movimiento['tipo'] === 'Gasto' ? 'fila-gasto' : 'fila-ingreso'; ?>
                            <tr class="<?php echo $claseFila; ?>">
                                <td><?php echo (new DateTime($movimiento['fecha']))->format('d/m/Y'); ?></td>
                                <td><?php echo htmlspecialchars($movimiento['tipo']); ?></td>
                                <td><?php echo htmlspecialchars($movimiento['categoria']); ?></td>
                                <td><?php echo htmlspecialchars($movimiento['descripcion'] ?: $movimiento['nombre']); ?></td>
                                <td>₡<?php echo number_format((float) $movimiento['monto'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
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
