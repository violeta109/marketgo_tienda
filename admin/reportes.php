<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'ADMIN') {
    header('Location: ../login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reportes - MarketGo</title>

    <link rel="stylesheet" href="../css/style.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="../css/page-admin-reportes.css">
</head>

<body>

<div class="dashboard-container">

    <!-- MENÚ LATERAL -->
    <aside class="sidebar">

        <div class="logo">
            <span class="brand-mark">🛒</span>

            <span>
                Market<spanclass="mg-inline-1">Go</span>
            </span>
        </div>


        <nav class="sidebar-menu">

            <a href="dashboard.php">
                <span>📊</span>
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

            <a href="usuarios.php">
                <span>👥</span>
                Usuarios
            </a>

            <a href="pedidos.php">
                <span>🛍️</span>
                Pedidos
            </a>

            <a href="reportes.php" class="active">
                <span>📈</span>
                Reportes
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

                <h1>Reportes</h1>

                <p>
                    Consulta las estadísticas y resultados de MarketGo.
                </p>

            </div>


            <div class="user-profile">

                <div class="user-avatar">
                    👤
                </div>

                <div class="user-info">

                    <strong>
                        <?php
                        echo isset($_SESSION['nombre'])
                            ? htmlspecialchars($_SESSION['nombre'])
                            : 'Administrador';
                        ?>
                    </strong>

                    <span>
                        Administrador
                    </span>

                </div>

            </div>

        </header>


        <!-- FILTRO -->
        <section class="content-section">

            <div class="section-header">

                <div>

                    <h2>
                        Reporte estadístico
                    </h2>

                    <p>
                        comportamiento de las ventas y pedidos.
                    </p>

                </div>


                <div class="section-actions">

                    <select
                        id="periodo"
                        class="filter-select"
                        onchange="actualizarReporte()">

                        <option value="mes">
                            Este mes
                        </option>

                        <option value="semana">
                            Esta semana
                        </option>

                        <option value="anio">
                            Este año
                        </option>

                    </select>

                </div>

            </div>


            <!-- TARJETAS ESTADÍSTICAS -->
            <div class="stats-grid">

                <div class="stat-card">

                    <div class="stat-icon">
                        💰
                    </div>

                    <div>

                        <span>
                            Ventas totales
                        </span>

                        <h2>
                            S/ 12,580
                        </h2>

                    </div>

                </div>


                <div class="stat-card">

                    <div class="stat-icon">
                        🛍️
                    </div>

                    <div>

                        <span>
                            Pedidos realizados
                        </span>

                        <h2>
                            48
                        </h2>

                    </div>

                </div>


                <div class="stat-card">

                    <div class="stat-icon">
                        👥
                    </div>

                    <div>

                        <span>
                            Clientes
                        </span>

                        <h2>
                            32
                        </h2>

                    </div>

                </div>


                <div class="stat-card">

                    <div class="stat-icon">
                        📦
                    </div>

                    <div>

                        <span>
                            Productos vendidos
                        </span>

                        <h2>
                            156
                        </h2>

                    </div>

                </div>

            </div>

        </section>


        <!-- GRÁFICOS -->
        <section class="reports-grid">


            <!-- GRÁFICO DE VENTAS -->
            <div class="report-card">

                <div class="report-card-header">

                    <div>

                        <h2>
                            Ventas por mes
                        </h2>

                        <p>
                            Evolución de las ventas.
                        </p>

                    </div>

                </div>


                <div class="chart-container">

                    <canvas id="ventasChart"></canvas>

                </div>

            </div>


            <!-- GRÁFICO DE PEDIDOS -->
            <div class="report-card">

                <div class="report-card-header">

                    <div>

                        <h2>
                            Estado de pedidos
                        </h2>

                        <p>
                            Distribución de pedidos.
                        </p>

                    </div>

                </div>


                <div class="chart-container">

                    <canvas id="pedidosChart"></canvas>

                </div>

            </div>


        </section>


        <!-- PRODUCTOS MÁS VENDIDOS -->
        <section class="content-section">

            <div class="section-header">

                <div>

                    <h2>
                        Productos más vendidos
                    </h2>

                    <p>
                        Productos con mayor cantidad de ventas.
                    </p>

                </div>

            </div>


            <div class="table-container">

                <table class="data-table">

                    <thead>

                        <tr>

                            <th>
                                Producto
                            </th>

                            <th>
                                Categoría
                            </th>

                            <th>
                                Cantidad vendida
                            </th>

                            <th>
                                Ingresos
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <tr>

                            <td>
                                Manzana
                            </td>

                            <td>
                                Frutas y verduras
                            </td>

                            <td>
                                85 unidades
                            </td>

                            <td>
                                S/ 170.00
                            </td>

                        </tr>


                        <tr>

                            <td>
                                Leche Gloria
                            </td>

                            <td>
                                Lácteos
                            </td>

                            <td>
                                72 unidades
                            </td>

                            <td>
                                S/ 288.00
                            </td>

                        </tr>


                        <tr>

                            <td>
                                Pan integral
                            </td>

                            <td>
                                Abarrotes
                            </td>

                            <td>
                                64 unidades
                            </td>

                            <td>
                                S/ 192.00
                            </td>

                        </tr>


                        <tr>

                            <td>
                                Agua mineral
                            </td>

                            <td>
                                Bebidas
                            </td>

                            <td>
                                58 unidades
                            </td>

                            <td>
                                S/ 116.00
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </section>


        <!-- BOTONES DE REPORTES -->
        <section class="content-section">

            <div class="section-header">

                <div>

                    <h2>
                        Generar reporte
                    </h2>

                    <p>
                        Descarga la información registrada en el sistema.
                    </p>

                </div>

            </div>


            <div class="report-buttons">

                <button
                    class="btn-primary"
                    onclick="generarExcel()">

                    📊
                    Descargar Excel

                </button>


                <button
                    class="btn-secondary"
                    onclick="generarPDF()">

                    📄
                    Descargar PDF

                </button>

            </div>

        </section>

    </main>

</div>


<script>

/* GRÁFICO  */

const ventasCanvas =
    document.getElementById('ventasChart');

const ventasChart =
    new Chart(ventasCanvas, {

        type: 'line',

        data: {

            labels: [
                'Enero',
                'Febrero',
                'Marzo',
                'Abril',
                'Mayo',
                'Junio'
            ],

            datasets: [

                {

                    label: 'Ventas',

                    data: [
                        8500,
                        9200,
                        10500,
                        9800,
                        11500,
                        12580
                    ],

                    borderWidth: 3,

                    tension: 0.4,

                    fill: false

                }

            ]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            plugins: {

                legend: {
                    display: true
                }

            },

            scales: {

                y: {

                    beginAtZero: true

                }

            }

        }

    });



/*  GRÁFICO DE PEDIDO */

const pedidosCanvas =
    document.getElementById('pedidosChart');

const pedidosChart =
    new Chart(pedidosCanvas, {

        type: 'doughnut',

        data: {

            labels: [
                'Entregados',
                'Pendientes',
                'En proceso',
                'Cancelados'
            ],

            datasets: [

                {

                    data: [
                        28,
                        12,
                        8,
                        4
                    ],

                    borderWidth: 2

                }

            ]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            plugins: {

                legend: {

                    position: 'bottom'

                }

            }

        }

    });



function actualizarReporte() {

    const periodo =
        document.getElementById('periodo').value;

    if (periodo === 'semana') {

        alert(
            'El reporte semanal se conectará con MySQL posteriormente.'
        );

    }

    if (periodo === 'anio') {

        alert(
            'El reporte anual se conectará con MySQL posteriormente.'
        );

    }

}



/* EXCEL*/

function generarExcel() {

    alert(
        'La generación del archivo Excel se conectará con MySQL posteriormente.'
    );

}



/*  PDF*/

function generarPDF() {

    alert(
        'La generación del archivo PDF se conectará con MySQL posteriormente.'
    );

}

</script>


<script src="../js/ui.js" defer></script>
</body>
</html>