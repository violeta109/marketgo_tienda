<?php
session_start();
require_once __DIR__ . '/../php/conexion.php';

if (!isset($_SESSION['usuario_id']) || ($_SESSION['rol'] ?? '') !== 'ADMIN') {
    header('Location: ../login.php');
    exit();
}

$nombreUsuario = $_SESSION['nombre'] ?? 'Administrador';
$apellidoUsuario = $_SESSION['apellido'] ?? '';

$mensajeExito = $_SESSION['proveedor_exito'] ?? '';
$mensajeError = $_SESSION['proveedor_error'] ?? '';

unset($_SESSION['proveedor_exito'], $_SESSION['proveedor_error']);


/* OBTENER PROVEEDORES */

$proveedores = [];

$sql = "SELECT
            id,
            codigo,
            nombre,
            ruc,
            telefono,
            correo,
            direccion,
            contacto,
            estado
        FROM proveedores
        ORDER BY id DESC";

$resultado = $conn->query($sql);

if ($resultado) {
    $proveedores = $resultado->fetch_all(MYSQLI_ASSOC);
}


/*  ESTADÍSTICAS*/

$totalProveedores = count($proveedores);
$activos = 0;
$inactivos = 0;

foreach ($proveedores as $proveedor) {

    if (($proveedor['estado'] ?? '') === 'ACTIVO') {
        $activos++;
    } else {
        $inactivos++;
    }
}


/* ESTADO */

