<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'CLIENTE') {
    header('Location: ../login.php');
    exit();
}

$nombreUsuario = $_SESSION['nombre'] ?? 'Cliente';
$apellidoUsuario = $_SESSION['apellido'] ?? '';

$categorias = [
    [
        'nombre' => 'Frutas y verduras',
        'icono' => '🥦',
        'descripcion' => 'Productos frescos y saludables.',
        'clase' => 'verde'
    ],
    [
        'nombre' => 'Abarrotes',
        'icono' => '🛒',
        'descripcion' => 'Productos básicos para tu hogar.',
        'clase' => 'azul'
    ],
    [
        'nombre' => 'Lácteos',
        'icono' => '🥛',
        'descripcion' => 'Leche, yogures y otros derivados.',
        'clase' => 'amarillo'
    ],
    [
        'nombre' => 'Bebidas',
        'icono' => '🥤',
        'descripcion' => 'Bebidas para todos los gustos.',
        'clase' => 'rojo'
    ],
    [
        'nombre' => 'Limpieza',
        'icono' => '🧽',
        'descripcion' => 'Productos para mantener tu hogar limpio.',
        'clase' => 'morado'
    ],
    [
        'nombre' => 'Snacks',
        'icono' => '🍪',
        'descripcion' => 'Galletas, dulces y productos para compartir.',
        'clase' => 'naranja'
    ]
];
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Categorías - MarketGo</title>

    <link rel="stylesheet" href="../css/style.css">

    

<link rel="stylesheet" href="../css/page-cliente-categorias.css">
</head>


<body>

<div class="dashboard-layout">


    <!-- SIDEBAR -->

    <aside class="sidebar">

        <div class="sidebar-logo">

            <span class="brand-mark">🛒</span>

            <span>
                Market<spanclass="mg-inline-1">Go</span>
            </span>

        </div>


        <nav class="sidebar-menu">

            <a href="dashboard.php">
                <span>📊</span>
                <span>Inicio</span>
            </a>

            <a href="categorias.php" class="active">
                <span>🗂️</span>
                <span>Categorías</span>
            </a>

            <a href="productos.php">
                <span>📦</span>
                <span>Productos</span>
            </a>

            <a href="ofertas.php">
                <span>🏷️</span>
                <span>Ofertas</span>
            </a>

            <a href="carrito.php">
                <span>🛒</span>
                <span>Carrito</span>
            </a>

            <a href="pedidos_c.php">
                <span>📋</span>
                <span>Mis pedidos</span>
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

        <header class="topbar">

            <div>

                <h1>Categorías</h1>

                <p>
                    Explora nuestros productos por categoría.
                </p>

            </div>


            <div class="topbar-user">

                <div class="user-avatar">

                    <?= strtoupper(substr($nombreUsuario, 0, 1)); ?>

                </div>


                <div>

                    <strong>
                        <?= htmlspecialchars($nombreUsuario . ' ' . $apellidoUsuario); ?>
                    </strong>

                    <small>
                        Cliente
                    </small>

                </div>

            </div>

        </header>



        <!-- CATEGORÍAS -->

        <section class="content-card">

            <div class="content-card-header">

                <div>

                    <h2>
                        Todas las categorías
                    </h2>

                    <p>
                        Selecciona una categoría para ver sus productos.
                    </p>

                </div>

            </div>


            <div class="categories-grid">


                <?php foreach ($categorias as $categoria): ?>

                    <a
                        href="productos.php?categoria=<?= urlencode($categoria['nombre']); ?>"
                        class="category-card"
                    >

                        <div class="category-icon <?= $categoria['clase']; ?>">

                            <?= $categoria['icono']; ?>

                        </div>


                        <h3>

                            <?= htmlspecialchars($categoria['nombre']); ?>

                        </h3>


                        <p>

                            <?= htmlspecialchars($categoria['descripcion']); ?>

                        </p>


                        <span class="category-link">

                            Ver productos →

                        </span>

                    </a>

                <?php endforeach; ?>


            </div>

        </section>


    </main>

</div>

<script src="../js/ui.js" defer></script>
</body>

</html>