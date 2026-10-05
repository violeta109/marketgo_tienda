<?php
session_start();
require_once __DIR__ . '/../php/conexion.php';
if (!isset($_SESSION['usuario_id']) || ($_SESSION['rol'] ?? '') !== 'CLIENTE') { header('Location: ../login.php'); exit(); }
function escapar($texto){return htmlspecialchars((string)$texto,ENT_QUOTES,'UTF-8');}
$nombreUsuario=$_SESSION['nombre']??'Cliente';$apellidoUsuario=$_SESSION['apellido']??'';$nombreCompleto=trim($nombreUsuario.' '.$apellidoUsuario);$inicial=strtoupper(substr($nombreUsuario,0,1));
$sql="SELECT p.id,p.codigo,p.nombre,p.descripcion,p.precio,p.stock,p.imagen,c.nombre AS categoria,o.precio_oferta,o.fecha_inicio,o.fecha_fin
 FROM ofertas o INNER JOIN productos p ON p.id=o.producto_id INNER JOIN categorias c ON c.id=p.categoria_id
 WHERE o.estado='ACTIVA' AND p.estado='ACTIVO' AND c.estado='ACTIVA'
 AND o.fecha_inicio<=NOW() AND (o.fecha_fin IS NULL OR o.fecha_fin>=NOW()) AND p.stock>0 ORDER BY o.fecha_fin IS NULL,o.fecha_fin,p.nombre";
$res=$conn->query($sql);$productosOferta=$res?$res->fetch_all(MYSQLI_ASSOC):[];
foreach($productosOferta as &$producto){$producto['precio_anterior']=(float)$producto['precio'];$producto['precio_oferta']=(float)$producto['precio_oferta'];$producto['descuento']=$producto['precio_anterior']>0?round((($producto['precio_anterior']-$producto['precio_oferta'])/$producto['precio_anterior'])*100):0;}unset($producto);
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ofertas | MarketGo</title>

    <link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/page-cliente-ofertas.css">
</head>

<body>

<div class="app-container">

    <!-- SIDEBAR -->
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

            <a href="productos.php">
                <span>📦</span>
                Productos
            </a>

            <a href="ofertas.php" class="active">
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


    <!-- CONTENIDO -->
    <main class="main-content">

        <!-- TOPBAR -->
        <header class="topbar">

            <div>
                <h1>Ofertas</h1>

                <p class="page-subtitle">
                    Aprovecha nuestros productos con descuento
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


        <!-- CONTENIDO -->
        <section class="content-section">

            <div class="section-header">

                <div>
                    <h2>🔥 Ofertas disponibles</h2>

                    <p>
                        Productos seleccionados con precios especiales.
                    </p>
                </div>

                <a href="carrito.php" class="btn-primary">
                    🛒 Ver carrito
                </a>

            </div>


            <?php if (count($productosOferta) > 0): ?>

                <div class="products-grid">

                    <?php foreach ($productosOferta as $producto): ?>

                        <?php
                        $iconos = [
                            'Frutas y verduras' => '🥦',
                            'Abarrotes' => '🛒',
                            'Lácteos' => '🥛',
                            'Bebidas' => '🥤',
                            'Limpieza' => '🧽',
                            'Snacks' => '🍪'
                        ];

                        $icono = $iconos[$producto['categoria']] ?? '📦';
                        ?>

                        <div class="product-card">

                            <!-- DESCUENTO -->
                            <div class="offer-badge">
                                -<?= (int)$producto['descuento'] ?>%
                            </div>


                            <!-- IMAGEN / ICONO -->
                            <div class="product-image">
                                <?= $icono ?>
                            </div>


                            <div class="product-card-content">

                                <span class="product-category">
                                    <?= escapar($producto['categoria']) ?>
                                </span>


                                <h3>
                                    <?= escapar($producto['nombre']) ?>
                                </h3>


                                <p class="product-description">
                                    <?= escapar($producto['descripcion']) ?>
                                </p>


                                <!-- PRECIOS -->
                                <div class="offer-prices">

                                    <span class="old-price">
                                        S/
                                        <?= number_format(
                                            $producto['precio_anterior'],
                                            2
                                        ) ?>
                                    </span>

                                    <span class="offer-price">
                                        S/
                                        <?= number_format(
                                            $producto['precio_oferta'],
                                            2
                                        ) ?>
                                    </span>

                                </div>


                                <div class="product-stock">

                                    Stock disponible:
                                    <strong>
                                        <?= (int)$producto['stock'] ?>
                                    </strong>

                                </div>


                                <!-- BOTÓN -->
                                <button
                                    type="button"
                                    class="btn-primary btn-cart"
                                    onclick='agregarAlCarrito(
                                        <?= json_encode($producto['nombre']) ?>,
                                        <?= json_encode((float)$producto['precio_oferta']) ?>,
                                        <?= json_encode($producto['codigo']) ?>
                                    )'
                                >
                                    🛒 Agregar al carrito
                                </button>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

            <?php else: ?>

                <div class="empty-state">

                    <div class="empty-state-icon">
                        🏷️
                    </div>

                    <h3>
                        No hay ofertas disponibles
                    </h3>

                    <p>
                        Actualmente no hay productos con descuento.
                    </p>

                    <a href="productos.php" class="btn-primary">
                        Ver productos
                    </a>

                </div>

            <?php endif; ?>

        </section>

    </main>

</div>


<script>

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

    alert('Producto agregado al carrito correctamente.');
}

</script>

<script src="../js/ui.js" defer></script>
</body>
</html>
