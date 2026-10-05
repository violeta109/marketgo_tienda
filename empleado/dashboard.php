<?php
session_start();
require_once __DIR__ . '/../php/auth.php';
require_once __DIR__ . '/../php/conexion.php';
exigir_rol("EMPLEADO", "../login.php");

function totalConsulta(mysqli $conn, string $sql): int
{
  $resultado = $conn->query($sql);
  if (!$resultado) return 0;
  $fila = $resultado->fetch_assoc();
  return (int)($fila['total'] ?? 0);
}
$productos = totalConsulta($conn, "SELECT COUNT(*) AS total FROM productos");
$stock_bajo = totalConsulta($conn, "SELECT COUNT(*) AS total FROM productos WHERE stock <= 5");
$pedidos = totalConsulta($conn, "SELECT COUNT(*) AS total FROM pedidos");
$nombre = htmlspecialchars($_SESSION['nombre'] ?? 'Empleado', ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Panel de empleado - MarketGo</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/page-empleado-dashboard.css">
</head>

<body>
  <div class="app-shell">
    <aside class="sidebar">
      <div class="side-logo"><span class="brand-mark">🛒</span><span>Market<spanclass="mg-inline-1">Go</span></span></div>
      <nav class="side-nav" aria-label="Navegación principal">
        <a class="active" href="dashboard.php"><span class="side-icon">🏠</span><span>Inicio</span></a>
        <a href="productos.php"><span class="side-icon">📦</span><span>Productos</span></a>
        <a href="stock.php"><span class="side-icon">⚠️</span><span>Control de stock</span></a>
        <a href="pedidos.php"><span class="side-icon">🛒</span><span>Pedidos</span></a>
        <a href="perfil.php"><span class="side-icon">👤</span><span>Mi perfil</span></a>
      </nav>
      <div class="logout"><a href="../php/logout.php"><span class="side-icon">🚪</span><span>Cerrar sesión</span></a></div>
    </aside>
    <main class="main-area">
      <header class="topbar">
        <div class="topbar-title">Panel de empleado</div>
        <div class="profile">
          <div class="avatar">👤</div>
          <div><strong><?= $nombre ?></strong><br><small>Empleado</small></div>
        </div>
      </header>
      <section class="content">
        <div class="welcome">
          <h1>¡Bienvenido, <?= $nombre ?>!</h1>
          <p>Resumen de inventario y operaciones de MarketGo.</p>
        </div>
        <div class="stat-grid">
          <div class="stat-card">
            <div class="stat-icon">📦</div>
            <div><small>Productos registrados</small><strong><?= number_format($productos) ?></strong></div>
          </div>
          <div class="stat-card">
            <div class="stat-icon">⚠️</div>
            <div><small>Productos con stock bajo</small><strong><?= number_format($stock_bajo) ?></strong></div>
          </div>
          <div class="stat-card">
            <div class="stat-icon">🛒</div>
            <div><small>Pedidos</small><strong><?= number_format($pedidos) ?></strong></div>
          </div>
        </div>
        <div class="page-head">
          <div>
            <h1>Accesos rápidos</h1>
            <p>Selecciona el módulo que necesitas utilizar.</p>
          </div>
        </div>
        <div class="stat-grid">
          <a class="panel-box" href="productos.php">
            <h3>📦 Productos</h3>
            <p>Consultar y administrar productos.</p>
          </a>
          <a class="panel-box" href="stock.php">
            <h3>⚠️ Control de stock</h3>
            <p>Revisar existencias y niveles bajos.</p>
          </a>
          <a class="panel-box" href="pedidos.php">
            <h3>🛒 Pedidos</h3>
            <p>Consultar pedidos registrados.</p>
          </a>
        </div>
      </section>
    </main>
  </div>
  <script src="../js/ui.js" defer></script>
</body>

</html>