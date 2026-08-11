<?php
session_start();
require_once "../models/Meta.php";

if (!isset($_SESSION['usuario'])) {
    header("Location: ../../public/index.php");
    exit();
}

$id_usuario = (int) $_SESSION['usuario']['id_usuario'];
$metas = Meta::obtenerPorUsuario($id_usuario);
?>

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
            <span id="nombre-usuario">Hola, <?php echo htmlspecialchars($_SESSION['usuario']['nombre']); ?></span>
            <a href="../../public/index.php" class="btn-salir">Cerrar sesión</a>
        </div>
    </header>

    <main class="contenido">

        <h1>Metas de ahorro</h1>

        <section class="tarjeta-formulario">
            <h2>Crear nueva meta</h2>

            <form id="form-meta" method="POST" action="../controllers/GoalController.php" novalidate>

                <div class="campo">
                    <label for="nombre">Nombre de la meta</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Ej. Viaje, Fondo de emergencia" required>
                </div>

                <div class="campo">
                    <label for="monto_inicial">Monto inicial</label>
                    <input type="number" id="monto_inicial" name="monto_inicial" min="0" step="0.01" placeholder="0.00" required>
                </div>

                <div class="campo">
                    <label for="monto_objetivo">Monto objetivo</label>
                    <input type="number" id="monto_objetivo" name="monto_objetivo" min="0" step="0.01" placeholder="0.00" required>
                </div>

                <div class="campo">
                    <label for="cuota">Cuota</label>
                    <input type="number" id="cuota" name="cuota" min="0.01" step="0.01" placeholder="0.00" required>
                </div>

                <div class="campo">
                    <label for="fecha_inicio">Fecha de inicio</label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" required>
                </div>

                <div class="campo">
                    <label for="fecha_cumplimiento">Fecha estimada de cumplimiento</label>
                    <input type="date" id="fecha_cumplimiento" name="fecha_cumplimiento" required>
                </div>

                <div class="campo">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion" rows="3" placeholder="Descripción de la meta..."></textarea>
                </div>

                <input type="hidden" name="id_estado" value="2">

                <button type="submit" name="accion" value="crearMeta" class="btn-primario">Crear meta</button>
            </form>
        </section>

        <section class="lista-metas" id="lista-metas" aria-label="Metas de ahorro registradas">
            <h2>Mis metas</h2>

            <?php if ($metas->num_rows === 0): ?>
                <p class="fila-vacia" id="mensaje-sin-metas">Todavía no has creado ninguna meta de ahorro.</p>
            <?php else: ?>
                <?php while ($meta = $metas->fetch_assoc()): ?>
                    <?php
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
                        
                    $montoActualCumplido = min($montoActual, $montoObjetivo);
                    $porcentaje = $montoObjetivo > 0
                        ? min(($montoActualCumplido / $montoObjetivo) * 100, 100)
                        : 0;
                    ?>
                    <article class="tarjeta-meta">
                        <div class="cuerpo-meta">
                            <div class="encabezado-meta">
                                <h3>
                                    <?php echo htmlspecialchars($meta['nombre']); ?>
                                    - Cuota de ₡<?php echo number_format((float) $meta['cuota'], 2); ?>
                                </h3>
                                <div class="fechas-meta">
                                    <span class="fecha-meta">Fecha inicial: <?php echo (new DateTime($meta['fecha_inicio']))->format('d/m/Y'); ?></span>
                                    <span class="fecha-meta">Fecha objetivo: <?php echo (new DateTime($meta['fecha_cumplimiento']))->format('d/m/Y'); ?></span>
                                </div>
                            </div>
                            <p class="descripcion-meta"><?php echo htmlspecialchars($meta['descripcion'] ?? ''); ?></p>
                            <div class="barra-progreso" role="progressbar" aria-valuenow="<?php echo round($porcentaje); ?>" aria-valuemin="0" aria-valuemax="100">
                                <div class="progreso" style="width: <?php echo $porcentaje; ?>%;"></div>
                            </div>
                            <div class="meta-acciones">
                                <p class="detalle-meta">
                                    <span>₡<?php echo number_format($montoActual, 2); ?></span> ahorrados de
                                    <span>₡<?php echo number_format($montoObjetivo, 2); ?></span>
                                </p>
                            </div>
                        </div>
                        <div class="accion-meta">
                            <form method="POST" action="../controllers/GoalController.php" class="meta-form-eliminar">
                                <input type="hidden" name="id_meta" value="<?php echo (int) $meta['id_meta']; ?>">
                                <button type="submit" name="accion" value="eliminarMeta" class="btn-eliminar">Eliminar</button>
                            </form>
                        </div>
                    </article>
                <?php endwhile; ?>
            <?php endif; ?>
        </section>

    </main>

    <footer class="footer-app">
        <p>&copy; 2026 SmartBudget - Grupo 4</p>
    </footer>

    <script src="../../public/js/script.js"></script>
</body>
</html>
