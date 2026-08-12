<?php
session_start();
require_once "../models/Gasto.php";
require_once "../models/Ingreso.php";
require_once "../models/Meta.php";

if (!isset($_SESSION['usuario'])) {
    header("Location: ../../public/index.php");
    exit();
}

$loginOk = isset($_GET['login']) && $_GET['login'] === 'ok';
$id_usuario = (int) $_SESSION['usuario']['id_usuario'];
$totalIngresos = Ingreso::obtenerTotalMesActual($id_usuario);
$totalGastos = Gasto::obtenerTotalMesActual($id_usuario);
$cuotasMetas = Meta::obtenerTotalCuotasMesActual($id_usuario);
$balanceDisponible = $totalIngresos - $cuotasMetas - $totalGastos;
$meses = [
    1 => 'enero',
    2 => 'febrero',
    3 => 'marzo',
    4 => 'abril',
    5 => 'mayo',
    6 => 'junio',
    7 => 'julio',
    8 => 'agosto',
    9 => 'septiembre',
    10 => 'octubre',
    11 => 'noviembre',
    12 => 'diciembre'
];
$nombreMesActual = $meses[(int) date('n')];
$resultadoGastosCategoria = Gasto::obtenerTotalesPorCategoria($id_usuario);
$arregloGastosCategoria = [];

while ($fila = $resultadoGastosCategoria->fetch_assoc()) {
    $arregloGastosCategoria[] = [
        'categoria' => $fila['categoria'],
        'total' => (float) $fila['total']
    ];
}
//Obtener los resutados en formatos mysqli_result
$resultadosGastosSeisMeses = Gasto::obtenerTotalesUltimosSeisMeses($id_usuario);
$resultadosIngresosSeisMeses = Ingreso::obtenerTotalesUltimosSeisMeses($id_usuario);
$resultadoMetas = Meta::obtenerPorUsuario($id_usuario);


// Obtener los totales de gastos de los últimos 6 meses
$totalesGastosPorMes = [];
while ($fila = $resultadosGastosSeisMeses->fetch_assoc()) {
    $totalesGastosPorMes[$fila['mes_anio']] = (float) $fila['total'];
}
//Obtener los totales de ingresos de los últimos 6 meses
$totalesIngresosPorMes = [];
while ($fila = $resultadosIngresosSeisMeses->fetch_assoc()) {
    $totalesIngresosPorMes[$fila['mes_anio']] = (float) $fila['total'];
}

$metas = [];
while ($fila = $resultadoMetas->fetch_assoc()) {
    $metas[] = $fila;
}

// Preparar los datos para los gráficos circulares de metas
$arregloCuatroMetasCirculares = [];
for($i = 0; $i < 4 && $i < count($metas); $i++) {
    //Obtener la meta de la iteración actual
    $meta = $metas[$i];

    //Calculando los meses ahorrados
    $mesInicio = new DateTime($meta['fecha_inicio']);
    $mesInicio->modify('first day of this month');
    $mesActual = new DateTime('first day of this month');
    $mesesAhorrados = 0;

    if ($mesInicio <= $mesActual) {
        $diferencia = $mesInicio->diff($mesActual);
        $mesesAhorrados = ($diferencia->y * 12) + $diferencia->m + 1;
    }
    $montoObjetivo = (float) $meta['monto_objetivo'];
    $montoActual = (float) $meta['monto_inicial']
        + ((float) $meta['cuota'] * $mesesAhorrados);

    $montoActual = min($montoActual, $montoObjetivo);
    $porcentaje = $montoObjetivo > 0 ? ($montoActual / $montoObjetivo) * 100 : 0;

    $arregloCuatroMetasCirculares[] = [
        'nombre' => $meta['nombre'],
        'monto_actual' => round((float) $montoActual, 2),
        'monto_objetivo' => (float) $meta['monto_objetivo'],
        'porcentaje' => round((float) $porcentaje, 1)
    ];
}




//Arreglo de 6 meses completos, incluyendo los meses sin datos.
$arregloGastosSeisMeses = [];
$arregloIngresosSeisMeses = [];
$arregloMetasSeisMeses = [];

$fecha = new DateTime('first day of this month');
$fecha->modify('-5 months');

