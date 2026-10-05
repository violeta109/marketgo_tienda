<?php
session_start();
require_once __DIR__ . '/../php/conexion.php';
if (!isset($_SESSION['usuario_id']) || ($_SESSION['rol'] ?? '') !== 'CLIENTE') { header('Location: ../login.php'); exit(); }
function escapar($texto){return htmlspecialchars((string)$texto,ENT_QUOTES,'UTF-8');}
$nombreUsuario=$_SESSION['nombre']??'Cliente';$apellidoUsuario=$_SESSION['apellido']??'';$nombreCompleto=trim($nombreUsuario.' '.$apellidoUsuario);$inicial=strtoupper(substr($nombreUsuario,0,1));
$mensajeExito=$_SESSION['pedido_exito']??'';$mensajeError=$_SESSION['pedido_error']??'';unset($_SESSION['pedido_exito'],$_SESSION['pedido_error']);
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Mi carrito | MarketGo</title>

    <link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/page-cliente-carrito.css">
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

            <a href="carrito.php" class="active">
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


    <!-- CONTENIDO PRINCIPAL -->
    <main class="main-content">

        <!-- TOPBAR -->
        <header class="topbar">

            <div>
                <h1>Mi carrito</h1>

                <p class="page-subtitle">
                    Revisa tus productos antes de realizar la compra
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

            <?php if ($mensajeExito): ?>

                <div class="alert alert-success">
                    ✅ <?= escapar($mensajeExito) ?>
                </div>

            <?php endif; ?>


            <?php if ($mensajeError): ?>

                <div class="alert alert-error">
                    ⚠️ <?= escapar($mensajeError) ?>
                </div>

            <?php endif; ?>


            <div class="section-header">

                <div>

                    <h2>🛒 Productos seleccionados</h2>

                    <p>
                        Puedes modificar las cantidades o eliminar productos.
                    </p>

                </div>

                <a href="productos.php" class="btn-secondary">
                    ← Seguir comprando
                </a>

            </div>


            <!-- CONTENEDOR DEL CARRITO -->
            <div id="carrito-contenido">

                <div class="empty-state">

                    <div class="empty-state-icon">
                        🛒
                    </div>

                    <h3>
                        Tu carrito está vacío
                    </h3>

                    <p>
                        Agrega productos para comenzar tu compra.
                    </p>

                    <a href="productos.php" class="btn-primary">
                        Ver productos
                    </a>

                </div>

            </div>

        </section>

    </main>

</div>


<script>

let carrito = JSON.parse(
    localStorage.getItem('marketgo_carrito') || '[]'
);


/*
|--------------------------------------------------------------------------
| Mostrar carrito
|--------------------------------------------------------------------------
*/

function mostrarCarrito() {

    const contenedor = document.getElementById('carrito-contenido');

    if (carrito.length === 0) {

        contenedor.innerHTML = `
            <div class="empty-state">

                <div class="empty-state-icon">
                    🛒
                </div>

                <h3>
                    Tu carrito está vacío
                </h3>

                <p>
                    Agrega productos para comenzar tu compra.
                </p>

                <a href="productos.php" class="btn-primary">
                    Ver productos
                </a>

            </div>
        `;

        return;
    }


    let total = 0;
    let cantidadProductos = 0;

    let filas = '';


    carrito.forEach((producto, indice) => {

        const precio = Number(producto.precio);
        const cantidad = Number(producto.cantidad);

        const subtotal = precio * cantidad;

        total += subtotal;
        cantidadProductos += cantidad;


        filas += `
            <tr>

                <td>
                    <strong>
                        ${escapeHTML(producto.codigo || '')}
                    </strong>
                </td>

                <td>
                    ${escapeHTML(producto.nombre)}
                </td>

                <td>
                    S/ ${precio.toFixed(2)}
                </td>

                <td>

                    <div class="quantity-control">

                        <button
                            type="button"
                            onclick="cambiarCantidad(${indice}, -1)"
                        >
                            −
                        </button>

                        <span>
                            ${cantidad}
                        </span>

                        <button
                            type="button"
                            onclick="cambiarCantidad(${indice}, 1)"
                        >
                            +
                        </button>

                    </div>

                </td>

                <td>
                    <strong>
                        S/ ${subtotal.toFixed(2)}
                    </strong>
                </td>

                <td>

                    <button
                        type="button"
                        class="btn-danger"
                        onclick="eliminarProducto(${indice})"
                    >
                        🗑️
                    </button>

                </td>

            </tr>
        `;

    });


    contenedor.innerHTML = `

        <div class="table-container">

            <table>

                <thead>

                    <tr>
                        <th>Código</th>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Acción</th>
                    </tr>

                </thead>

                <tbody>
                    ${filas}
                </tbody>

            </table>

        </div>


        <div class="cart-summary">

            <div>

                <span>
                    Productos
                </span>

                <strong>
                    ${cantidadProductos}
                </strong>

            </div>


            <div>

                <span>
                    Total a pagar
                </span>

                <strong class="cart-total">
                    S/ ${total.toFixed(2)}
                </strong>

            </div>


            <div class="cart-actions">

                <button
                    type="button"
                    class="btn-secondary"
                    onclick="vaciarCarrito()"
                >
                    🗑️ Vaciar carrito
                </button>


                <button
                    type="button"
                    class="btn-primary"
                    onclick="realizarCompra()"
                >
                    💳 Realizar compra
                </button>

            </div>

        </div>

    `;

}



function cambiarCantidad(indice, cambio) {

    carrito[indice].cantidad += cambio;


    if (carrito[indice].cantidad <= 0) {
        carrito.splice(indice, 1);
    }


    guardarCarrito();

    mostrarCarrito();
}



function eliminarProducto(indice) {

    if (!confirm('¿Deseas eliminar este producto del carrito?')) {
        return;
    }

    carrito.splice(indice, 1);

    guardarCarrito();

    mostrarCarrito();
}


function vaciarCarrito() {

    if (carrito.length === 0) {
        return;
    }


    if (!confirm('¿Deseas vaciar todo el carrito?')) {
        return;
    }


    carrito = [];

    guardarCarrito();

    mostrarCarrito();
}



function guardarCarrito() {

    localStorage.setItem(
        'marketgo_carrito',
        JSON.stringify(carrito)
    );

}


function realizarCompra() {

    if (carrito.length === 0) {

        alert('Tu carrito está vacío.');

        return;
    }


    if (!confirm('¿Deseas confirmar esta compra?')) {
        return;
    }


    const formulario = document.createElement('form');

    formulario.method = 'POST';

    formulario.action = '../php/registrar_pedido.php';


    const campoCarrito = document.createElement('input');

    campoCarrito.type = 'hidden';

    campoCarrito.name = 'carrito';

    campoCarrito.value = JSON.stringify(carrito);


    formulario.appendChild(campoCarrito);

    document.body.appendChild(formulario);

    formulario.submit();

}



function escapeHTML(texto) {

    const div = document.createElement('div');

    div.textContent = texto;

    return div.innerHTML;
}



mostrarCarrito();

</script>

<script src="../js/ui.js" defer></script>
</body>

</html>
