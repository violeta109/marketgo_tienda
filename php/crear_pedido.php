<?php

session_start();

require_once "conexion.php";

if (
    !isset($_SESSION["usuario_id"]) ||
    $_SESSION["rol"] !== "CLIENTE"
) {
    header("Location: ../login.php");
    exit();
}

$usuario_id = $_SESSION["usuario_id"];

$producto_id = intval($_POST["producto_id"] ?? 0);
$cantidad = intval($_POST["cantidad"] ?? 0);

if ($producto_id <= 0 || $cantidad <= 0) {
    header("Location: ../cliente/productos.php?error=datos");
    exit();
}

$conn->begin_transaction();

try {

    // Buscar producto
    $sql = "SELECT id, nombre, precio, stock
            FROM productos
            WHERE id = ?
            FOR UPDATE";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $producto_id);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 0) {
        throw new Exception("Producto no encontrado.");
    }

    $producto = $resultado->fetch_assoc();

    // Verificar stock
    if ($producto["stock"] < $cantidad) {
        throw new Exception("Stock insuficiente.");
    }

    // Calcular total
    $subtotal = $producto["precio"] * $cantidad;
    $total = $subtotal;

    // Crear pedido
    $sql = "INSERT INTO pedidos
            (usuario_id, total, estado)
            VALUES (?, ?, 'PENDIENTE')";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("id", $usuario_id, $total);
    $stmt->execute();

    $pedido_id = $conn->insert_id;

    $sql = "INSERT INTO detalle_pedidos
            (pedido_id, producto_id, cantidad, precio, subtotal)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "iiidd",
        $pedido_id,
        $producto_id,
        $cantidad,
        $producto["precio"],
        $subtotal
    );

    $stmt->execute();

    $sql = "UPDATE productos
            SET stock = stock - ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ii",
        $cantidad,
        $producto_id
    );
    $stmt->execute();

    $conn->commit();

    header(
        "Location: ../cliente/mis_pedidos.php?mensaje=pedido_ok"
    );

    exit();

} catch (Exception $e) {

    $conn->rollback();

    header(
        "Location: ../cliente/productos.php?error=pedido"
    );

    exit();
}

?>