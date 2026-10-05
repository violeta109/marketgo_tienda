<?php
session_start();
require_once __DIR__ . '/../php/conexion.php';
if (!isset($_SESSION['usuario_id']) || ($_SESSION['rol'] ?? '') !== 'EMPLEADO') {
    header('Location: ../login.php');
    exit();
}
$nombreUsuario = $_SESSION['nombre'] ?? 'Empleado';
$apellidoUsuario = $_SESSION['apellido'] ?? '';
$res = $conn->query("SELECT p.id,p.codigo,p.nombre,p.descripcion,p.precio,p.stock,p.stock_minimo,p.imagen,p.estado,c.nombre AS categoria
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
    elseif ($stock <= $min) {
        $stockBajo++;
        $disponibles++;
    } else {
        $disponibles++;
    }
}
function obtenerEstado($stock, $minimo = 5)
{
    if ((int)$stock <= 0) return ['Agotado', 'cancelado'];
    if ((int)$stock <= (int)$minimo) return ['Stock bajo', 'pendiente'];
    return ['Disponible', 'entregado'];
}
?>


<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Productos - MarketGo</title>

    <link
        rel="stylesheet"
        href="../css/style.css">



    <link rel="stylesheet" href="../css/page-empleado-productos.css">
</head>

<body>

    <div class="dashboard-container">


        <!-- SIDEBAR -->

        <aside class="sidebar">

            <div class="logo">

                <span class="brand-mark">🛒</span>

                <span>
                    Market<spanclass="mg-inline-1">Go</span>
                </span>

            </div>


            <nav class="menu">

                <a href="dashboard.php">
                    <span>🏠</span>
                    Dashboard
                </a>

                <a href="productos.php" class="active">
                    <span>📦</span>
                    Productos
                </a>

                <a href="pedidos.php">
                    <span>🛍️</span>
                    Pedidos
                </a>

                <a href="stock.php">
                    <span>📊</span>
                    Stock
                </a>

                <a href="perfil.php">
                    <span>👤</span>
                    Perfil
                </a>

            </nav>

        </aside>



        <!-- CONTENIDO -->

        <main class="main-content">


            <!-- TOPBAR -->

            <header class="topbar">

                <div>

                    <h1>Productos</h1>

                    <p>
                        Consulta el catálogo de productos
                    </p>

                </div>


                <div class="user-info">

                    <div class="avatar">

                        <?= strtoupper(
                            substr($nombreUsuario, 0, 1)
                        ); ?>

                    </div>

                    <div>

                        <strong>
                            <?= htmlspecialchars(
                                $nombreUsuario . ' ' . $apellidoUsuario
                            ); ?>
                        </strong>

                        <small>
                            Empleado
                        </small>

                    </div>


                    <a
                        href="../php/logout.php"
                        title="Cerrar sesión">
                        🚪
                    </a>

                </div>

            </header>



            <section class="content-area">


                <!-- ESTADÍSTICAS -->

                <div class="stats-grid">


                    <div class="stat-card">

                        <div class="stat-icon">
                            📦
                        </div>

                        <div>

                            <span>
                                Total productos
                            </span>

                            <h2>
                                <?= $totalProductos; ?>
                            </h2>

                        </div>

                    </div>



                    <div class="stat-card">

                        <div class="stat-icon">
                            ✅
                        </div>

                        <div>

                            <span>
                                Disponibles
                            </span>

                            <h2>
                                <?= $disponibles; ?>
                            </h2>

                        </div>

                    </div>



                    <div class="stat-card">

                        <div class="stat-icon">
                            ⚠️
                        </div>

                        <div>

                            <span>
                                Stock bajo
                            </span>

                            <h2>
                                <?= $stockBajo; ?>
                            </h2>

                        </div>

                    </div>



                    <div class="stat-card">

                        <div class="stat-icon">
                            ❌
                        </div>

                        <div>

                            <span>
                                Agotados
                            </span>

                            <h2>
                                <?= $agotados; ?>
                            </h2>

                        </div>

                    </div>


                </div>



                <!-- CABECERA -->

                <div class="section-header">

                    <div>

                        <h2>
                            Catálogo de productos
                        </h2>

                        <p>
                            Información actualizada del inventario
                        </p>

                    </div>

                </div>



                <!-- FILTROS -->

                <div class="card">

                    <div class="filters">


                        <div class="search-box">

                            🔍

                            <input
                                type="text"
                                id="buscarProducto"
                                placeholder="Buscar producto...">

                        </div>


                        <select id="filtroCategoria">

                            <option value="">
                                Todas las categorías
                            </option>

                            <option value="Frutas y verduras">
                                Frutas y verduras
                            </option>

                            <option value="Abarrotes">
                                Abarrotes
                            </option>

                            <option value="Lácteos">
                                Lácteos
                            </option>

                            <option value="Bebidas">
                                Bebidas
                            </option>

                            <option value="Limpieza">
                                Limpieza
                            </option>

                            <option value="Snacks">
                                Snacks
                            </option>

                        </select>


                    </div>

                </div>



                <!-- TABLA -->

                <div class="card table-card">

                    <div class="table-responsive">

                        <table class="data-table">

                            <thead>

                                <tr>

                                    <th>
                                        Código
                                    </th>

                                    <th>
                                        Producto
                                    </th>

                                    <th>
                                        Categoría
                                    </th>

                                    <th>
                                        Precio
                                    </th>

                                    <th>
                                        Stock
                                    </th>

                                    <th>
                                        Estado
                                    </th>

                                    <th>
                                        Acción
                                    </th>

                                </tr>

                            </thead>


                            <tbody id="tablaProductos">


                                <?php if (empty($productos)): ?>

                                    <tr>

                                        <td
                                            colspan="7"
                                            class="mg-inline-2">
                                            No hay productos registrados.
                                        </td>

                                    </tr>


                                <?php else: ?>


                                    <?php foreach ($productos as $producto): ?>

                                        <?php

                                        [$estado, $claseEstado] =
                                            obtenerEstado(
                                                $producto['stock']
                                            );

                                        ?>


                                        <tr
                                            data-nombre="<?= htmlspecialchars(
                                                                strtolower($producto['nombre'])
                                                            ); ?>"
                                            data-categoria="<?= htmlspecialchars(
                                                                strtolower($producto['categoria'])
                                                            ); ?>">


                                            <td>

                                                <strong>
                                                    <?= htmlspecialchars(
                                                        $producto['codigo']
                                                    ); ?>
                                                </strong>

                                            </td>


                                            <td>

                                                <?= htmlspecialchars(
                                                    $producto['nombre']
                                                ); ?>

                                            </td>


                                            <td>

                                                <?= htmlspecialchars(
                                                    $producto['categoria']
                                                ); ?>

                                            </td>


                                            <td>

                                                <strong>
                                                    S/
                                                    <?= number_format(
                                                        (float)$producto['precio'],
                                                        2
                                                    ); ?>
                                                </strong>

                                            </td>


                                            <td>

                                                <?= (int)$producto['stock']; ?>

                                            </td>


                                            <td>

                                                <span
                                                    class="status <?= $claseEstado; ?>">
                                                    <?= $estado; ?>
                                                </span>

                                            </td>


                                            <td>

                                                <button
                                                    type="button"
                                                    class="btn-secondary"
                                                    onclick='verProducto(
                                                <?= json_encode(
                                                    $producto,
                                                    JSON_HEX_TAG |
                                                        JSON_HEX_APOS |
                                                        JSON_HEX_QUOT |
                                                        JSON_HEX_AMP
                                                ); ?>
                                            )'>
                                                    👁️ Ver detalle
                                                </button>

                                            </td>


                                        </tr>


                                    <?php endforeach; ?>


                                <?php endif; ?>


                            </tbody>

                        </table>

                    </div>

                </div>


            </section>

        </main>

    </div>



    <!-- MODAL DETALLE -->

    <div
        id="modalDetalle"
        class="detalle-producto">

        <div class="detalle-contenido">


            <div class="detalle-header">

                <h2>
                    Detalle del producto
                </h2>

                <button
                    type="button"
                    class="cerrar-detalle"
                    onclick="cerrarDetalle()">
                    ×
                </button>

            </div>


            <div class="detalle-grid">


                <div class="detalle-item">

                    <span>
                        Código
                    </span>

                    <strong id="detalleCodigo">
                    </strong>

                </div>


                <div class="detalle-item">

                    <span>
                        Producto
                    </span>

                    <strong id="detalleNombre">
                    </strong>

                </div>


                <div class="detalle-item">

                    <span>
                        Categoría
                    </span>

                    <strong id="detalleCategoria">
                    </strong>

                </div>


                <div class="detalle-item">

                    <span>
                        Precio
                    </span>

                    <strong id="detallePrecio">
                    </strong>

                </div>


                <div class="detalle-item">

                    <span>
                        Stock
                    </span>

                    <strong id="detalleStock">
                    </strong>

                </div>


                <div class="detalle-item">

                    <span>
                        Estado
                    </span>

                    <strong id="detalleEstado">
                    </strong>

                </div>


            </div>


            <div class="detalle-descripcion">

                <strong>
                    Descripción
                </strong>

                <p id="detalleDescripcion">
                    -
                </p>

            </div>


            <br>


            <button
                type="button"
                class="btn-secondary"
                onclick="cerrarDetalle()">
                Cerrar
            </button>


        </div>

    </div>



    <script>
        /* BUSCADOR */

        document
            .getElementById('buscarProducto')
            .addEventListener(
                'input',
                filtrarProductos
            );


        /* FILTRO */

        document
            .getElementById('filtroCategoria')
            .addEventListener(
                'change',
                filtrarProductos
            );


        function filtrarProductos() {

            const texto =
                document
                .getElementById('buscarProducto')
                .value
                .toLowerCase()
                .trim();


            const categoria =
                document
                .getElementById('filtroCategoria')
                .value
                .toLowerCase();


            const filas =
                document.querySelectorAll(
                    '#tablaProductos tr'
                );


            filas.forEach(function(fila) {

                const nombre =
                    fila.dataset.nombre || '';

                const categoriaFila =
                    fila.dataset.categoria || '';


                const coincideNombre =
                    nombre.includes(texto);


                const coincideCategoria =
                    categoria === '' ||
                    categoriaFila === categoria;


                if (
                    coincideNombre &&
                    coincideCategoria
                ) {

                    fila.style.display = '';

                } else {

                    fila.style.display = 'none';

                }

            });

        }



        /* VER DETALLE */

        function verProducto(producto) {

            document.getElementById(
                    'detalleCodigo'
                ).textContent =
                producto.codigo || '-';


            document.getElementById(
                    'detalleNombre'
                ).textContent =
                producto.nombre || '-';


            document.getElementById(
                    'detalleCategoria'
                ).textContent =
                producto.categoria || '-';


            document.getElementById(
                    'detallePrecio'
                ).textContent =
                'S/ ' +
                Number(producto.precio || 0)
                .toFixed(2);


            document.getElementById(
                    'detalleStock'
                ).textContent =
                producto.stock || 0;


            let estado = 'Disponible';


            if (Number(producto.stock) <= 0) {

                estado = 'Agotado';

            } else if (Number(producto.stock) <= 5) {

                estado = 'Stock bajo';

            }


            document.getElementById(
                    'detalleEstado'
                ).textContent =
                estado;


            document.getElementById(
                    'detalleDescripcion'
                ).textContent =
                producto.descripcion || 'Sin descripción';


            document
                .getElementById('modalDetalle')
                .classList.add('activo');

        }



        /* CERRAR MODAL */

        function cerrarDetalle() {

            document
                .getElementById('modalDetalle')
                .classList.remove('activo');

        }



        /* CERRAR AL HACER CLIC FUERA */

        document
            .getElementById('modalDetalle')
            .addEventListener(
                'click',
                function(event) {

                    if (event.target === this) {

                        cerrarDetalle();

                    }

                }
            );
    </script>

    <script src="../js/ui.js" defer></script>
</body>

</html>