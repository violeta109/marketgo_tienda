<?php
session_start();
require_once __DIR__ . '/../php/conexion.php';
if (!isset($_SESSION['usuario_id']) || ($_SESSION['rol'] ?? '') !== 'CLIENTE') {
    header('Location: ../login.php');
    exit();
}
function escapar($texto)
{
    return htmlspecialchars((string)$texto, ENT_QUOTES, 'UTF-8');
}
function claseEstado($estado)
{
    switch ($estado) {
        case 'ENTREGADO':
            return 'entregado';
        case 'EN PROCESO':
            return 'pendiente';
        case 'CANCELADO':
            return 'cancelado';
        default:
            return 'pendiente';
    }
}
$nombreUsuario = $_SESSION['nombre'] ?? 'Cliente';
$apellidoUsuario = $_SESSION['apellido'] ?? '';
$nombreCompleto = trim($nombreUsuario . ' ' . $apellidoUsuario);
$inicial = strtoupper(substr($nombreUsuario, 0, 1));
$usuarioId = (int)$_SESSION['usuario_id'];
$pedidosRegistrados = [];
$stmt = $conn->prepare("SELECT p.id,p.codigo,p.fecha,p.total,p.estado,p.observacion FROM pedidos p WHERE p.usuario_id=? ORDER BY p.fecha DESC");
$stmt->bind_param('i', $usuarioId);
$stmt->execute();
$pedidosRegistrados = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
foreach ($pedidosRegistrados as &$pedido) {
    $stmtDet = $conn->prepare("SELECT dp.cantidad,dp.precio,dp.subtotal,pr.nombre FROM detalle_pedido dp INNER JOIN productos pr ON pr.id=dp.producto_id WHERE dp.pedido_id=? ORDER BY dp.id");
    $stmtDet->bind_param('i', $pedido['id']);
    $stmtDet->execute();
    $pedido['detalle'] = $stmtDet->get_result()->fetch_all(MYSQLI_ASSOC);
    $pedido['productos'] = count($pedido['detalle']);
}
unset($pedido);
$misPedidos = $pedidosRegistrados;
$busqueda = trim($_GET['buscar'] ?? '');
$estadoSeleccionado = trim($_GET['estado'] ?? '');
$pedidosFiltrados = array_values(array_filter($misPedidos, function ($pedido) use ($busqueda, $estadoSeleccionado) {
    $b = true;
    $e = true;
    if ($busqueda !== '') {
        $texto = strtolower(($pedido['codigo'] ?? '') . ' ' . ($pedido['fecha'] ?? ''));
        $b = strpos($texto, strtolower($busqueda)) !== false;
    }
    if ($estadoSeleccionado !== '') $e = ($pedido['estado'] ?? '') === $estadoSeleccionado;
    return $b && $e;
}));
$totalPedidos = count($misPedidos);
$pendientes = $enProceso = $entregados = $cancelados = 0;
foreach ($misPedidos as $pedido) {
    switch ($pedido['estado'] ?? '') {
        case 'PENDIENTE':
            $pendientes++;
            break;
        case 'EN PROCESO':
            $enProceso++;
            break;
        case 'ENTREGADO':
            $entregados++;
            break;
        case 'CANCELADO':
            $cancelados++;
            break;
    }
}
?>


<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Mis pedidos | MarketGo</title>

    <link
        rel="stylesheet"
        href="../css/style.css">

