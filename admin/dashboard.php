<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.php');
    exit();
}

if ($_SESSION['rol'] !== 'ADMIN') {
    header('Location: ../login.php');
    exit();
}

$nombre = $_SESSION['nombre'] ?? 'Administrador';
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
        Panel del Administrador - MarketGo
    </title>

    <link
        rel="stylesheet"
        href="../css/style.css"
    >

<link rel="stylesheet" href="../css/page-admin-dashboard.css">
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

                <span class="side-icon">🏠</span>

                <span>Inicio</span>

            </a>


            <a href="productos.php">

                <span class="side-icon">📦</span>

                <span>Productos</span>

            </a>


            <a href="categorias.php">

                <span class="side-icon">🗂️</span>

                <span>Categorías</span>

            </a>

            


            <a href="usuarios.php">

                <span class="side-icon">👥</span>

                <span>Usuarios</span>

            </a>


            <a href="pedidos.php">

                <span class="side-icon">🛒</span>

                <span>Pedidos</span>

            </a>


            <a href="reportes.php">

                <span class="side-icon">📊</span>

                <span>Reportes</span>

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


    <main class="main-area">


        <!-- BARRA SUPERIOR -->

        <header class="topbar">


            <div class="topbar-title">

                Panel del Administrador

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
                        Administrador
                    </small>

                </div>

            </div>


        </header>



        <!-- CONTENIDO -->

        <section class="content">


            <div class="welcome">

                <h1>

                    Bienvenido,
                    <?php echo htmlspecialchars($nombre); ?>

                </h1>

                <p>
                    Aquí tienes un resumen general de tu sistema.
                </p>

            </div>



            <div class="stat-grid">


                <div class="stat-card">

                    <div class="stat-icon">
                        📦
                    </div>

                    <div>

                        <small>
                            Productos
                        </small>

                        <strong>
                            125
                        </strong>

                    </div>

                </div>



                <div class="stat-card">

                    <div class="stat-icon">
                        🛒
                    </div>

                    <div>

                        <small>
                            Pedidos
                        </small>

                        <strong>
                            48
                        </strong>

                    </div>

                </div>



                <div class="stat-card">

                    <div class="stat-icon">
                        👥
                    </div>

                    <div>

                        <small>
                            Clientes
                        </small>

                        <strong>
                            32
                        </strong>

                    </div>

                </div>



                <div class="stat-card">

                    <div class="stat-icon">
                        💰
                    </div>

                    <div>

                        <small>
                            Ventas
                        </small>

                        <strong>
                            S/ 12,580
                        </strong>

                    </div>

                </div>


            </div>


            <div class="dashboard-grid">


                <!-- GRÁFICO -->

                <div class="panel-box">

                    <h3>
                        Ventas de la semana
                    </h3>


                    <div class="bars">


                        <div class="bar-wrap">

                            <div
                               
                              
                             class="bar mg-inline-2"></div>

                            <div class="bar-label">
                                Lun
                            </div>

                        </div>


                        <div class="bar-wrap">

                            <div
                               
                              
                             class="bar mg-inline-3"></div>

                            <div class="bar-label">
                                Mar
                            </div>

                        </div>


                        <div class="bar-wrap">

                            <div
                               
                              
                             class="bar mg-inline-4"></div>

                            <div class="bar-label">
                                Mié
                            </div>

                        </div>


                        <div class="bar-wrap">

                            <div
                               
                              
                             class="bar mg-inline-5"></div>

                            <div class="bar-label">
                                Jue
                            </div>

                        </div>


                        <div class="bar-wrap">

                            <div
                               
                              
                             class="bar mg-inline-6"></div>

                            <div class="bar-label">
                                Vie
                            </div>

                        </div>


                        <div class="bar-wrap">

                            <div
                               
                              
                             class="bar mg-inline-7"></div>

                            <div class="bar-label">
                                Sáb
                            </div>

                        </div>


                        <div class="bar-wrap">

                            <div
                               
                              
                             class="bar mg-inline-8"></div>

                            <div class="bar-label">
                                Dom
                            </div>

                        </div>


                    </div>

                </div>


                <div class="panel-box">

                    <h3>
                        Últimos pedidos
                    </h3>


                    <table class="mini-table">

                        <thead>

                        <tr>

                            <th>
                                Cliente
                            </th>

                            <th>
                                Producto
                            </th>

                            <th>
                                Estado
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        <tr>

                            <td>
                                Ana López
                            </td>

                            <td>
                                Laptop
                            </td>

                            <td>
                                <span class="status pendiente">
                                    En proceso
                                </span>
                            </td>

                        </tr>


                        <tr>

                            <td>
                                Carlos Ruiz
                            </td>

                            <td>
                                Mouse
                            </td>

                            <td>
                                <span class="status enviado">
                                    Enviado
                                </span>
                            </td>

                        </tr>


                        <tr>

                            <td>
                                María Torres
                            </td>

                            <td>
                                Teclado
                            </td>

                            <td>
                                <span class="status pendiente">
                                    Pendiente
                                </span>
                            </td>

                        </tr>


                        <tr>

                            <td>
                                José Silva
                            </td>

                            <td>
                                Monitor
                            </td>

                            <td>
                                <span class="status entregado">
                                    Entregado
                                </span>
                            </td>

                        </tr>

                        </tbody>

                    </table>

                </div>


            </div>

            <div
               class="mg-inline-9"
            >

                <h3class="mg-inline-10">
                    Funciones principales del administrador
                </h3>

            </div>


            <div class="quick-grid">


                <a
                    href="productos.php"
                    class="quick-card"
                >

                    <div class="qicon">
                        📦
                    </div>

                    <h4>
                        Gestionar productos
                    </h4>

                    <p>
                        Agregar, editar y eliminar productos.
                    </p>

                </a>



                <a
                    href="categorias.php"
                    class="quick-card"
                >

                    <div class="qicon">
                        🗂️
                    </div>

                    <h4>
                        Categorías
                    </h4>

                    <p>
                        Organizar los productos por categorías.
                    </p>

                </a>



                <a
                    href="usuarios.php"
                    class="quick-card"
                >

                    <div class="qicon">
                        👥
                    </div>

                    <h4>
                        Usuarios
                    </h4>

                    <p>
                        Administrar empleados y clientes.
                    </p>

                </a>



                <a
                    href="reportes.php"
                    class="quick-card"
                >

                    <div class="qicon">
                        📊
                    </div>

                    <h4>
                        Reportes
                    </h4>

                    <p>
                        Consultar estadísticas y ventas.
                    </p>

                </a>


            </div>


        </section>

    </main>

</div>

<script src="../js/ui.js" defer></script>
</body>

</html>