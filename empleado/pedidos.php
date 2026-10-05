<?php
session_start();
require_once __DIR__ . '/../php/conexion.php';

if (!isset($_SESSION['usuario_id']) || ($_SESSION['rol'] ?? '') !== 'EMPLEADO') {
    header('Location: ../login.php'); exit();
}
$nombreUsuario=$_SESSION['nombre'] ?? 'Empleado';
$apellidoUsuario=$_SESSION['apellido'] ?? '';
$mensajeExito=$_SESSION['pedido_exito'] ?? '';
$mensajeError=$_SESSION['pedido_error'] ?? '';
unset($_SESSION['pedido_exito'], $_SESSION['pedido_error']);

$pedidos=[];
$res=$conn->query("SELECT p.id,p.codigo,p.fecha,p.total,p.estado,p.observacion,
    u.id AS cliente_id,u.identificacion,CONCAT(u.nombre,' ',u.apellido) AS cliente,
    (SELECT COUNT(*) FROM detalle_pedido dp WHERE dp.pedido_id=p.id) AS productos
    FROM pedidos p INNER JOIN usuarios u ON u.id=p.usuario_id ORDER BY p.fecha DESC");
if($res){
    $pedidos=$res->fetch_all(MYSQLI_ASSOC);
    foreach($pedidos as &$pedido){
        $stmt=$conn->prepare("SELECT dp.cantidad,dp.precio,dp.subtotal,pr.nombre FROM detalle_pedido dp
            INNER JOIN productos pr ON pr.id=dp.producto_id WHERE dp.pedido_id=? ORDER BY dp.id");
        $stmt->bind_param('i',$pedido['id']); $stmt->execute();
        $pedido['detalle']=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    unset($pedido);
}
$totalPedidos=count($pedidos); $pendientes=$proceso=$entregados=$cancelados=0;
foreach($pedidos as $pedido){
    switch(strtoupper($pedido['estado']??'')){case 'PENDIENTE':$pendientes++;break;case 'EN PROCESO':$proceso++;break;case 'ENTREGADO':$entregados++;break;case 'CANCELADO':$cancelados++;break;}
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pedidos - MarketGo</title>

    <link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/page-empleado-pedidos.css">
</head>

<body>

<div class="dashboard-container">

    <!-- SIDEBAR -->
    <aside class="sidebar">

        <div class="sidebar-brand">

            <span class="brand-mark">🛒</span>

            <span>
                Market<spanclass="mg-inline-1">Go</span>
            </span>

        </div>


        <nav class="sidebar-menu">

            <a href="dashboard.php">
                <span>🏠</span>
                <span>Inicio</span>
            </a>

            <a href="productos.php">
                <span>📦</span>
                <span>Productos</span>
            </a>

            <a href="pedidos.php" class="active">
                <span>🛒</span>
                <span>Pedidos</span>
            </a>

            <a href="stock.php">
                <span>📊</span>
                <span>Stock</span>
            </a>

            <a href="perfil.php">
                <span>👤</span>
                <span>Mi perfil</span>
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

                <h1>Pedidos</h1>

                <p>
                    Consulta y gestiona los pedidos de los clientes.
                </p>

            </div>


            <div class="user-profile">

                <div class="user-avatar">

                    <?= strtoupper(substr($nombreUsuario, 0, 1)) ?>

                </div>


                <div class="user-info">

                    <strong>
                        <?= htmlspecialchars($nombreUsuario . ' ' . $apellidoUsuario) ?>
                    </strong>

                    <span>
                        Empleado
                    </span>

                </div>

            </div>

        </header>


        <section class="content-section">


            <!-- MENSAJES -->

            <?php if ($mensajeExito): ?>

                <div class="alert alert-success">

                    <?= htmlspecialchars($mensajeExito) ?>

                </div>

            <?php endif; ?>


            <?php if ($mensajeError): ?>

                <div class="alert alert-error">

                    <?= htmlspecialchars($mensajeError) ?>

                </div>

            <?php endif; ?>


            <!-- ESTADÍSTICAS -->

            <div class="stats-grid">

                <div class="stat-card">

                    <div class="stat-icon">
                        🛒
                    </div>

                    <div>

                        <span>
                            Total pedidos
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
                        ⚙️
                    </div>

                    <div>

                        <span>
                            En proceso
                        </span>

                        <strong>
                            <?= $proceso ?>
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


            <!-- CABECERA -->

            <div class="section-header">

                <div>

                    <h2>
                        Lista de pedidos
                    </h2>

                    <p>
                        Revisa el estado y detalle de cada pedido.
                    </p>

                </div>


                <divclass="mg-inline-2">

                    <input
                        type="text"
                        id="buscarPedido"
                        class="search-input"
                        placeholder="Buscar pedido..."
                    >


                    <select
                        id="filtroEstado"
                        class="filter-select"
                    >

                        <option value="">
                            Todos los estados
                        </option>

                        <option value="PENDIENTE">
                            Pendiente
                        </option>

                        <option value="EN PROCESO">
                            En proceso
                        </option>

                        <option value="ENTREGADO">
                            Entregado
                        </option>

                        <option value="CANCELADO">
                            Cancelado
                        </option>

                    </select>

                </div>

            </div>


            <!-- TABLA -->

            <div class="table-container">

                <table class="data-table">

                    <thead>

                        <tr>

                            <th>
                                Código
                            </th>

                            <th>
                                Cliente
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
                                Acciones
                            </th>

                        </tr>

                    </thead>


                    <tbody id="tablaPedidos">


                    <?php if (empty($pedidos)): ?>

                        <tr>

                            <td
                                colspan="7"
                               class="mg-inline-3"
                            >

                                <div
                                   class="mg-inline-4"
                                >
                                    🛒
                                </div>


                                <strong>
                                    No hay pedidos registrados
                                </strong>


                                <p
                                   class="mg-inline-5"
                                >
                                    Los pedidos realizados por los clientes aparecerán aquí.
                                </p>

                            </td>

                        </tr>


                    <?php else: ?>


                        <?php foreach ($pedidos as $pedido): ?>

                            <?php

                            $codigo =
                                $pedido['codigo'] ?? '---';

                            $cliente =
                                $pedido['cliente'] ?? 'Cliente';

                            $fecha =
                                $pedido['fecha'] ?? '---';

                            $productos =
                                $pedido['productos'] ?? 0;

                            $total =
                                $pedido['total'] ?? 0;

                            $estado =
                                strtoupper(
                                    $pedido['estado'] ?? 'PENDIENTE'
                                );


                            if ($estado === 'ENTREGADO') {

                                $claseEstado = 'entregado';

                            } elseif ($estado === 'CANCELADO') {

                                $claseEstado = 'cancelado';

                            } else {

                                $claseEstado = 'pendiente';

                            }

                            ?>

                            <tr
                                class="fila-pedido"
                                data-codigo="<?= htmlspecialchars($codigo) ?>"
                                data-cliente="<?= htmlspecialchars($cliente) ?>"
                                data-estado="<?= htmlspecialchars($estado) ?>"
                            >

                                <td>

                                    <strong>
                                        <?= htmlspecialchars($codigo) ?>
                                    </strong>

                                </td>


                                <td>

                                    <?= htmlspecialchars($cliente) ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars($fecha) ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars($productos) ?>

                                </td>


                                <td>

                                    <strong>
                                        S/
                                        <?= number_format((float)$total, 2) ?>
                                    </strong>

                                </td>


                                <td>

                                    <span
                                        class="status <?= $claseEstado ?>"
                                    >
                                        <?= htmlspecialchars($estado) ?>
                                    </span>

                                </td>


                                <td>

                                    <button
                                        type="button"
                                        class="btn-secondary"
                                        onclick='verPedido(
                                            <?= json_encode(
                                                $pedido,
                                                JSON_HEX_TAG |
                                                JSON_HEX_APOS |
                                                JSON_HEX_QUOT |
                                                JSON_HEX_AMP
                                            ) ?>
                                        )'
                                    >
                                        Ver
                                    </button>


                                    <button
                                        type="button"
                                        class="btn-primary"
                                        onclick='editarPedido(
                                            <?= json_encode(
                                                $pedido,
                                                JSON_HEX_TAG |
                                                JSON_HEX_APOS |
                                                JSON_HEX_QUOT |
                                                JSON_HEX_AMP
                                            ) ?>
                                        )'
                                    >
                                        Editar
                                    </button>

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



<!-- MODAL VER -->

<div
    id="modalPedido"
    class="modal"
>

    <div class="modal-content">


        <div class="modal-header">

            <h2>
                Detalle del pedido
            </h2>

            <button
                type="button"
                onclick="cerrarModal('modalPedido')"
            >
                ✕
            </button>

        </div>


        <div id="detallePedido"></div>


    </div>

</div>



<!-- MODAL EDITAR -->

<div
    id="modalEditar"
    class="modal"
>

    <div class="modal-content">


        <div class="modal-header">

            <h2>
                Actualizar pedido
            </h2>

            <button
                type="button"
                onclick="cerrarModal('modalEditar')"
            >
                ✕
            </button>

        </div>


        <form
            action="../php/actualizar_pedido.php"
            method="POST"
        >

            <input
                type="hidden"
                name="codigo"
                id="editarCodigo"
            >


            <input
                type="hidden"
                name="origen"
                value="empleado"
            >


            <div class="form-group">

                <label>
                    Estado
                </label>


                <select
                    name="estado"
                    id="editarEstado"
                   
                  
                    required
                 class="filter-select mg-inline-6">

                    <option value="PENDIENTE">
                        Pendiente
                    </option>

                    <option value="EN PROCESO">
                        En proceso
                    </option>

                    <option value="ENTREGADO">
                        Entregado
                    </option>

                    <option value="CANCELADO">
                        Cancelado
                    </option>

                </select>

            </div>


            <div class="form-group">

                <label>
                    Observación
                </label>


                <textarea
                    name="observacion"
                    id="editarObservacion"
                    rows="4"
                    placeholder="Escribe una observación..."
                   class="mg-inline-7"
                ></textarea>

            </div>


            <div
               class="mg-inline-8"
            >

                <button
                    type="button"
                    class="btn-secondary"
                    onclick="cerrarModal('modalEditar')"
                >
                    Cancelar
                </button>


                <button
                    type="submit"
                    class="btn-primary"
                >
                    Guardar cambios
                </button>

            </div>

        </form>

    </div>

</div>



<script>


// BUSCADOR

document
    .getElementById('buscarPedido')
    .addEventListener(
        'input',
        filtrarPedidos
    );


document
    .getElementById('filtroEstado')
    .addEventListener(
        'change',
        filtrarPedidos
    );


function filtrarPedidos() {

    const texto =
        document
            .getElementById('buscarPedido')
            .value
            .toLowerCase();


    const estado =
        document
            .getElementById('filtroEstado')
            .value;


    const filas =
        document.querySelectorAll(
            '.fila-pedido'
        );


    filas.forEach(fila => {

        const codigo =
            fila.dataset.codigo.toLowerCase();

        const cliente =
            fila.dataset.cliente.toLowerCase();

        const estadoFila =
            fila.dataset.estado;


        const coincideTexto =
            codigo.includes(texto) ||
            cliente.includes(texto);


        const coincideEstado =
            estado === '' ||
            estadoFila === estado;


        fila.style.display =
            coincideTexto && coincideEstado
                ? ''
                : 'none';

    });

}



// VER PEDIDO

function verPedido(pedido) {

    let productosHTML = '';


    if (
        pedido.detalle &&
        pedido.detalle.length > 0
    ) {

        productosHTML = `

            <h3class="mg-inline-9">
                Productos
            </h3>


            <div class="table-container">

                <table class="data-table">

                    <thead>

                        <tr>

                            <th>
                                Producto
                            </th>

                            <th>
                                Precio
                            </th>

                            <th>
                                Cantidad
                            </th>

                            <th>
                                Subtotal
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        ${pedido.detalle.map(item => `

                            <tr>

                                <td>
                                    ${escapeHTML(item.nombre)}
                                </td>

                                <td>
                                    S/
                                    ${parseFloat(item.precio).toFixed(2)}
                                </td>

                                <td>
                                    ${item.cantidad}
                                </td>

                                <td>
                                    S/
                                    ${parseFloat(item.subtotal).toFixed(2)}
                                </td>

                            </tr>

                        `).join('')}

                    </tbody>

                </table>

            </div>

        `;

    } else {

        productosHTML = `

            <pclass="mg-inline-10">
                No hay detalle disponible.
            </p>

        `;

    }


    document
        .getElementById('detallePedido')
        .innerHTML = `

            <divclass="mg-inline-11">

                <div>

                    <strong>
                        Código:
                    </strong>

                    ${escapeHTML(
                        pedido.codigo || ''
                    )}

                </div>


                <div>

                    <strong>
                        Cliente:
                    </strong>

                    ${escapeHTML(
                        pedido.cliente || ''
                    )}

                </div>


                <div>

                    <strong>
                        Fecha:
                    </strong>

                    ${escapeHTML(
                        pedido.fecha || ''
                    )}

                </div>


                <div>

                    <strong>
                        Estado:
                    </strong>

                    ${escapeHTML(
                        pedido.estado || ''
                    )}

                </div>


                <div>

                    <strong>
                        Total:
                    </strong>

                    S/
                    ${parseFloat(
                        pedido.total || 0
                    ).toFixed(2)}

                </div>


                ${
                    pedido.observacion
                    ? `

                        <div>

                            <strong>
                                Observación:
                            </strong>

                            ${escapeHTML(
                                pedido.observacion
                            )}

                        </div>

                    `
                    : ''
                }

            </div>


            ${productosHTML}


            <div
               class="mg-inline-12"
            >

                <button
                    type="button"
                    class="btn-secondary"
                    onclick="cerrarModal('modalPedido')"
                >
                    Cerrar
                </button>

            </div>

        `;


    document
        .getElementById('modalPedido')
        .style.display = 'flex';

}



// EDITAR

function editarPedido(pedido) {

    document
        .getElementById('editarCodigo')
        .value =
            pedido.codigo || '';


    document
        .getElementById('editarEstado')
        .value =
            pedido.estado || 'PENDIENTE';


    document
        .getElementById('editarObservacion')
        .value =
            pedido.observacion || '';


    document
        .getElementById('modalEditar')
        .style.display = 'flex';

}



// CERRAR MODAL

function cerrarModal(id) {

    document
        .getElementById(id)
        .style.display = 'none';

}



// CERRAR AL HACER CLICK FUERA

window.addEventListener(
    'click',
    function(event) {

        const modalPedido =
            document.getElementById(
                'modalPedido'
            );

        const modalEditar =
            document.getElementById(
                'modalEditar'
            );


        if (event.target === modalPedido) {

            modalPedido.style.display =
                'none';

        }


        if (event.target === modalEditar) {

            modalEditar.style.display =
                'none';

        }

    }
);



// PROTECCIÓN HTML

function escapeHTML(text) {

    return String(text)

        .replace(
            /&/g,
            '&amp;'
        )

        .replace(
            /</g,
            '&lt;'
        )

        .replace(
            />/g,
            '&gt;'
        )

        .replace(
            /"/g,
            '&quot;'
        )

        .replace(
            /'/g,
            '&#039;'
        );

}

</script>


<script src="../js/ui.js" defer></script>
</body>

</html>
