<?php
session_start();
require_once __DIR__ . "/conexion.php";

if (($_SESSION['rol'] ?? '') !== 'ADMIN') {
    header('Location: ../login.php');
    exit();
}

$codigo = trim($_GET['codigo'] ?? $_POST['codigo'] ?? '');

if ($codigo === '') {
    $_SESSION['producto_error'] = 'No se indicó el producto.';
    header('Location: ../admin/productos.php');
    exit();
}


$stmt = $conn->prepare(
    "UPDATE productos SET estado = 'INACTIVO' WHERE codigo = ?"
);
$stmt->bind_param("s", $codigo);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    $_SESSION['producto_exito'] =
        'Producto desactivado correctamente.';
} else {
    $_SESSION['producto_error'] =
        'No se encontró el producto o ya estaba inactivo.';
}

header('Location: ../admin/productos.php');
exit();
?>
