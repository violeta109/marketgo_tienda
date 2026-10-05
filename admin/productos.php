<?php
session_start();
require_once __DIR__ . '/../php/conexion.php';

if (!isset($_SESSION['usuario_id']) || ($_SESSION['rol'] ?? '') !== 'ADMIN') {
    header('Location: ../login.php');
    exit();
}

$nombreUsuario = $_SESSION['nombre'] ?? 'Administrador';
$apellidoUsuario = $_SESSION['apellido'] ?? '';

$mensajeExito = $_SESSION['producto_exito'] ?? '';
$mensajeError = $_SESSION['producto_error'] ?? '';
unset($_SESSION['producto_exito'], $_SESSION['producto_error']);

$resultado = $conn->query("SELECT p.id, p.codigo, p.nombre, p.descripcion, p.categoria_id,
    c.nombre AS categoria, p.precio, p.stock, p.stock_minimo, p.imagen, p.estado
    FROM productos p INNER JOIN categorias c ON c.id = p.categoria_id
    ORDER BY p.id DESC");
$productos = $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];

$resCategorias = $conn->query("SELECT id, codigo, nombre FROM categorias WHERE estado='ACTIVA' ORDER BY nombre");
$categoriasActivas = $resCategorias ? $resCategorias->fetch_all(MYSQLI_ASSOC) : [];

$totalProductos = count($productos);
$disponibles = 0;
$stockBajo = 0;
$agotados = 0;

foreach ($productos as $producto) {
    $stock = (int)($producto['stock'] ?? 0);
    $minimo = (int)($producto['stock_minimo'] ?? 5);
    if ($stock <= 0) $agotados++;
    elseif ($stock <= $minimo) {
        $stockBajo++;
        $disponibles++;
    } else $disponibles++;
}

