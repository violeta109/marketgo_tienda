<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'EMPLEADO') {
    header('Location: ../login.php');
    exit();
}

$usuarioId = $_SESSION['usuario_id'] ?? 'EMP001';
$nombreUsuario = $_SESSION['nombre'] ?? 'Empleado';
$apellidoUsuario = $_SESSION['apellido'] ?? '';
$correoUsuario = $_SESSION['correo'] ?? 'empleado@marketgo.com';
$rolUsuario = $_SESSION['rol'] ?? 'EMPLEADO';

$nombreCompleto = trim($nombreUsuario . ' ' . $apellidoUsuario);
$inicial = strtoupper(substr($nombreUsuario, 0, 1));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Mi Perfil - MarketGo</title>

    <link rel="stylesheet" href="../css/style.css">

    
<link rel="stylesheet" href="../css/page-empleado-perfil.css">
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

            <a href="stock.php">
                <span>📋</span>
                <span>Stock</span>
            </a>

            <a href="perfil.php" class="active">
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


    <!-- CONTENIDO -->
    <main class="main-content">

        <!-- TOPBAR -->
        <header class="topbar">

            <div>
                <h1>Mi Perfil</h1>
                <p>Consulta la información de tu cuenta.</p>
            </div>

            <div class="topbar-user">

                <div class="user-avatar">
                    <?= htmlspecialchars($inicial); ?>
                </div>

                <div>
                    <strong>
                        <?= htmlspecialchars($nombreCompleto); ?>
                    </strong>

                    <small>Empleado</small>
                </div>

            </div>

        </header>


        <!-- PERFIL -->
        <section class="profile-container">

            <!-- TARJETA IZQUIERDA -->
            <div class="profile-card profile-main">

                <div class="profile-avatar">
                    <?= htmlspecialchars($inicial); ?>
                </div>

                <h2>
                    <?= htmlspecialchars($nombreCompleto); ?>
                </h2>

                <p>
                    <?= htmlspecialchars($correoUsuario); ?>
                </p>

                <span class="role-badge">
                    EMPLEADO
                </span>

            </div>


            <!-- INFORMACIÓN -->
            <div class="profile-card profile-details">

                <h2>Información personal</h2>

                <div class="profile-row">

                    <div class="profile-label">
                        Identificación
                    </div>

                    <div class="profile-value">
                        <?= htmlspecialchars($usuarioId); ?>
                    </div>

                </div>


                <div class="profile-row">

                    <div class="profile-label">
                        Nombre
                    </div>

                    <div class="profile-value">
                        <?= htmlspecialchars($nombreUsuario); ?>
                    </div>

                </div>


                <div class="profile-row">

                    <div class="profile-label">
                        Apellido
                    </div>

                    <div class="profile-value">
                        <?= htmlspecialchars($apellidoUsuario); ?>
                    </div>

                </div>


                <div class="profile-row">

                    <div class="profile-label">
                        Correo electrónico
                    </div>

                    <div class="profile-value">
                        <?= htmlspecialchars($correoUsuario); ?>
                    </div>

                </div>


                <div class="profile-row">

                    <div class="profile-label">
                        Rol
                    </div>

                    <div class="profile-value">
                        <?= htmlspecialchars($rolUsuario); ?>
                    </div>

                </div>


                <div class="profile-note">
                    🔒 La información mostrada corresponde a la cuenta con la
                    que has iniciado sesión en MarketGo.
                </div>

            </div>

        </section>

    </main>

</div>

<script src="../js/ui.js" defer></script>
</body>
</html>