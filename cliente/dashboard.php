<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.php');
    exit();
}

if ($_SESSION['rol'] !== 'CLIENTE') {
    header('Location: ../login.php');
    exit();
}

$nombre = $_SESSION['nombre'] ?? 'Cliente';
$apellido = $_SESSION['apellido'] ?? '';

$nombreCompleto = trim($nombre . ' ' . $apellido);

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        MarketGo - Cliente
    </title>

    <link
        rel="stylesheet"
        href="../css/style.css"
    >

<link rel="stylesheet" href="../css/page-cliente-dashboard.css">
</head>

<body>

<div class="app-shell">



    <aside class="sidebar">


        <div class="side-logo">

            <span class="brand-mark">
                🛒
            </span>

            <span>
                Market<spanclass="mg-inline-1">Go</span>
            </span>

        </div>


        <nav class="side-nav">


            <a
                href="dashboard.php"
                class="active"
            >

                <span class="side-icon">
                    🏠
                </span>

                <span>
                    Inicio
                </span>

            </a>


            <a href="categorias.php">

                <span class="side-icon">
                    🗂️
                </span>

                <span>
                    Categorías
                </span>

            </a>


            <a href="productos.php">

                <span class="side-icon">
                    📦
                </span>

                <span>
                    Productos
                </span>

            </a>


            <a href="ofertas.php">

                <span class="side-icon">
                    🏷️
                </span>

                <span>
                    Ofertas
                </span>

            </a>


            <a href="carrito.php">

                <span class="side-icon">
                    🛒
                </span>

                <span>
                    Carrito
                </span>

            </a>


            <a href="pedidos_c.php">

                <span class="side-icon">
                    📋
                </span>

                <span>
                    Mis pedidos
                </span>

            </a>


            <a href="perfil.php">

                <span class="side-icon">
                    👤
                </span>

                <span>
                    Mi perfil
                </span>

            </a>


        </nav>


        <div class="logout">

            <a href="../php/logout.php">

                <span class="side-icon">
                    🚪
                </span>

                <span>
                    Cerrar sesión
                </span>

            </a>

        </div>


    </aside>



    <!-- ==================================
         CONTENIDO
    =================================== -->

    <main class="main-area">


        <header class="topbar">


            <div class="topbar-title">

                MarketGo

            </div>


            <div class="profile">

                <div class="avatar">
                    👤
                </div>

                <div>

                    <strong>
                        <?php echo htmlspecialchars($nombreCompleto); ?>
                    </strong>

                    <br>

                    <small>
                        Cliente
                    </small>

                </div>

            </div>


        </header>



        <section class="content">


            <!-- BIENVENIDA -->

            <div class="welcome">

                <h1>

                    ¡Bienvenido,
                    <?php echo htmlspecialchars($nombre); ?>!

                </h1>

                <p>
                    Descubre los mejores productos para ti.
                </p>

            </div>



            <!-- BUSCADOR -->

            <div
               
              
             class="panel-box mg-inline-2">

                <input
                    type="text"
                    placeholder="Buscar productos..."
                   class="mg-inline-3"
                >

                <button
                   
                  
                 class="btn mg-inline-4">
                    🔍
                </button>

            </div>



            <!-- CATEGORÍAS -->

            <div class="page-head">

                <div>

                    <h1>
                        Categorías
                    </h1>

                    <p>
                        Explora nuestros productos.
                    </p>

                </div>

            </div>


            <div class="category-grid">


                <a
                    href="productos.php?categoria=frutas"
                    class="category-card"
                >

                    <div class="cat-icon">
                        🍎
                    </div>

                    <h3>
                        Frutas y verduras
                    </h3>

                </a>


                <a
                    href="productos.php?categoria=abarrotes"
                    class="category-card"
                >

                    <div class="cat-icon">
                        🛍️
                    </div>

                    <h3>
                        Abarrotes
                    </h3>

                </a>


                <a
                    href="productos.php?categoria=lacteos"
                    class="category-card"
                >

                    <div class="cat-icon">
                        🥛
                    </div>

                    <h3>
                        Lácteos
                    </h3>

                </a>


                <a
                    href="productos.php?categoria=bebidas"
                    class="category-card"
                >

                    <div class="cat-icon">
                        🥤
                    </div>

                    <h3>
                        Bebidas
                    </h3>

                </a>


                <a
                    href="productos.php?categoria=limpieza"
                    class="category-card"
                >

                    <div class="cat-icon">
                        🧴
                    </div>

                    <h3>
                        Limpieza
                    </h3>

                </a>


            </div>



            <!-- OFERTAS -->

            <div
               
              
             class="page-head mg-inline-5">

                <div>

                    <h1>
                        Ofertas especiales
                    </h1>

                    <p>
                        Aprovecha nuestros mejores precios.
                    </p>

                </div>

                <a
                    href="ofertas.php"
                    class="btn"
                >
                    Ver más
                </a>

            </div>



            <div class="products-grid">


                <div class="product-card">


                    <div class="product-image">

                        <spanclass="mg-inline-6">
                            🍎
                        </span>

                    </div>


                    <div class="product-info">

                        <small>
                            FRUTAS
                        </small>

                        <h3>
                            Manzana
                        </h3>

                        <div class="price">
                            S/ 2.50
                        </div>


                        <a
                            href="carrito.php"
                           
                          
                         class="btn mg-inline-7">
                            Agregar
                        </a>

                    </div>

                </div>



                <div class="product-card">


                    <div class="product-image">

                        <spanclass="mg-inline-8">
                            🥛
                        </span>

                    </div>


                    <div class="product-info">

                        <small>
                            LÁCTEOS
                        </small>

                        <h3>
                            Leche Gloria
                        </h3>

                        <div class="price">
                            S/ 4.20
                        </div>


                        <a
                            href="carrito.php"
                           
                          
                         class="btn mg-inline-9">
                            Agregar
                        </a>

                    </div>

                </div>



                <div class="product-card">


                    <div class="product-image">

                        <spanclass="mg-inline-10">
                            🥖
                        </span>

                    </div>


                    <div class="product-info">

                        <small>
                            PANADERÍA
                        </small>

                        <h3>
                            Pan integral
                        </h3>

                        <div class="price">
                            S/ 3.50
                        </div>


                        <a
                            href="carrito.php"
                           
                          
                         class="btn mg-inline-11">
                            Agregar
                        </a>

                    </div>

                </div>


            </div>


        </section>

    </main>

</div>

<script src="../js/ui.js" defer></script>
</body>

</html>