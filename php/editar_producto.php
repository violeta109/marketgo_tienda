<?php

session_start();

require_once "conexion.php";

if (!isset($_SESSION["usuario_id"]) || $_SESSION["rol"] !== "ADMIN") {
    header("Location: ../login.php");
    exit();
}

$id = intval($_POST["id"] ?? 0);
$nombre = trim($_POST["nombre"] ?? "");
$descripcion = trim($_POST["descripcion"] ?? "");
$precio = floatval($_POST["precio"] ?? 0);
$stock = intval($_POST["stock"] ?? 0);
$categoria = trim($_POST["categoria"] ?? "");

if ($id <= 0 || $nombre === "" || $precio <= 0 || $stock < 0) {
    header("Location: ../admin/productos.php?error=datos");
    exit();
}

$sql = "UPDATE productos
        SET nombre = ?,
            descripcion = ?,
            precio = ?,
            stock = ?,
            categoria = ?
        WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssdisi",
    $nombre,
    $descripcion,
    $precio,
    $stock,
    $categoria,
    $id
);

if ($stmt->execute()) {
    header("Location: ../admin/productos.php?mensaje=editado");
} else {
    header("Location: ../admin/productos.php?error=editar");
}

exit();

?>