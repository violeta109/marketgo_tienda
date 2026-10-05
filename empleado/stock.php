<?php
session_start();
require_once __DIR__ . '/../php/conexion.php';
if (!isset($_SESSION['usuario_id']) || ($_SESSION['rol'] ?? '') !== 'EMPLEADO') {
    header('Location: ../login.php');
    exit();
}
$nombreUsuario = $_SESSION['nombre'] ?? 'Empleado';
$apellidoUsuario = $_SESSION['apellido'] ?? '';
$res = $conn->query("SELECT p.id,p.codigo,p.nombre,p.precio,p.stock,p.stock_minimo,p.estado,c.nombre AS categoria
 FROM productos p INNER JOIN categorias c ON c.id=p.categoria_id ORDER BY p.nombre");
$productos = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
$totalProductos = count($productos);
$disponibles = 0;
$stockBajo = 0;
$agotados = 0;
foreach ($productos as $producto) {
    $stock = (int)$producto['stock'];
    $min = (int)$producto['stock_minimo'];
    if ($stock <= 0) $agotados++;
    elseif ($stock <= $min) $stockBajo++;
    else $disponibles++;
}
function estadoStock($stock, $minimo = 5)
{
    if ((int)$stock <= 0) return ['texto' => 'Agotado', 'clase' => 'cancelado'];
    if ((int)$stock <= (int)$minimo) return ['texto' => 'Stock bajo', 'clase' => 'pendiente'];
    return ['texto' => 'Disponible', 'clase' => 'entregado'];
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock - MarketGo</title>

    <link rel="stylesheet" href="../css/style.css">


    <link rel="stylesheet" href="../css/page-empleado-stock.css">
</head>

<body>

    <div class="dashboard-layout">

        <!-- SIDEBAR -->
        <aside class="sidebar">

            <div class="sidebar-logo">
                <span class="brand-mark">🛒</span>
                <span>Market<spanclass="mg-inline-1">Go</span></span>
            </div>

            <nav class="sidebar-menu">

                <a href="dashboard.php">
                    <span>📊</span>
                    <span>Inicio</span>
                </a>

                <a href="productos.php">
                    <span>📦</span>
                    <span>Productos</span>
                </a>

                <a href="pedidos.php">
                    <span>🛍️</span>
                    <span>Pedidos</span>
                </a>

                <a href="stock.php" class="active">
                    <span>📋</span>
                    <span>Stock</span>
                </a>

                <a href="perfil.php">
                    <span>👤</span>
                    <span>Perfil</span>
                </a>

            </nav>

            <div class="sidebar-bottom">
                <a href="../php/logout.php">
                    <span>🚪</span>
                    <span>Cerrar sesión</span>
                </a>
            </div>

        </aside>


        <!-- CONTENIDO PRINCIPAL -->
        <main class="main-content">

            <!-- TOPBAR -->
            <header class="topbar">

                <div>
                    <h1>Control de Stock</h1>
                    <p>Consulta el inventario disponible de MarketGo.</p>
                </div>

                <div class="topbar-user">

                    <div class="user-avatar">
                        <?= strtoupper(substr($nombreUsuario, 0, 1)); ?>
                    </div>

                    <div>
                        <strong>
                            <?= htmlspecialchars($nombreUsuario . ' ' . $apellidoUsuario); ?>
                        </strong>

                        <small>Empleado</small>
                    </div>

                </div>

            </header>


            <!-- ESTADÍSTICAS -->
            <section class="stats-grid">

                <div class="stat-card">
                    <div class="stat-icon">📦</div>

                    <div>
                        <span>Total productos</span>
                        <strong><?= $totalProductos; ?></strong>
                    </div>
                </div>


                <div class="stat-card">
                    <div class="stat-icon">✅</div>

                    <div>
                        <span>Disponibles</span>
                        <strong><?= $disponibles; ?></strong>
                    </div>
                </div>


                <div class="stat-card">
                    <div class="stat-icon">⚠️</div>

                    <div>
                        <span>Stock bajo</span>
                        <strong><?= $stockBajo; ?></strong>
                    </div>
                </div>


                <div class="stat-card">
                    <div class="stat-icon">❌</div>

                    <div>
                        <span>Agotados</span>
                        <strong><?= $agotados; ?></strong>
                    </div>
                </div>

            </section>


            <!-- CONTENIDO -->
            <section class="content-card">

                <div class="content-card-header">

                    <div>
                        <h2>Inventario actual</h2>
                        <p>Revisa las cantidades disponibles de cada producto.</p>
                    </div>

                </div>


                <!-- FILTROS -->
                <div class="filters">

                    <div class="search-box">
                        <span>🔎</span>

                        <input
                            type="text"
                            id="buscarStock"
                            placeholder="Buscar producto...">
                    </div>


                    <select id="filtroStock">

                        <option value="">Todos los estados</option>
                        <option value="Disponible">Disponible</option>
                        <option value="Stock bajo">Stock bajo</option>
                        <option value="Agotado">Agotado</option>

                    </select>

                </div>


                <!-- TABLA -->
                <div class="table-container">

                    <table id="tablaStock">

                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Estado</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php if (empty($productos)): ?>

                                <tr>
                                    <td colspan="6">

                                        <div class="empty-stock">

                                            <div class="empty-stock-icon">
                                                📦
                                            </div>

                                            <strong>No hay productos registrados</strong>

                                            <p>
                                                Actualmente no existen productos para mostrar.
                                            </p>

                                        </div>

                                    </td>
                                </tr>

                            <?php else: ?>

                                <?php foreach ($productos as $producto): ?>

                                    <?php
                                    $stock = (int)$producto['stock'];
                                    $estado = estadoStock($stock);

                                    if ($stock <= 0) {
                                        $porcentaje = 0;
                                    } else {
                                        $porcentaje = min(($stock / 30) * 100, 100);
                                    }
                                    ?>

                                    <tr
                                        data-producto="<?= strtolower(htmlspecialchars($producto['nombre'])); ?>"
                                        data-categoria="<?= strtolower(htmlspecialchars($producto['categoria'])); ?>"
                                        data-estado="<?= $estado['texto']; ?>">

                                        <td>
                                            <strong>
                                                <?= htmlspecialchars($producto['codigo']); ?>
                                            </strong>
                                        </td>


                                        <td>
                                            <strong>
                                                <?= htmlspecialchars($producto['nombre']); ?>
                                            </strong>
                                        </td>


                                        <td>
                                            <?= htmlspecialchars($producto['categoria']); ?>
                                        </td>


                                        <td>
                                            S/ <?= number_format((float)$producto['precio'], 2); ?>
                                        </td>


                                        <td>

                                            <div class="stock-info">

                                                <div class="stock-number
                                            <?= $stock <= 0 ? 'out' : ($stock <= 5 ? 'low' : 'ok'); ?>">

                                                    <?= $stock; ?> unidades

                                                </div>

                                                <div class="stock-bar">

                                                    <div
                                                        class="stock-bar-fill <?= $stock <= 0 ? 'out' : ($stock <= 5 ? 'low' : 'ok'); ?>"
                                                        style="width: <?= $porcentaje; ?>%;"></div>

                                                </div>

                                            </div>

                                        </td>


                                        <td>

                                            <span class="status-badge <?= $estado['clase']; ?>">
                                                <?= $estado['texto']; ?>
                                            </span>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </section>

        </main>

    </div>


    <script>
        const buscarStock = document.getElementById('buscarStock');
        const filtroStock = document.getElementById('filtroStock');
        const filas = document.querySelectorAll('#tablaStock tbody tr');

        function filtrarStock() {

            const texto = buscarStock.value.toLowerCase().trim();
            const estadoSeleccionado = filtroStock.value;

            filas.forEach(fila => {

                const producto = fila.dataset.producto || '';
                const categoria = fila.dataset.categoria || '';
                const estado = fila.dataset.estado || '';

                const coincideTexto =
                    producto.includes(texto) ||
                    categoria.includes(texto);

                const coincideEstado =
                    estadoSeleccionado === '' ||
                    estado === estadoSeleccionado;

                fila.style.display =
                    coincideTexto && coincideEstado ?
                    '' :
                    'none';
            });
        }

        buscarStock.addEventListener('input', filtrarStock);
        filtroStock.addEventListener('change', filtrarStock);
    </script>

    <script src="../js/ui.js" defer></script>
</body>

</html>