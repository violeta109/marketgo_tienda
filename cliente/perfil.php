<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'CLIENTE') {
    header('Location: ../login.php');
    exit();
}

function escapar($texto)
{
    return htmlspecialchars((string)$texto, ENT_QUOTES, 'UTF-8');
}

$usuarioId = $_SESSION['usuario_id'] ?? '';
$nombre = $_SESSION['nombre'] ?? 'Cliente';
$apellido = $_SESSION['apellido'] ?? '';
$correo = $_SESSION['correo'] ?? '';
$rol = $_SESSION['rol'] ?? 'CLIENTE';

$nombreCompleto = trim($nombre . ' ' . $apellido);
$inicial = strtoupper(substr($nombre, 0, 1));
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Mi perfil | MarketGo</title>

    <link
        rel="stylesheet"
        href="../css/style.css"
    >

<link rel="stylesheet" href="../css/page-cliente-perfil.css">
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

            <a href="perfil.php" class="active">
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


    <!-- CONTENIDO PRINCIPAL -->

    <main class="main-content">

        <!-- TOPBAR -->

        <header class="topbar">

            <div>

                <h1>Mi perfil</h1>

                <p class="page-subtitle">
                    Consulta la información de tu cuenta
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


        <!-- PERFIL -->

        <section class="content-section">

            <div class="profile-container">


                <!-- TARJETA PRINCIPAL -->

                <div class="profile-card">

                    <div class="profile-avatar">

                        <?= escapar($inicial) ?>

                    </div>


                    <h2>
                        <?= escapar($nombreCompleto) ?>
                    </h2>


                    <p class="profile-role">
                        Cliente MarketGo
                    </p>


                    <span class="status-badge entregado">
                        Cuenta activa
                    </span>

                </div>


                <!-- INFORMACIÓN -->

                <div class="profile-info-card">

                    <div class="section-header">

                        <div>

                            <h2>
                                Información personal
                            </h2>

                            <p>
                                Datos registrados en tu cuenta.
                            </p>

                        </div>

                    </div>


                    <div class="detail-grid">


                        <div class="detail-item">

                            <span>
                                Identificación
                            </span>

                            <strong>
                                <?= escapar($usuarioId) ?>
                            </strong>

                        </div>


                        <div class="detail-item">

                            <span>
                                Nombre
                            </span>

                            <strong>
                                <?= escapar($nombre) ?>
                            </strong>

                        </div>


                        <div class="detail-item">

                            <span>
                                Apellido
                            </span>

                            <strong>
                                <?= escapar($apellido) ?>
                            </strong>

                        </div>


                        <div class="detail-item">

                            <span>
                                Correo electrónico
                            </span>

                            <strong>
                                <?= escapar($correo) ?>
                            </strong>

                        </div>


                        <div class="detail-item">

                            <span>
                                Tipo de usuario
                            </span>

                            <strong>
                                <?= escapar($rol) ?>
                            </strong>

                        </div>


                        <div class="detail-item">

                            <span>
                                Estado
                            </span>

                            <strong>
                                Activo
                            </strong>

                        </div>

                    </div>


                    <div class="profile-actions">

                        <a
                            href="productos.php"
                            class="btn-primary"
                        >
                            🛒 Ir a productos
                        </a>


                        <a
                            href="../php/logout.php"
                            class="btn-secondary"
                        >
                            🚪 Cerrar sesión
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