function estadoProveedor($estado)
{
    if ($estado === 'ACTIVO') {
        return ['Activo', 'entregado'];
    }

    return ['Inactivo', 'cancelado'];
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Proveedores - MarketGo</title>

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/page-admin-proveedores.css">

</head>


<body>

<div class="dashboard-container">


    <!--  SIDEBAR-->

    <aside class="sidebar">

        <div class="logo">

            <span class="brand-mark">🛒</span>

            <span>
                Market<span class="mg-inline-1">Go</span>
            </span>

        </div>


        <nav class="menu">

            <a href="dashboard.php">
                <span>🏠</span>
                Dashboard
            </a>


            <a href="productos.php">
                <span>📦</span>
                Productos
            </a>


            <a href="categorias.php">
                <span>🏷️</span>
                Categorías
            </a>


            <a href="proveedores.php" class="active">
                <span>🏢</span>
                Proveedores
            </a>


            <a href="usuarios.php">
                <span>👥</span>
                Usuarios
            </a>


            <a href="pedidos.php">
                <span>🛍️</span>
                Pedidos
            </a>


            <a href="reportes.php">
                <span>📊</span>
                Reportes
            </a>

        </nav>

    </aside>



    <!-- CONTENIDO PRINCIPAL -->

    <main class="main-content">


        <!-- TOPBAR -->

        <header class="topbar">

            <div>

                <h1>Proveedores</h1>

                <p>
                    Administra los proveedores de MarketGo
                </p>

            </div>


            <div class="user-info">

                <div class="avatar">

                    <?= strtoupper(
                        substr($nombreUsuario, 0, 1)
                    ); ?>

                </div>


                <div>

                    <strong>
                        <?= htmlspecialchars(
                            $nombreUsuario . ' ' . $apellidoUsuario
                        ); ?>
                    </strong>

                    <small>
                        Administrador
                    </small>

                </div>


                <a href="../php/logout.php"
                   title="Cerrar sesión">
                    🚪
                </a>

            </div>

        </header>



        <section class="content-area">


            <!--  MENSAJES-->

            <?php if ($mensajeExito !== ''): ?>

                <div class="mensaje-producto mensaje-exito">

                    <?= htmlspecialchars($mensajeExito); ?>

                </div>

            <?php endif; ?>


            <?php if ($mensajeError !== ''): ?>

                <div class="mensaje-producto mensaje-error">

                    <?= htmlspecialchars($mensajeError); ?>

                </div>

            <?php endif; ?>



            <!--  ESTADÍSTICAS -->

            <div class="stats-grid">


                <div class="stat-card">

                    <div class="stat-icon">
                        🏢
                    </div>

                    <div>

                        <span>
                            Total proveedores
                        </span>

                        <h2>
                            <?= $totalProveedores; ?>
                        </h2>

                    </div>

                </div>



                <div class="stat-card">

                    <div class="stat-icon">
                        ✅
                    </div>

                    <div>

                        <span>
                            Activos
                        </span>

                        <h2>
                            <?= $activos; ?>
                        </h2>

                    </div>

                </div>



                <div class="stat-card">

                    <div class="stat-icon">
                        ❌
                    </div>

                    <div>

                        <span>
                            Inactivos
                        </span>

                        <h2>
                            <?= $inactivos; ?>
                        </h2>

                    </div>

                </div>

            </div>



            <!-- CABECERA-->

            <div class="section-header">

                <div>

                    <h2>
                        Lista de proveedores
                    </h2>

                    <p>
                        Gestiona los proveedores registrados
                    </p>

                </div>


                <button
                    class="btn-primary"
                    onclick="mostrarFormulario()">

                    + Nuevo proveedor

                </button>

            </div>



            <!-- FORMULARIO-->

            <div
                id="formularioProveedor"
                class="formulario-producto">

                <div class="card">

                    <h3>
                        Registrar nuevo proveedor
                    </h3>


                    <form
                        action="../php/agregar_proveedor.php"
                        method="POST">


                        <div class="form-grid">


                            <div class="campo-producto">

                                <label>
                                    Código
                                </label>

                                <input
                                    type="text"
                                    name="codigo"
                                    placeholder="Ej. PROV001"
                                    required
                                >

                            </div>



                            <div class="campo-producto">

                                <label>
                                    Nombre del proveedor
                                </label>

                                <input
                                    type="text"
                                    name="nombre"
                                    placeholder="Nombre del proveedor"
                                    required
                                >

                            </div>



                            <div class="campo-producto">

                                <label>
                                    RUC
                                </label>

                                <input
                                    type="text"
                                    name="ruc"
                                    placeholder="RUC"
                                >

                            </div>



                            <div class="campo-producto">

                                <label>
                                    Teléfono
                                </label>

                                <input
                                    type="text"
                                    name="telefono"
                                    placeholder="Teléfono"
                                >

                            </div>



                            <div class="campo-producto">

                                <label>
                                    Correo
                                </label>

                                <input
                                    type="email"
                                    name="correo"
                                    placeholder="correo@empresa.com"
                                >

                            </div>



                            <div class="campo-producto">

                                <label>
                                    Contacto
                                </label>

                                <input
                                    type="text"
                                    name="contacto"
                                    placeholder="Persona de contacto"
                                >

                            </div>



                            <div class="campo-producto campo-completo">

                                <label>
                                    Dirección
                                </label>

                                <input
                                    type="text"
                                    name="direccion"
                                    placeholder="Dirección del proveedor"
                                >

                            </div>


                        </div>


                        <br>


                        <button
                            type="submit"
                            class="btn-primary">

                            Guardar proveedor

                        </button>


                        <button
                            type="button"
                            class="btn-secondary"
                            onclick="ocultarFormulario()">

                            Cancelar

                        </button>


                    </form>

                </div>

            </div>



            <!--  BUSCADOR -->

            <div class="card">

                <div class="filters">


                    <div class="search-box">

                        🔍

                        <input
                            type="text"
                            id="buscarProveedor"
                            placeholder="Buscar proveedor..."
                        >

                    </div>


                    <select id="filtroEstado">

                        <option value="">
                            Todos los estados
                        </option>

                        <option value="ACTIVO">
                            Activos
                        </option>

                        <option value="INACTIVO">
                            Inactivos
                        </option>

                    </select>


                </div>

            </div>



            <!-- TABLA-->

            <div class="card table-card">

                <div class="table-responsive">


                    <table class="data-table">


                        <thead>

                            <tr>

                                <th>
                                    Código
                                </th>

                                <th>
                                    Proveedor
                                </th>

                                <th>
                                    RUC
                                </th>

                                <th>
                                    Teléfono
                                </th>

                                <th>
                                    Correo
                                </th>

                                <th>
                                    Contacto
                                </th>

                                <th>
                                    Estado
                                </th>

                                <th>
                                    Acciones
                                </th>

                            </tr>

                        </thead>


                        <tbody id="tablaProveedores">


                        <?php if (empty($proveedores)): ?>

                            <tr>

                                <td
                                    colspan="8"
                                    style="text-align:center;">

                                    No hay proveedores registrados.

                                </td>

                            </tr>


                        <?php else: ?>


                            <?php foreach ($proveedores as $proveedor): ?>

                                <?php

                                [$estadoTexto, $claseEstado]
                                    = estadoProveedor(
                                        $proveedor['estado']
                                    );

                                ?>


                                <tr
                                    data-nombre="<?= htmlspecialchars(
                                        strtolower(
                                            $proveedor['nombre']
                                        )
                                    ); ?>"

                                    data-ruc="<?= htmlspecialchars(
                                        strtolower(
                                            $proveedor['ruc'] ?? ''
                                        )
                                    ); ?>"

                                    data-estado="<?= htmlspecialchars(
                                        $proveedor['estado']
                                    ); ?>"
                                >


                                    <td>

                                        <strong>
                                            <?= htmlspecialchars(
                                                $proveedor['codigo']
                                            ); ?>
                                        </strong>

                                    </td>


                                    <td>

                                        <?= htmlspecialchars(
                                            $proveedor['nombre']
                                        ); ?>

                                    </td>


                                    <td>

                                        <?= htmlspecialchars(
                                            $proveedor['ruc'] ?? ''
                                        ); ?>

                                    </td>


                                    <td>

                                        <?= htmlspecialchars(
                                            $proveedor['telefono'] ?? ''
                                        ); ?>

                                    </td>


                                    <td>

                                        <?= htmlspecialchars(
                                            $proveedor['correo'] ?? ''
                                        ); ?>

                                    </td>


                                    <td>

                                        <?= htmlspecialchars(
                                            $proveedor['contacto'] ?? ''
                                        ); ?>

                                    </td>


                                    <td>

                                        <span
                                            class="status <?= $claseEstado; ?>">

                                            <?= $estadoTexto; ?>

                                        </span>

                                    </td>


                                    <td>

                                        <div
                                            class="acciones-producto">


                                            <button
                                                type="button"
                                                class="btn-editar"
                                                onclick='editarProveedor(<?= json_encode(
                                                    $proveedor,
                                                    JSON_HEX_TAG |
                                                    JSON_HEX_APOS |
                                                    JSON_HEX_QUOT |
                                                    JSON_HEX_AMP
                                                ); ?>)'>

                                                ✏️ Editar

                                            </button>


                                            <form
                                                action="../php/eliminar_proveedor.php"
                                                method="POST"
                                                onsubmit="return confirmarEliminar('<?= htmlspecialchars(
                                                    $proveedor['nombre'],
                                                    ENT_QUOTES
                                                ); ?>')">


                                                <input
                                                    type="hidden"
                                                    name="codigo"
                                                    value="<?= htmlspecialchars(
                                                        $proveedor['codigo']
                                                    ); ?>"
                                                >


                                                <button
                                                    type="submit"
                                                    class="btn-eliminar">

                                                    🗑️ Desactivar

                                                </button>


                                            </form>


                                        </div>

                                    </td>


                                </tr>


                            <?php endforeach; ?>


                        <?php endif; ?>


                        </tbody>

                    </table>

                </div>

            </div>


        </section>

    </main>

