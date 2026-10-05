<?php

session_start();

require_once "conexion.php";

if (!isset($_SESSION["usuario_id"]) || $_SESSION["rol"] !== "CLIENTE") {
    header("Location: ../login.php");
    exit();
}

$producto_id = intval($_POST["producto_id"] ?? 0);
$cantidad = intval($_POST["cantidad"] ?? 0);

if ($producto_id <= 0 || $cantidad <= 0) {
    header("Location: ../cliente/productos.php?error=datos");
    exit();
}

/* Buscar*/

$sql = "SELECT id, nombre, precio, stock
        FROM productos
        WHERE id = ?
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $producto_id);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    header("Location: ../cliente/productos.php?error=producto");
    exit();
}

$producto = $resultado->fetch_assoc();

if ($producto["stock"] < $cantidad) {
    header("Location: ../cliente/productos.php?error=stock");
    exit();
}

$total = $producto["precio"] * $cantidad;

/* stock*/

$sql = "UPDATE productos
        SET stock = stock - ?
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $cantidad, $producto_id);
$stmt->execute();

header("Location: ../cliente/carrito.php?mensaje=agregado");
exit();

?>