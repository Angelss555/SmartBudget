<?php
session_start();
require_once "../models/Gasto.php";
require_once "../models/CategoriaGasto.php";

if (!isset($_SESSION['usuario'])) {
    header("Location: ../../public/index.php");
    exit();
}

$id_usuario = (int) $_SESSION['usuario']['id_usuario'];
$categorias = CategoriaGasto::obtenerTodos();
$gastos = Gasto::obtenerPorUsuario($id_usuario);
$gastosSeisMeses = Gasto::obtenerTotalesUltimosSeisMeses($id_usuario);

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

        <div class="usuario_sesion">
            <span id="nombre_usuario">Hola, <?php echo htmlspecialchars($_SESSION['usuario']['nombre']); ?></span>
            <a href="../../public/index.php" class="btn-salir">Cerrar sesión</a>
        </div>
    </header>

    <main class="contenido">

        <h1>Gestión de gastos</h1>

        <section class="tarjeta-formulario">
            <h2>Registrar nuevo gasto</h2>

            <!-- El "action" apunta al controlador PHP de gastos -->
            <form id="form-gasto" method="POST" action="../controllers/ExpenseController.php" novalidate>

                <div class="campo">
                    <label for="nombre">Nombre del gasto</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Ej. Supermercado, Netflix" required>
                </div>

                <div class="campo">
                    <label for="monto">Monto</label>
                    <input type="number" id="monto" name="monto" min="0" step="0.01" placeholder="0.00" required>
                </div>

                <div class="campo">
                    <label for="id_categoria">Categoría</label>
                    <select id="id_categoria" name="id_categoria" required>
                        <option value="">Selecciona una categoría</option>
                        <?php while ($categoria = $categorias->fetch_assoc()): ?>
                            <option value="<?php echo (int) $categoria['id_categoria']; ?>">
                                <?php echo htmlspecialchars($categoria['nombre']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="campo">
                    <label for="fecha">Fecha</label>
                    <input type="date" id="fecha" name="fecha" required>
                </div>

                <div class="campo">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion" placeholder="Detalle adicional (opcional)"></textarea>
                </div>

                <input type="hidden" name="id_estado" value="2">

                <button type="submit" name="accion" value="crearGasto" class="btn-primario">Guardar gasto</button>
            </form>
        </section>

        <section class="tarjeta-tabla">
            <div class="encabezado-tabla">
                    <h2>Listado de gastos</h2>

                <div class="filtros">
                    <label for="filtro-periodo">Periodo</label>
                    <select id="filtro-periodo" name="periodo">
                        <option value="mes-actual">Mes actual</option>
                        <option value="ultimos-3">Últimos 3 meses</option>
                        <option value="ultimos-6">Últimos 6 meses</option>
                    </select>
                </div>
            </div>

            <table class="tabla-datos" id="tabla-gastos">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Descripción</th>
                        <th>Monto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($gastos->num_rows === 0): ?>
                        <tr class="fila-vacia">
                            <td colspan="6">Todavía no hay gastos registrados.</td>
                        </tr>
                    <?php else: ?>
                        <?php while ($gasto = $gastos->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <?php
                                        $fecha = new DateTime($gasto['fecha']);
                                        echo $fecha->format('d/m/Y');
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($gasto['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($gasto['categoria']); ?></td>
                                <td><?php echo htmlspecialchars($gasto['descripcion'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>₡<?php echo number_format((float) $gasto['monto'], 2); ?></td>
                                <td>
                                    <form method="POST" action="../controllers/ExpenseController.php">
                                        <input type="hidden" name="id_gasto" value="<?php echo (int) $gasto['id_gasto']; ?>">
                                        <button type="submit" name="accion" value="eliminarGasto" class="btn-eliminar">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
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