</div>



<!--  MODAL EDITAR -->

<div
    id="modalEditar"
    class="modal-producto">


    <div class="modal-contenido">


        <div class="modal-header">

            <h2>
                Editar proveedor
            </h2>


            <button
                type="button"
                class="cerrar-modal"
                onclick="cerrarModal()">

                ×

            </button>

        </div>



        <form
            action="../php/actualizar_proveedor.php"
            method="POST">


            <input
                type="hidden"
                name="codigo_original"
                id="editarCodigoOriginal"
            >


            <div class="form-grid">


                <div class="campo-producto">

                    <label>
                        Código
                    </label>

                    <input
                        type="text"
                        name="codigo"
                        id="editarCodigo"
                        required
                    >

                </div>


                <div class="campo-producto">

                    <label>
                        Nombre
                    </label>

                    <input
                        type="text"
                        name="nombre"
                        id="editarNombre"
                        required
                    >

                </div>


                <div class="campo-producto">

                    <label>
                        RUC
                    </label>

                    <input
                        type="text"
                        name="ruc"
                        id="editarRuc"
                    >

                </div>


                <div class="campo-producto">

                    <label>
                        Teléfono
                    </label>

                    <input
                        type="text"
                        name="telefono"
                        id="editarTelefono"
                    >

                </div>


                <div class="campo-producto">

                    <label>
                        Correo
                    </label>

                    <input
                        type="email"
                        name="correo"
                        id="editarCorreo"
                    >

                </div>


                <div class="campo-producto">

                    <label>
                        Contacto
                    </label>

                    <input
                        type="text"
                        name="contacto"
                        id="editarContacto"
                    >

                </div>


                <div class="campo-producto campo-completo">

                    <label>
                        Dirección
                    </label>

                    <input
                        type="text"
                        name="direccion"
                        id="editarDireccion"
                    >

                </div>


                <div class="campo-producto">

                    <label>
                        Estado
                    </label>

                    <select
                        name="estado"
                        id="editarEstado">

                        <option value="ACTIVO">
                            Activo
                        </option>

                        <option value="INACTIVO">
                            Inactivo
                        </option>

                    </select>

                </div>


            </div>


            <br>


            <button
                type="submit"
                class="btn-primary">

                Guardar cambios

            </button>


            <button
                type="button"
                class="btn-secondary"
                onclick="cerrarModal()">

                Cancelar

            </button>


        </form>

    </div>

