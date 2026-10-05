```php
<?php
session_start();

require_once __DIR__ . '/../php/conexion.php';

/* =========================================================
   VALIDAR SESIÓN
   ========================================================= */

if (
    !isset($_SESSION['usuario_id']) ||
    ($_SESSION['rol'] ?? '') !== 'CLIENTE'
) {
    header('Location: ../login.php');
    exit();
}

/* =========================================================
   DATOS DEL USUARIO
   ========================================================= */

$nombreUsuario = $_SESSION['nombre'] ?? 'Cliente';
$apellidoUsuario = $_SESSION['apellido'] ?? '';

$nombreCompleto = trim($nombreUsuario . ' ' . $apellidoUsuario);

if ($nombreCompleto === '') {
    $nombreCompleto = 'Cliente';
}

$inicial = strtoupper(substr($nombreUsuario, 0, 1));

/* =========================================================
   FILTROS
   ========================================================= */

$busqueda = trim($_GET['buscar'] ?? '');
$categoriaSeleccionada = trim($_GET['categoria'] ?? '');

/* =========================================================
   FUNCIONES
   ========================================================= */

function escapar($texto)
{
    return htmlspecialchars(
        (string)$texto,
        ENT_QUOTES,
        'UTF-8'
    );
}

function estadoProducto($stock)
{
    $stock = (int)$stock;

    if ($stock <= 0) {
        return [
            'texto' => 'Agotado',
            'clase' => 'cancelado'
        ];
    }

    if ($stock <= 5) {
        return [
            'texto' => 'Stock bajo',
            'clase' => 'pendiente'
        ];
    }

    return [
        'texto' => 'Disponible',
        'clase' => 'entregado'
    ];
}

/* =========================================================
   OBTENER PRODUCTOS DESDE MYSQL
   ========================================================= */

$sqlProductos = "
    SELECT
        p.id,
        p.codigo,
        p.nombre,
        p.descripcion,
        p.precio,
        p.stock,
        p.imagen,
        c.nombre AS categoria
    FROM productos p
    INNER JOIN categorias c
        ON c.id = p.categoria_id
    WHERE p.estado = 'ACTIVO'
      AND c.estado = 'ACTIVO'
    ORDER BY p.nombre ASC
";

$res = $conn->query($sqlProductos);

$productos = [];

if ($res) {
    $productos = $res->fetch_all(MYSQLI_ASSOC);
}

/* =========================================================
   OBTENER CATEGORÍAS DESDE MYSQL
   ========================================================= */

$sqlCategorias = "
    SELECT
        id,
        nombre
    FROM categorias
    WHERE estado = 'ACTIVO'
    ORDER BY nombre ASC
";

$resCat = $conn->query($sqlCategorias);

$categorias = [];

if ($resCat) {
    $categorias = $resCat->fetch_all(MYSQLI_ASSOC);
}

/* =========================================================
   FILTRAR PRODUCTOS
   ========================================================= */

$productosFiltrados = array_filter(
    $productos,
    function ($producto) use (
        $busqueda,
        $categoriaSeleccionada
    ) {

        $cumpleBusqueda = true;
        $cumpleCategoria = true;

        /* Buscar por nombre, código o categoría */
        if ($busqueda !== '') {

            $texto = strtolower(
                ($producto['nombre'] ?? '') . ' ' .
                ($producto['codigo'] ?? '') . ' ' .
                ($producto['categoria'] ?? '')
            );

            $cumpleBusqueda =
                strpos(
                    $texto,
                    strtolower($busqueda)
                ) !== false;
        }

        /* Filtrar por categoría */
        if ($categoriaSeleccionada !== '') {

            $cumpleCategoria =
                ($producto['categoria'] ?? '') ===
                $categoriaSeleccionada;
        }

        return $cumpleBusqueda && $cumpleCategoria;
    }
);

$productosFiltrados = array_values($productosFiltrados);

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Productos | MarketGo</title>

    <link
        rel="stylesheet"
        href="../css/style.css"
    >

<link rel="stylesheet" href="../css/page-cliente-productos.css">
</head>

<body>

<div class="app-container">

    <!-- =====================================================
         SIDEBAR
         ===================================================== -->

    <aside class="sidebar">

        <div class="brand">

            <span class="brand-mark">🛒</span>

            <span>
                Market<spanclass="mg-inline-1">Go</span>
            </span>

        </div>

        <nav class="sidebar-menu">

            <a href="dashboard.php">
                <span>📊</span>
                Inicio
            </a>

            <a href="categorias.php">
                <span>🗂️</span>
                Categorías
            </a>

            <a
                href="productos.php"
                class="active"
            >
                <span>📦</span>
                Productos
            </a>

            <a href="ofertas.php">
                <span>🏷️</span>
                Ofertas
            </a>

            <a href="carrito.php">
                <span>🛒</span>
                Mi carrito
            </a>

            <a href="pedidos_c.php">
                <span>📋</span>
                Mis pedidos
            </a>

            <a href="perfil.php">
                <span>👤</span>
                Mi perfil
            </a>

        </nav>

        <div class="sidebar-bottom">

            <a href="../php/logout.php">

                <span>🚪</span>

                Cerrar sesión

            </a>

        </div>

    </aside>

    <!-- =====================================================
         CONTENIDO PRINCIPAL
         ===================================================== -->

    <main class="main-content">

        <!-- =================================================
             TOPBAR
             ================================================= -->

        <header class="topbar">

            <div>

                <h1>
                    Productos
                </h1>

                <p class="page-subtitle">
                    Encuentra los productos que necesitas
                </p>

            </div>

            <div class="topbar-user">

                <div class="user-avatar">

                    <?= escapar($inicial) ?>

                </div>

                <div>

                    <strong>
                        <?= escapar($nombreCompleto) ?>
                    </strong>

                    <small>
                        Cliente
                    </small>

                </div>

            </div>

        </header>

        <!-- =================================================
             SECCIÓN PRODUCTOS
             ================================================= -->

        <section class="content-section">

            <div class="section-header">

                <div>

                    <h2>
                        Catálogo de productos
                    </h2>

                    <p>
                        Selecciona un producto y agrégalo a tu carrito.
                    </p>

                </div>

                <a
                    href="carrito.php"
                    class="btn-primary"
                >
                    🛒 Ver carrito
                </a>

            </div>

            <!-- =================================================
                 FILTROS
                 ================================================= -->

            <form
                method="GET"
                class="filters-container"
            >

                <div class="form-group">

                    <label for="buscar">
                        Buscar producto
                    </label>

                    <input
                        type="text"
                        id="buscar"
                        name="buscar"
                        placeholder="Nombre o código..."
                        value="<?= escapar($busqueda) ?>"
                    >

                </div>

                <div class="form-group">

                    <label for="categoria">
                        Categoría
                    </label>

                    <select
                        id="categoria"
                        name="categoria"
                    >

                        <option value="">
                            Todas las categorías
                        </option>

                        <?php foreach ($categorias as $categoria): ?>

                            <option
                                value="<?= escapar($categoria['nombre']) ?>"
                                <?= $categoriaSeleccionada === $categoria['nombre']
                                    ? 'selected'
                                    : '' ?>
                            >

                                <?= escapar($categoria['nombre']) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="filter-actions">

                    <button
                        type="submit"
                        class="btn-primary"
                    >
                        🔍 Buscar
                    </button>

                    <a
                        href="productos.php"
                        class="btn-secondary"
                    >
                        Limpiar
                    </a>

                </div>

            </form>

            <!-- =================================================
                 PRODUCTOS
                 ================================================= -->

            <?php if (count($productosFiltrados) > 0): ?>

                <div class="products-grid">

                    <?php foreach ($productosFiltrados as $producto): ?>

                        <?php

                        $estado = estadoProducto(
                            $producto['stock']
                        );

                        $disponible =
                            (int)$producto['stock'] > 0;

                        ?>

                        <div class="product-card">

                            <div class="product-image">
                                <?php if (!empty($producto['imagen'])): ?>
                                    <img src="../<?= escapar(ltrim($producto['imagen'], '/')) ?>" alt="<?= escapar($producto['nombre']) ?>" loading="lazy">
                                <?php else: ?>
                                    <div class="product-image-placeholder" aria-label="Fotografía no disponible">
                                        <span>📦</span><small>Imagen no disponible</small>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="product-card-content">

                                <span class="product-category">

                                    <?= escapar(
                                        $producto['categoria']
                                    ) ?>

                                </span>

                                <h3>

                                    <?= escapar(
                                        $producto['nombre']
                                    ) ?>

                                </h3>

                                <p class="product-description">

                                    <?= escapar(
                                        $producto['descripcion']
                                    ) ?>

                                </p>

                                <div class="product-price">

                                    S/
                                    <?= number_format(
                                        (float)$producto['precio'],
                                        2
                                    ) ?>

                                </div>

                                <div class="product-stock">

                                    Stock disponible:

                                    <strong>
                                        <?= (int)$producto['stock'] ?>
                                    </strong>

                                </div>

                                <span
                                    class="status-badge <?= escapar(
                                        $estado['clase']
                                    ) ?>"
                                >

                                    <?= escapar(
                                        $estado['texto']
                                    ) ?>

                                </span>

                                <button
                                    type="button"
                                    class="btn-primary btn-cart"
                                    <?= !$disponible
                                        ? 'disabled'
                                        : '' ?>
                                    onclick='agregarAlCarrito(
                                        <?= json_encode(
                                            $producto['nombre'],
                                            JSON_HEX_APOS |
                                            JSON_HEX_QUOT
                                        ) ?>,
                                        <?= json_encode(
                                            (float)$producto['precio']
                                        ) ?>,
                                        <?= json_encode(
                                            $producto['codigo']
                                        ) ?>
                                    )'
                                >

                                    <?= $disponible
                                        ? '🛒 Agregar al carrito'
                                        : 'Producto agotado' ?>

                                </button>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

            <?php else: ?>

                <div class="empty-state">

                    <div class="empty-state-icon">
                        🔎
                    </div>

                    <h3>
                        No se encontraron productos
                    </h3>

                    <p>
                        Prueba con otro nombre o selecciona otra categoría.
                    </p>

                    <a
                        href="productos.php"
                        class="btn-primary"
                    >
                        Ver todos los productos
                    </a>

                </div>

            <?php endif; ?>

        </section>

    </main>

</div>

<script>

/*
 * ============================================================
 * AGREGAR PRODUCTO AL CARRITO
 * ============================================================
 *
 * Por ahora se conserva el funcionamiento visual del carrito.
 * El siguiente paso puede conectarlo directamente a MySQL
 * mediante una tabla carrito/detalle_carrito.
 */

function agregarAlCarrito(nombre, precio, codigo) {

    let carrito = JSON.parse(
        localStorage.getItem('marketgo_carrito') || '[]'
    );

    const productoExistente = carrito.find(
        producto => producto.codigo === codigo
    );

    if (productoExistente) {

        productoExistente.cantidad += 1;

    } else {

        carrito.push({

            codigo: codigo,
            nombre: nombre,
            precio: precio,
            cantidad: 1

        });

    }

    localStorage.setItem(
        'marketgo_carrito',
        JSON.stringify(carrito)
    );

    alert(
        'Producto agregado al carrito correctamente.'
    );
}

</script>

<script src="../js/ui.js" defer></script>
</body>

</html>
```