// Crea arreglos de 6 meses de metas, ingresos y gastos, incluyendo meses sin datos
for ($i = 0; $i < 6; $i++) {
    $mes = $fecha->format('Y-m');
    $inicioMes = $fecha->format('Y-m-01');
    $finMes = $fecha->format('Y-m-t');

    $totalMetasMes = 0;

    foreach ($metas as $meta) {
        $metaActivaDuranteMes = 
            (int) $meta['id_estado'] === 2 &&
            $meta['fecha_inicio'] <= $finMes &&
            $meta['fecha_cumplimiento'] >= $inicioMes;

        if ($metaActivaDuranteMes) {
            $totalMetasMes += (float) $meta['cuota'];
        }
    }

    $arregloIngresosSeisMeses[] = [
        'mes' => $mes,
        'total' => $totalesIngresosPorMes[$mes] ?? 0
    ];

    $arregloGastosSeisMeses[] = [
        'mes' => $mes,
        'total' => $totalesGastosPorMes[$mes] ?? 0
    ];

    $arregloMetasSeisMeses[] = [
        'mes' => $mes,
        'total' => $totalMetasMes
    ];

    $fecha->modify('+1 month');
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
    <link rel="stylesheet" href="../../public/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <header class="topbar">
        <div class="marca">  
            <img src="../../public/img/icon.png" alt="Icono de Smartbudget"  class="icon-small">
            <span>SmartBudget</span>
        </div>

        <nav class="menu-principal" aria-label="Navegación principal">
            <ul>
                <li><a href="dashboard.php" class="activo">Dashboard</a></li>
                <li><a href="ingresos.php">Ingresos</a></li>
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

        <h1>Resumen del mes</h1>

        <section class="tarjetas-resumen" aria-label="Resumen financiero">
            <article class="tarjeta">
                <h2>Total de ingresos</h2>
                <p class="monto ingreso" id="total-ingresos">₡<?php echo number_format($totalIngresos, 2); ?></p>
            </article>

            <article class="tarjeta">
                <h2>Aporte mensual a metas</h2>
                <p class="monto reservado" id="cuota-metas">₡<?php echo number_format($cuotasMetas, 2); ?></p>
            </article>

            <article class="tarjeta">
                <h2>Total de gastos</h2>
                <p class="monto gasto" id="total-gastos">₡<?php echo number_format($totalGastos, 2); ?></p>
            </article>

            <article class="tarjeta">
                <h2>Balance disponible</h2>
                <p class="monto" id="balance-disponible">₡<?php echo number_format($balanceDisponible, 2); ?></p>
            </article>
        </section>

        <section class="alertas" aria-live="polite">
            <div class="alerta alerta-advertencia" <?php echo $totalGastos < 180000 ? 'hidden' : ''; ?> id="alerta-categoria">
                ⚠️ Te acercas al monto máximo planificado en la categoría <strong>Alimentación</strong>.
            </div>
        </section>

        <section class="zona-analitica" aria-label="Gráficos y metas">
            <div class="tarjeta-grafico">
                <div class="tarjeta-gasto-categoria">
                    <h2>Gastos por categoría del mes de <?php echo $nombreMesActual; ?></h2>
                    <div class="grafico-categoria-contenedor">
                        <canvas id="grafico-categorias" aria-label="Gráfico de gastos por categoría"></canvas>
                    </div>
                </div>
            </div>

            <div class="tarjeta-grafico">
                <div class="tarjeta-ingreso-gastos-metas-comprometido">
                    <h2>Ingresos vs. gastos vs. metas vs. dinero comprometido (últimos 6 meses)</h2>
                    <div class="grafico-ingreso-gastos-metas-comprometido-contenedor">
                        <canvas id="grafico-seis-meses" aria-label="Gráfico de ingresos, gastos, metas y dinero comprometido"></canvas>
                    </div>
                </div>
            </div>

            <div class="tarjeta-grafico">
                <div class="tarjeta-cumplimiento-metas">
                    <h2>Resumen de cumplimiento de metas</h2>
                    <div class="metas-circulares-grid">
                        <div class="meta-circular-placeholder">
                            <canvas id="grafico-meta-1" aria-label="Gráfico circular de meta 1"></canvas>
                            <span>Meta 1: <?php echo htmlspecialchars($arregloCuatroMetasCirculares[0]['nombre'] ?? 'Agrega una meta'); ?></span>
                        </div>
                        <div class="meta-circular-placeholder">
                            <canvas id="grafico-meta-2" aria-label="Gráfico circular de meta 2"></canvas>
                            <span>Meta 2: <?php echo htmlspecialchars($arregloCuatroMetasCirculares[1]['nombre'] ?? 'Agrega una meta'); ?></span>
                        </div>
                        <div class="meta-circular-placeholder">
                            <canvas id="grafico-meta-3" aria-label="Gráfico circular de meta 3"></canvas>
                            <span>Meta 3: <?php echo htmlspecialchars($arregloCuatroMetasCirculares[2]['nombre'] ?? 'Agrega una meta'); ?></span>
                        </div>
                        <div class="meta-circular-placeholder">
                            <canvas id="grafico-meta-4" aria-label="Gráfico circular de meta 4"></canvas>
                            <span>Meta 4: <?php echo htmlspecialchars($arregloCuatroMetasCirculares[3]['nombre'] ?? 'Agrega una meta'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="accesos-rapidos">
            <h2>Accesos rápidos</h2>
            <div class="botones-rapidos">
                <a href="ingresos.php" class="btn-terciario">+ Nuevo ingreso</a>
                <a href="gastos.php" class="btn-terciario">+ Nuevo gasto</a>
                <a href="metas.php" class="btn-terciario">+ Nueva meta de ahorro</a>
            </div>
        </section>

    </main>

    <footer class="footer-app">
        <p>&copy; 2026 SmartBudget - Grupo 4</p>
    </footer>

    <?php if ($loginOk): ?>
        <div class="toast toast-success" id="loginToast">
            Inicio de sesión exitoso
        </div>
    <?php endif; ?>

    <script id="gastos-categoria-data" type="application/json"><?php
        echo json_encode($arregloGastosCategoria, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP);
    ?></script>
    <script id="ingresos-seis-meses-data" type="application/json"><?php
        echo json_encode($arregloIngresosSeisMeses, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP);
    ?></script>
    <script id="gastos-seis-meses-data" type="application/json"><?php
        echo json_encode($arregloGastosSeisMeses, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP);
    ?></script>
    <script id="metas-seis-meses-data" type="application/json"><?php
        echo json_encode($arregloMetasSeisMeses, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP);
    ?></script>
    <script id="metas-circulares-data" type="application/json"><?php
        echo json_encode($arregloCuatroMetasCirculares, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP);
    ?></script>
    
    <script src="../../public/js/script.js"></script>
</body>
</html>