<?php
session_start();
require_once __DIR__ . '/../php/conexion.php';
if (!isset($_SESSION['usuario_id']) || ($_SESSION['rol'] ?? '') !== 'ADMIN') {
    header('Location: ../login.php');
    exit();
}
$nombre = $_SESSION['nombre'] ?? 'Administrador';
$mensajeExito = $_SESSION['categoria_exito'] ?? '';
$mensajeError = $_SESSION['categoria_error'] ?? '';
unset($_SESSION['categoria_exito'], $_SESSION['categoria_error']);
$res = $conn->query("SELECT c.id,c.codigo,c.nombre,c.descripcion,c.estado,COUNT(p.id) AS productos FROM categorias c LEFT JOIN productos p ON p.categoria_id=c.id GROUP BY c.id ORDER BY c.nombre");
$categorias = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
?>


<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Categorías - MarketGo
    </title>

    <link
        rel="stylesheet"
        href="../css/style.css">

<link rel="stylesheet" href="../css/page-admin-categorias.css">
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


                <a
                    href="categorias.php"
                    class="active">

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


            <header class="topbar">

                <div class="topbar-title">
                    Gestión de categorías
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
                            Categorías
                        </h1>

                        <p>
                            Organiza los productos de MarketGo.
                        </p>

                    </div>

                    <button
                        class="btn"
                        onclick="mostrarCategoria()">
                        + Nueva categoría
                    </button>

                </div>


                <!-- FORMULARIO -->

                <div
                   
                    id="formCategoria"
                   class="panel-box mg-inline-2">

                    <h3>
                        Registrar categoría
                    </h3>


                    <form
                        class="auth-form"
                        action="../php/agregar_categoria.php"
                        method="POST">

                        <div class="form-grid">


                            <div class="field">

                                <label>
                                    Nombre
                                </label>

                                <input
                                    type="text"
                                    name="nombre"
                                    required>

                            </div>


                            <div class="field">

                                <label>
                                    Estado
                                </label>

                                <select name="estado">

                                    <option value="ACTIVA">
                                        Activa
                                    </option>

                                    <option value="INACTIVA">
                                        Inactiva
                                    </option>

                                </select>

                            </div>


                            <div class="field full">

                                <label>
                                    Descripción
                                </label>

                                <textarea
                                    name="descripcion"
                                    rows="3"></textarea>

                            </div>

                        </div>


                        <button
                            type="submit"
                            class="btn">
                            Guardar categoría
                        </button>

                    </form>

                </div>


                <!-- CATEGORÍAS -->

                <div class="category-grid">

                    <?php if (empty($categorias)): ?>
                        <div class="panel-box">
                            <p>No hay categorías registradas.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($categorias as $categoria): ?>
                            <div class="category-card">
                                <div class="cat-icon">🗂️</div>
                                <h3><?= htmlspecialchars($categoria['nombre']); ?></h3>
                                <p><?= (int)$categoria['productos']; ?> productos</p>
                                <p><?= htmlspecialchars($categoria['descripcion'] ?? ''); ?></p>
                                <br>
                                <span class="status <?= $categoria['estado'] === 'ACTIVA' ? 'entregado' : 'cancelado'; ?>">
                                    <?= htmlspecialchars($categoria['estado']); ?>
                                </span>
                                <divclass="mg-inline-3">
                                    <a class="btn" href="../php/eliminar_categoria.php?codigo=<?= urlencode($categoria['codigo']); ?>"
                                        onclick="return confirm('¿Deseas desactivar/eliminar esta categoría?');">Eliminar</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>


            </section>

        </main>

    </div>


    <script>
        function mostrarCategoria() {

            document.getElementById(
                'formCategoria'
            ).style.display = 'block';

        }
    </script>


<script src="../js/ui.js" defer></script>
</body>

</html>