</div>



<script>

/*FORMULARIO*/

function mostrarFormulario() {

    document
        .getElementById('formularioProveedor')
        .classList.add('activo');

    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}


function ocultarFormulario() {

    document
        .getElementById('formularioProveedor')
        .classList.remove('activo');

}


/* EDITAR*/

function editarProveedor(proveedor) {

    document.getElementById('editarCodigoOriginal').value =
        proveedor.codigo || '';

    document.getElementById('editarCodigo').value =
        proveedor.codigo || '';

    document.getElementById('editarNombre').value =
        proveedor.nombre || '';

    document.getElementById('editarRuc').value =
        proveedor.ruc || '';

    document.getElementById('editarTelefono').value =
        proveedor.telefono || '';

    document.getElementById('editarCorreo').value =
        proveedor.correo || '';

    document.getElementById('editarContacto').value =
        proveedor.contacto || '';

    document.getElementById('editarDireccion').value =
        proveedor.direccion || '';

    document.getElementById('editarEstado').value =
        proveedor.estado || 'ACTIVO';


    document
        .getElementById('modalEditar')
        .classList.add('activo');

}


function cerrarModal() {

    document
        .getElementById('modalEditar')
        .classList.remove('activo');

}


function confirmarEliminar(nombre) {

    return confirm(
        '¿Seguro que deseas desactivar el proveedor "' +
        nombre +
        '"?'
    );

}


/* BUSCADOR */

document
    .getElementById('buscarProveedor')
    .addEventListener(
        'input',
        filtrarProveedores
    );


document
    .getElementById('filtroEstado')
    .addEventListener(
        'change',
        filtrarProveedores
    );


function filtrarProveedores() {

    const texto =
        document
            .getElementById('buscarProveedor')
            .value
            .toLowerCase()
            .trim();


    const estado =
        document
            .getElementById('filtroEstado')
            .value;


    const filas =
        document.querySelectorAll(
            '#tablaProveedores tr'
        );


    filas.forEach(function(fila) {

        const nombre =
            fila.dataset.nombre || '';

        const ruc =
            fila.dataset.ruc || '';

        const estadoFila =
            fila.dataset.estado || '';


        const coincideTexto =
            nombre.includes(texto) ||
            ruc.includes(texto);


        const coincideEstado =
            estado === '' ||
            estadoFila === estado;


        fila.style.display =
            coincideTexto && coincideEstado
                ? ''
                : 'none';

    });

}


/* CERRAR MODAL*/

document
    .getElementById('modalEditar')
    .addEventListener(
        'click',
        function(event) {

            if (event.target === this) {
                cerrarModal();
            }

        }
    );

</script>


<script src="../js/ui.js" defer></script>

</body>

</html>