<?php
session_start();
require_once __DIR__ . "/conexion.php";

if (($_SESSION['rol'] ?? '') !== 'ADMIN') {
    header('Location: ../login.php');
    exit();
}

$codigo = trim($_GET['codigo'] ?? $_POST['codigo'] ?? '');

if ($codigo === '') {
    $_SESSION['pedido_error'] = 'No se indicó el pedido.';
    header('Location: ../admin/pedidos.php');
    exit();
}

$stmt = $conn->prepare(
    "UPDATE pedidos
     SET estado = 'CANCELADO',
         observacion = 'Cancelado por administrador'
     WHERE codigo = ?"
);
$stmt->bind_param("s", $codigo);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    $_SESSION['pedido_exito'] =
        "El pedido $codigo fue cancelado correctamente.";
} else {
    $_SESSION['pedido_error'] =
        "No se encontró el pedido $codigo.";
}

header('Location: ../admin/pedidos.php');
exit();
?>