function estadoProducto($stock, $minimo = 5)
{
    $stock = (int)$stock;
    $minimo = (int)$minimo;
    if ($stock <= 0) return ['Agotado', 'cancelado'];
    if ($stock <= $minimo) return ['Stock bajo', 'pendiente'];
    return ['Disponible', 'entregado'];
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - MarketGo</title>
    <link rel="stylesheet" href="../css/style.css">


    <link rel="stylesheet" href="../css/page-admin-productos.css">
</head>

<body>

    <div class="dashboard-container">

        <!-- SIDEBAR -->
        <aside class="sidebar">

            <div class="logo">
                <span class="brand-mark">🛒</span>
                <span>Market<spanclass="mg-inline-1">Go</span></span>
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

                <a href="categorias.php">
                    <span>🏷️</span>
                    Categorías
                </a>

                <a href="proveedores.php">
                    <span>🏢</span>
                    Proveedores
                </a>

                <a href="usuarios.php">
                    <span>👥</span>
                    Usuarios
                </a>

                <a href="pedidos.php">
                    <span>🛍️</span>
                    Pedidos
                </a>

                <a href="reportes.php">
                    <span>📊</span>
                    Reportes
                </a>

            </nav>

        </aside>


        <!-- CONTENIDO PRINCIPAL -->
        <main class="main-content">

            <!-- TOPBAR -->
            <header class="topbar">

                <div>
                    <h1>Productos</h1>
                    <p>Administra los productos de MarketGo</p>
                </div>

                <div class="user-info">

                    <div class="avatar">
                        <?= strtoupper(substr($nombreUsuario, 0, 1)); ?>
                    </div>

                    <div>
                        <strong><?= htmlspecialchars($nombreUsuario . ' ' . $apellidoUsuario); ?></strong>
                        <small>Administrador</small>
                    </div>

                    <a href="../php/logout.php" title="Cerrar sesión">🚪</a>

                </div>

            </header>


            <section class="content-area">

                <?php if (isset($_SESSION['producto_exito'])): ?>

                    <div class="mensaje-producto mensaje-exito">
                        <?= htmlspecialchars($_SESSION['producto_exito']); ?>
                    </div>

                    <?php unset($_SESSION['producto_exito']); ?>

                <?php endif; ?>


                <?php if (isset($_SESSION['producto_error'])): ?>

                    <div class="mensaje-producto mensaje-error">
                        <?= htmlspecialchars($_SESSION['producto_error']); ?>
                    </div>

                    <?php unset($_SESSION['producto_error']); ?>

                <?php endif; ?>


                <!-- ESTADÍSTICAS -->
                <div class="stats-grid">

                    <div class="stat-card">
                        <div class="stat-icon">📦</div>
                        <div>
                            <span>Total productos</span>
                            <h2><?= $totalProductos; ?></h2>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">✅</div>
                        <div>
                            <span>Disponibles</span>
                            <h2><?= $disponibles; ?></h2>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">⚠️</div>
                        <div>
                            <span>Stock bajo</span>
                            <h2><?= $stockBajo; ?></h2>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">❌</div>
                        <div>
                            <span>Agotados</span>
                            <h2><?= $agotados; ?></h2>
                        </div>
                    </div>

                </div>


                <!-- CABECERA -->
                <div class="section-header">

                    <div>
                        <h2>Lista de productos</h2>
                        <p>Gestiona el catálogo de productos</p>
                    </div>

                    <button class="btn-primary" onclick="mostrarFormulario()">
                        + Nuevo producto
                    </button>

                </div>


                <!-- FORMULARIO NUEVO PRODUCTO -->
                <div id="formularioProducto" class="formulario-producto">

                    <div class="card">

                        <h3>Registrar nuevo producto</h3>

                        <form action="../php/agregar_producto.php" method="POST" enctype="multipart/form-data">

                            <div class="form-grid">

                                <div class="campo-producto">
                                    <label>Nombre del producto</label>
                                    <input
                                        type="text"
                                        name="nombre"
                                        placeholder="Ej. Arroz Costeño"
                                        required>
                                </div>

                                <div class="campo-producto">
                                    <label>Categoría</label>

                                    <select name="categoria" required>
                                        <option value="">Seleccionar categoría</option>
                                        <?php foreach ($categoriasActivas as $categoria): ?>
                                            <option value="<?= htmlspecialchars($categoria['codigo']); ?>">
                                                <?= htmlspecialchars($categoria['nombre']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="campo-producto">
                                    <label>Precio (S/)</label>
                                    <input
                                        type="number"
                                        name="precio"
                                        step="0.01"
                                        min="0"
                                        placeholder="0.00"
                                        required>
                                </div>

                                <div class="campo-producto">
                                    <label>Stock</label>
                                    <input
                                        type="number"
                                        name="stock"
                                        min="0"
                                        placeholder="0"
                                        required>
                                </div>

                                <div class="campo-producto campo-completo">
                                    <label for="imagenProducto">Fotografía del producto</label>
                                    <input id="imagenProducto" type="file" name="imagen" accept="image/jpeg,image/png,image/webp" data-image-preview="previewProducto">
                                    <small>JPG, PNG o WEBP. Máximo 5 MB. Usa una foto frontal del producto; se mostrará completa, sin deformarse.</small>
                                    <img id="previewProducto" class="product-upload-preview" alt="Vista previa de la fotografía" hidden>
                                </div>

                                <div class="campo-producto campo-completo">
                                    <label>Descripción</label>
                                    <textarea
                                        name="descripcion"
                                        placeholder="Descripción del producto..."></textarea>
                                </div>

                            </div>

                            <br>

                            <button type="submit" class="btn-primary">
                                Guardar producto
                            </button>

                            <button
                                type="button"
                                class="btn-secondary"
                                onclick="ocultarFormulario()">
                                Cancelar
                            </button>

                        </form>

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
                            <option value="">Todas las categorías</option>
                            <?php foreach ($categoriasActivas as $categoria): ?>
                                <option value="<?= htmlspecialchars(strtolower($categoria['nombre'])); ?>"><?= htmlspecialchars($categoria['nombre']); ?></option>
                            <?php endforeach; ?>
                        </select>

                    </div>

                </div>


                <!-- TABLA -->
                <div class="card table-card">

                    <div class="table-responsive">

                        <table class="data-table">

                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Imagen</th>
                                    <th>Producto</th>
                                    <th>Categoría</th>
                                    <th>Precio</th>
                                    <th>Stock</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>

                            <tbody id="tablaProductos">

                                <?php if (empty($productos)): ?>

                                    <tr>
                                        <td colspan="8" class="mg-inline-2">
                                            No hay productos registrados.
                                        </td>
                                    </tr>

                                <?php else: ?>

                                    <?php foreach ($productos as $producto): ?>

                                        <?php
                                        [$estado, $claseEstado] = estadoProducto($producto['stock']);
                                        ?>

                                        <tr
                                            data-nombre="<?= htmlspecialchars(strtolower($producto['nombre'])); ?>"
                                            data-categoria="<?= htmlspecialchars(strtolower($producto['categoria'])); ?>">

                                            <td>
                                                <strong>
                                                    <?= htmlspecialchars($producto['codigo']); ?>
                                                </strong>
                                            </td>

                                            <td>
                                                <?php if (!empty($producto['imagen'])): ?>
                                                    <img class="product-table-thumb" src="../<?= htmlspecialchars(ltrim($producto['imagen'], '/')); ?>" alt="<?= htmlspecialchars($producto['nombre']); ?>" loading="lazy">
                                                <?php else: ?>
                                                    <span class="product-no-image">Sin foto</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?= htmlspecialchars($producto['nombre']); ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($producto['categoria']); ?>
                                            </td>

                                            <td>
                                                <strong>
                                                    S/ <?= number_format((float)$producto['precio'], 2); ?>
                                                </strong>
                                            </td>

                                            <td>
                                                <?= (int)$producto['stock']; ?>
                                            </td>

                                            <td>
                                                <span class="status <?= $claseEstado; ?>">
                                                    <?= $estado; ?>
                                                </span>
                                            </td>

                                            <td>

                                                <div class="acciones-producto">

                                                    <button
                                                        type="button"
                                                        class="btn-editar"
                                                        onclick='editarProducto(<?= json_encode($producto, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>)'>
                                                        ✏️ Editar
                                                    </button>

                                                    <form
                                                        action="../php/eliminar_producto.php"
                                                        method="POST"
                                                        onsubmit="return confirmarEliminar('<?= htmlspecialchars($producto['nombre'], ENT_QUOTES); ?>')">

                                                        <input
                                                            type="hidden"
                                                            name="codigo"
                                                            value="<?= htmlspecialchars($producto['codigo']); ?>">

                                                        <button
                                                            type="submit"
                                                            class="btn-eliminar">
                                                            🗑️ Eliminar
                                                        </button>

                                                    </form>

                                                </div>

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


    <!-- MODAL EDITAR -->
    <div id="modalEditar" class="modal-producto">

        <div class="modal-contenido">

            <div class="modal-header">

                <h2>Editar producto</h2>

                <button
                    type="button"
                    class="cerrar-modal"
                    onclick="cerrarModal()">
                    ×
                </button>

            </div>


            <form action="../php/actualizar_producto.php" method="POST" enctype="multipart/form-data">

                <input type="hidden" name="codigo" id="editarCodigo">

                <div class="form-grid">

                    <div class="campo-producto">
                        <label>Nombre</label>
                        <input
                            type="text"
                            name="nombre"
                            id="editarNombre"
                            required>
                    </div>

                    <div class="campo-producto">
                        <label>Categoría</label>

                        <select name="categoria" id="editarCategoria" required>
                            <?php foreach ($categoriasActivas as $categoria): ?>
                                <option value="<?= htmlspecialchars($categoria['codigo']); ?>"><?= htmlspecialchars($categoria['nombre']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="campo-producto">
                        <label>Precio (S/)</label>
                        <input
                            type="number"
                            name="precio"
                            id="editarPrecio"
                            step="0.01"
                            min="0"
                            required>
                    </div>

                    <div class="campo-producto">
                        <label>Stock</label>
                        <input
                            type="number"
                            name="stock"
                            id="editarStock"
                            min="0"
                            required>
                    </div>

                    <div class="campo-producto campo-completo">
                        <label for="editarImagen">Cambiar fotografía (opcional)</label>
                        <input id="editarImagen" type="file" name="imagen" accept="image/jpeg,image/png,image/webp" data-image-preview="previewEditarProducto">
                        <img id="previewEditarProducto" class="product-upload-preview" alt="Vista previa de la fotografía" hidden>
                    </div>

                    <div class="campo-producto campo-completo">
                        <label>Descripción</label>

                        <textarea
                            name="descripcion"
                            id="editarDescripcion"></textarea>
                    </div>

                </div>

                <br>

                <button type="submit" class="btn-primary">
                    Guardar cambios
                </button>

                <button
                    type="button"
                    class="btn-secondary"
                    onclick="cerrarModal()">
                    Cancelar
                </button>

            </form>

        </div>

    </div>


    <script>
        function mostrarFormulario() {
            document
                .getElementById('formularioProducto')
                .classList.add('activo');

            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function ocultarFormulario() {
            document
                .getElementById('formularioProducto')
                .classList.remove('activo');
        }


        function editarProducto(producto) {

            document.getElementById('editarCodigo').value =
                producto.codigo || '';

            document.getElementById('editarNombre').value =
                producto.nombre || '';

            document.getElementById('editarCategoria').value =
                producto.categoria || '';

            document.getElementById('editarPrecio').value =
                producto.precio || 0;

            document.getElementById('editarStock').value =
                producto.stock || 0;

            document.getElementById('editarDescripcion').value =
                producto.descripcion || '';

            document
                .getElementById('modalEditar')
                .classList.add('activo');
        }


        function cerrarModal() {

            document
                .getElementById('modalEditar')
                .classList.remove('activo');
        }


        function confirmarEliminar(nombre) {

            return confirm(
                '¿Seguro que deseas eliminar el producto "' +
                nombre +
                '"?'
            );
        }


        /* BUSCADOR */
        document
            .getElementById('buscarProducto')
            .addEventListener('input', filtrarProductos);


        /* FILTRO CATEGORÍA */
        document
            .getElementById('filtroCategoria')
            .addEventListener('change', filtrarProductos);


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
                document.querySelectorAll('#tablaProductos tr');

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

                if (coincideNombre && coincideCategoria) {
                    fila.style.display = '';
                } else {
                    fila.style.display = 'none';
                }

            });
        }

        document
            .getElementById('modalEditar')
            .addEventListener('click', function(event) {

                if (event.target === this) {
                    cerrarModal();
                }

            });
    </script>

    <script src="../js/productos.js" defer></script>
    <script src="../js/ui.js" defer></script>
</body>

</html>