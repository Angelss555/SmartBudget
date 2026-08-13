<?php
session_start();

require_once "../models/CategoriaIngreso.php";
require_once "../models/Ingreso.php";

if (!isset($_SESSION['usuario'])) {
    header("Location: ../../public/index.php");
    exit();
}

$id_usuario = (int) $_SESSION['usuario']['id_usuario'];
$categorias = CategoriaIngreso::obtenerTodos();
$ingresos = Ingreso::obtenerPorUsuario($id_usuario);


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

            <div class="usuario_sesion">
                <span id="nombre_usuario">Hola, <?php echo htmlspecialchars($_SESSION['usuario']['nombre']); ?></span>
                <a href="../../public/index.php" class="btn-salir">Cerrar sesión</a>
            </div>
        </header>

        <main class="contenido">

            <h1>Gestión de ingresos</h1>

            <section class="tarjeta-formulario">
                <h2>Registrar nuevo ingreso</h2>

                <!-- El "action" apunta al controlador PHP de ingresos -->
                <form id="form-ingreso" method="POST" action="../controllers/IncomeController.php" novalidate>

                    <div class="campo">
                        <label for="nombre">Nombre del ingreso</label>
                        <input type="text" id="nombre" name="nombre" placeholder="Ej. Salario, Freelance" required>
                    </div>

                    <div class="campo">
                        <label for="monto">Monto</label> <!--revisar validación dentro de la etiqueta-->
                        <input type="number" id="monto" name="monto" min="0" step="0.01" placeholder="0.00" required>
                    </div>

                    <div class="campo">
                        <label for="id_categoria">Categoría</label>
                        <select id="id_categoria" name="id_categoria" required>
                            <option value="">Selecciona una categoría</option>

                            <?php while ($categoria= $categorias->fetch_assoc()): ?>
                                <option value="<?php echo $categoria['id_categoria']; ?>">
                                    <?php echo htmlspecialchars($categoria['nombre']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="campo">
                        <label for="fecha-ingreso">Fecha</label>
                        <input type="date" id="fecha-ingreso" name="fecha-ingreso" required>
                    </div>

                    <div class="campo">
                        <label for="descripcion-ingreso">Descripción</label>
                        <textarea id="descripcion-ingreso" name="descripcion" placeholder="Detalle adicional (opcional)"></textarea>
                    </div>

                    <input type="hidden" name="id_estado" value="2">

                    <button type="submit" name="accion" value= "crearIngreso" class="btn-primario">Guardar ingreso</button>
                </form>
            </section>

            <section class="tarjeta-tabla">
                <div class="encabezado-tabla">
                    <h2>Listado de ingresos</h2>
                </div>

                <table class="tabla-datos" id="tabla-ingresos">
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
                        <?php if($ingresos->num_rows === 0): ?>
                            <tr class="fila-vacia">
                                <td colspan="6">Todavía no hay ingresos registrados.</td>
                            </tr>
                        <?php else: ?>
                            <?php while ($ingreso = $ingresos->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <?php 
                                            $fecha = new DateTime($ingreso['fecha']);
                                            echo $fecha->format('d/m/Y'); 
                                        ?>
                                     </td>
                                    <td><?php echo htmlspecialchars($ingreso['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($ingreso['categoria']); ?></td>
                                    <td><?php echo htmlspecialchars($ingreso['descripcion'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td>₡<?php echo number_format((float) $ingreso['monto'], 2); ?></td>
                                    <td>
                                        <form method="POST" action="../controllers/IncomeController.php">
                                            <input type="hidden" name="id_ingreso" value="<?php echo (int) $ingreso['id_ingreso']; ?>">
                                            <button type="submit" name="accion" value="eliminarIngreso" class="btn-eliminar">Eliminar</button>
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