<?php
session_start();
require_once __DIR__ . '/../php/conexion.php';
if (!isset($_SESSION['usuario_id']) || ($_SESSION['rol'] ?? '') !== 'ADMIN') { header('Location: ../login.php'); exit(); }
$nombre=$_SESSION['nombre']??'Administrador';
$mensajeExito=$_SESSION['usuario_exito']??'';$mensajeError=$_SESSION['usuario_error']??'';unset($_SESSION['usuario_exito'],$_SESSION['usuario_error']);
$res=$conn->query("SELECT id,identificacion,nombre,apellido,correo,rol,estado,fecha_registro FROM usuarios ORDER BY id DESC");
$usuarios=$res?$res->fetch_all(MYSQLI_ASSOC):[];
$totalUsuarios=count($usuarios);$clientes=$empleados=$admins=0;
foreach($usuarios as $u){if($u['rol']==='CLIENTE')$clientes++;elseif($u['rol']==='EMPLEADO')$empleados++;elseif($u['rol']==='ADMIN')$admins++;}
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
        Usuarios - MarketGo
    </title>

    <link
        rel="stylesheet"
        href="../css/style.css"
    >

<link rel="stylesheet" href="../css/page-admin-usuarios.css">
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


            <a href="dashboard.php">
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


            <a
                href="usuarios.php"
                class="active"
            >
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


        <header class="topbar">

            <div class="topbar-title">
                Gestión de usuarios
            </div>


            <div class="profile">

                <div class="avatar">
                    👤
                </div>

                <div>

                    <strong>
                        <?php echo htmlspecialchars($nombre); ?>
                    </strong>

                    <br>

                    <small>
                        Administrador
                    </small>

                </div>

            </div>

        </header>



        <section class="content">


            <div class="page-head">

                <div>

                    <h1>
                        Usuarios
                    </h1>

                    <p>
                        Administra clientes, empleados y administradores.
                    </p>

                </div>


                <a
                    href="../registro_admin.php"
                    class="btn"
                >
                    + Nuevo administrador
                </a>

            </div>



            <div
               
              
             class="panel-box mg-inline-2">

                <input
                    type="text"
                    id="buscarUsuario"
                    placeholder="Buscar usuario..."
                   class="mg-inline-3"
                    onkeyup="buscarUsuarios()"
                >

            </div>



            <div class="stat-grid">
                <div class="stat-card"><div class="stat-icon">👥</div><div><small>Total usuarios</small><strong><?= $totalUsuarios; ?></strong></div></div>
                <div class="stat-card"><div class="stat-icon">🛒</div><div><small>Clientes</small><strong><?= $clientes; ?></strong></div></div>
                <div class="stat-card"><div class="stat-icon">👨‍💼</div><div><small>Empleados</small><strong><?= $empleados; ?></strong></div></div>
                <div class="stat-card"><div class="stat-icon">🔐</div><div><small>Administradores</small><strong><?= $admins; ?></strong></div></div>
            </div>





            <div class="panel-box">


                <h3>
                    Usuarios registrados
                </h3>


                <table class="mini-table" id="tablaUsuarios">
                    <thead><tr><th>ID</th><th>Nombre</th><th>Correo</th><th>Rol</th><th>Estado</th><th>Acción</th></tr></thead>
                    <tbody>
                    <?php if (empty($usuarios)): ?>
                        <tr><td colspan="6">No hay usuarios registrados.</td></tr>
                    <?php else: ?>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?= htmlspecialchars($usuario['identificacion']); ?></td>
                                <td><?= htmlspecialchars($usuario['nombre'].' '.$usuario['apellido']); ?></td>
                                <td><?= htmlspecialchars($usuario['correo']); ?></td>
                                <td><?= htmlspecialchars($usuario['rol']); ?></td>
                                <td><span class="status <?= $usuario['estado']==='ACTIVO'?'entregado':'cancelado'; ?>"><?= htmlspecialchars($usuario['estado']); ?></span></td>
                                <td>
                                    <a class="btn" href="../php/eliminar_usuario.php?identificacion=<?= urlencode($usuario['identificacion']); ?>" onclick="return confirm('¿Deseas desactivar este usuario?');">Desactivar</a>
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

function buscarUsuarios() {

    let texto =
        document
        .getElementById('buscarUsuario')
        .value
        .toLowerCase();

    let filas =
        document.querySelectorAll(
            '#tablaUsuarios tbody tr'
        );


    filas.forEach(function(fila) {

        let contenido =
            fila.textContent.toLowerCase();

        fila.style.display =
            contenido.includes(texto)
            ? ''
            : 'none';

    });

}

</script>

<script src="../js/ui.js" defer></script>
</body>

</html>