<link rel="stylesheet" href="../css/page-cliente-pedidos_c.css">
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

                <a href="pedidos_c.php" class="active">
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

                    <h1>Mis pedidos</h1>

                    <p class="page-subtitle">
                        Consulta tus compras y el estado de cada pedido
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


            <section class="content-section">


                <?php if ($compraRealizada): ?>

                    <div class="alert alert-success">

                        ✅ Compra realizada correctamente.
                        Puedes consultar el estado de tu pedido en esta sección.

                    </div>

                <?php endif; ?>


                <!-- ESTADÍSTICAS -->

                <div class="stats-grid">

                    <div class="stat-card">

                        <div class="stat-icon">
                            📋
                        </div>

                        <div>

                            <span>
                                Total de pedidos
                            </span>

                            <strong>
                                <?= $totalPedidos ?>
                            </strong>

                        </div>

                    </div>


                    <div class="stat-card">

                        <div class="stat-icon">
                            ⏳
                        </div>

                        <div>

                            <span>
                                Pendientes
                            </span>

                            <strong>
                                <?= $pendientes ?>
                            </strong>

                        </div>

                    </div>


                    <div class="stat-card">

                        <div class="stat-icon">
                            🚚
                        </div>

                        <div>

                            <span>
                                En proceso
                            </span>

                            <strong>
                                <?= $enProceso ?>
                            </strong>

                        </div>

                    </div>


                    <div class="stat-card">

                        <div class="stat-icon">
                            ✅
                        </div>

                        <div>

                            <span>
                                Entregados
                            </span>

                            <strong>
                                <?= $entregados ?>
                            </strong>

                        </div>

                    </div>

                </div>


                <!-- ENCABEZADO -->

                <div class="section-header">

                    <div>

                        <h2>
                            Historial de pedidos
                        </h2>

                        <p>
                            Revisa el detalle de tus compras.
                        </p>

                    </div>


                    <a
                        href="productos.php"
                        class="btn-primary">
                        🛒 Comprar productos
                    </a>

                </div>


                <!-- FILTROS -->

                <form
                    method="GET"
                    class="filters-container">

                    <div class="form-group">

                        <label for="buscar">
                            Buscar pedido
                        </label>

                        <input
                            type="text"
                            id="buscar"
                            name="buscar"
                            placeholder="Código o fecha..."
                            value="<?= escapar($busqueda) ?>">

                    </div>


                    <div class="form-group">

                        <label for="estado">
                            Estado
                        </label>

                        <select
                            id="estado"
                            name="estado">

                            <option value="">
                                Todos los estados
                            </option>

                            <option
                                value="PENDIENTE"
                                <?= $estadoSeleccionado === 'PENDIENTE' ? 'selected' : '' ?>>
                                Pendiente
                            </option>

                            <option
                                value="EN PROCESO"
                                <?= $estadoSeleccionado === 'EN PROCESO' ? 'selected' : '' ?>>
                                En proceso
                            </option>

                            <option
                                value="ENTREGADO"
                                <?= $estadoSeleccionado === 'ENTREGADO' ? 'selected' : '' ?>>
                                Entregado
                            </option>

                            <option
                                value="CANCELADO"
                                <?= $estadoSeleccionado === 'CANCELADO' ? 'selected' : '' ?>>
                                Cancelado
                            </option>

                        </select>

                    </div>


                    <div class="filter-actions">

                        <button
                            type="submit"
                            class="btn-primary">
                            🔍 Buscar
                        </button>


                        <a
                            href="pedidos_c.php"
                            class="btn-secondary">
                            Limpiar
                        </a>

                    </div>

                </form>


                <!-- TABLA -->

                <?php if (count($pedidosFiltrados) > 0): ?>

                    <div class="table-container">

                        <table>

                            <thead>

                                <tr>

                                    <th>
                                        Pedido
                                    </th>

                                    <th>
                                        Fecha
                                    </th>

                                    <th>
                                        Productos
                                    </th>

                                    <th>
                                        Total
                                    </th>

                                    <th>
                                        Estado
                                    </th>

                                    <th>
                                        Acción
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                <?php foreach ($pedidosFiltrados as $indice => $pedido): ?>

                                    <?php
                                    $estado = $pedido['estado'] ?? 'PENDIENTE';
                                    $claseEstado = claseEstado($estado);
                                    ?>

                                    <tr>

                                        <td>

                                            <strong>
                                                <?= escapar($pedido['codigo'] ?? '-') ?>
                                            </strong>

                                        </td>


                                        <td>
                                            <?= escapar($pedido['fecha'] ?? '-') ?>
                                        </td>


                                        <td>
                                            <?= (int)($pedido['productos'] ?? 0) ?>
                                        </td>


                                        <td>

                                            <strong>
                                                S/
                                                <?= number_format(
                                                    (float)($pedido['total'] ?? 0),
                                                    2
                                                ) ?>
                                            </strong>

                                        </td>


                                        <td>

                                            <span
                                                class="status-badge <?= escapar($claseEstado) ?>">
                                                <?= escapar($estado) ?>
                                            </span>

                                        </td>


                                        <td>

                                            <button
                                                type="button"
                                                class="btn-secondary"
                                                onclick="verPedido(<?= $indice ?>)">
                                                👁️ Ver detalle
                                            </button>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            </tbody>

                        </table>

                    </div>

                <?php else: ?>

                    <div class="empty-state">

                        <div class="empty-state-icon">
                            📋
                        </div>

                        <h3>
                            No tienes pedidos registrados
                        </h3>

                        <p>
                            Cuando realices una compra,
                            aparecerá aquí.
                        </p>

                        <a
                            href="productos.php"
                            class="btn-primary">
                            Comprar productos
                        </a>

                    </div>

                <?php endif; ?>

            </section>

        </main>

    </div>


    <!-- MODAL DETALLE -->

    <div
        id="modalPedido"
       
       class="modal-overlay mg-inline-2">

        <div class="modal">

            <div class="modal-header">

                <h2>
                    Detalle del pedido
                </h2>

                <button
                    type="button"
                    class="modal-close"
                    onclick="cerrarModal()">
                    ×
                </button>

            </div>


            <div
                id="detallePedido"
                class="modal-body">
            </div>

        </div>

    </div>


    <script>
        const pedidos = <?= json_encode(
                            $pedidosFiltrados,
                            JSON_UNESCAPED_UNICODE |
                                JSON_UNESCAPED_SLASHES
                        ) ?>;


        /*
        |--------------------------------------------------------------------------
        | Ver pedido
        |--------------------------------------------------------------------------
        */

        function verPedido(indice) {

            const pedido = pedidos[indice];

            if (!pedido) {
                return;
            }


            let detalleProductos = '';


            if (
                pedido.detalle &&
                Array.isArray(pedido.detalle) &&
                pedido.detalle.length > 0
            ) {

                pedido.detalle.forEach(producto => {

                    const cantidad = Number(
                        producto.cantidad || 0
                    );

                    const precio = Number(
                        producto.precio || 0
                    );

                    const subtotal = Number(
                        producto.subtotal || precio * cantidad
                    );


                    detalleProductos += `

                <tr>

                    <td>
                        ${escapeHTML(producto.nombre || '')}
                    </td>

                    <td>
                        ${cantidad}
                    </td>

                    <td>
                        S/ ${precio.toFixed(2)}
                    </td>

                    <td>
                        S/ ${subtotal.toFixed(2)}
                    </td>

                </tr>

            `;

                });

            } else {

                detalleProductos = `

            <tr>

                <td colspan="4">
                    No hay detalle disponible.
                </td>

            </tr>

        `;

            }


            const estado = pedido.estado || 'PENDIENTE';


            document.getElementById('detallePedido').innerHTML = `

        <div class="detail-grid">

            <div>

                <span>
                    Pedido
                </span>

                <strong>
                    ${escapeHTML(pedido.codigo || '-')}
                </strong>

            </div>


            <div>

                <span>
                    Fecha
                </span>

                <strong>
                    ${escapeHTML(pedido.fecha || '-')}
                </strong>

            </div>


            <div>

                <span>
                    Estado
                </span>

                <strong>
                    ${escapeHTML(estado)}
                </strong>

            </div>


            <div>

                <span>
                    Total
                </span>

                <strong>
                    S/ ${Number(
                        pedido.total || 0
                    ).toFixed(2)}
                </strong>

            </div>

        </div>


        <h3>
            Productos
        </h3>


        <div class="table-container">

            <table>

                <thead>

                    <tr>

                        <th>
                            Producto
                        </th>

                        <th>
                            Cantidad
                        </th>

                        <th>
                            Precio
                        </th>

                        <th>
                            Subtotal
                        </th>

                    </tr>

                </thead>

                <tbody>

                    ${detalleProductos}

                </tbody>

            </table>

        </div>


        ${
            pedido.observacion
            ?
            `
                <div class="detail-observation">

                    <strong>
                        Observación:
                    </strong>

                    <p>
                        ${escapeHTML(pedido.observacion)}
                    </p>

                </div>
            `
            :
            ''
        }

    `;


            document.getElementById('modalPedido').style.display = 'flex';

        }


        /*
        |--------------------------------------------------------------------------
        | Cerrar modal
        |--------------------------------------------------------------------------
        */

        function cerrarModal() {

            document.getElementById('modalPedido').style.display = 'none';

        }


        /*
        |--------------------------------------------------------------------------
        | Cerrar haciendo clic fuera
        |--------------------------------------------------------------------------
        */

        document.getElementById('modalPedido').addEventListener(
            'click',
            function(event) {

                if (event.target === this) {
                    cerrarModal();
                }

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Seguridad HTML
        |--------------------------------------------------------------------------
        */

        function escapeHTML(texto) {

            const div = document.createElement('div');

            div.textContent = texto;

            return div.innerHTML;
        }


        /*
        |--------------------------------------------------------------------------
        | Limpiar carrito después de una compra
        |--------------------------------------------------------------------------
        */

        <?php if ($compraRealizada): ?>

            localStorage.removeItem('marketgo_carrito');

        <?php endif; ?>
    </script>

<script src="../js/ui.js" defer></script>
</body>

</html>