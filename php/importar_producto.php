<?php
session_start();
require_once __DIR__ . "/conexion.php";

if (($_SESSION['rol'] ?? '') !== 'ADMIN') {
    header('Location: ../login.php');
    exit();
}

if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
    header('Location: ../admin/productos.php?error=archivo');
    exit();
}

$extension = strtolower(
    pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION)
);

if ($extension !== 'csv') {
    header('Location: ../admin/productos.php?error=formato');
    exit();
}

$handle = fopen($_FILES['archivo']['tmp_name'], 'r');

if ($handle === false) {
    header('Location: ../admin/productos.php?error=lectura');
    exit();
}

fgetcsv($handle);

$insertados = 0;
$errores = 0;

while (($fila = fgetcsv($handle, 1000, ',')) !== false) {
    if (count($fila) < 5) {
        $errores++;
        continue;
    }

    $nombre = trim($fila[0]);
    $descripcion = trim($fila[1]);
    $precio = (float)$fila[2];
    $stock = (int)$fila[3];
    $categoria = trim($fila[4]);

    if ($nombre === '' || $precio < 0 || $stock < 0) {
        $errores++;
        continue;
    }

    $stmt = $conn->prepare(
        "SELECT id FROM categorias
         WHERE (codigo = ? OR nombre = ?) AND estado = 'ACTIVA'
         LIMIT 1"
    );
    $stmt->bind_param("ss", $categoria, $categoria);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) {
        $errores++;
        continue;
    }

    $categoria_id = (int)$res->fetch_assoc()['id'];

    $res = $conn->query(
        "SELECT MAX(CAST(SUBSTRING(codigo, 2) AS UNSIGNED)) AS ultimo
         FROM productos
         WHERE codigo REGEXP '^P[0-9]+$'"
    );
    $filaCodigo = $res->fetch_assoc();
    $siguiente = ((int)($filaCodigo['ultimo'] ?? 0)) + 1;
    $codigoProducto = 'P' . str_pad($siguiente, 3, '0', STR_PAD_LEFT);

    $stmt = $conn->prepare(
        "INSERT INTO productos
         (codigo, nombre, descripcion, categoria_id, precio, stock, estado)
         VALUES (?, ?, ?, ?, ?, ?, 'ACTIVO')"
    );
    $stmt->bind_param(
        "sssidi",
        $codigoProducto,
        $nombre,
        $descripcion,
        $categoria_id,
        $precio,
        $stock
    );

    if ($stmt->execute()) {
        $insertados++;
    } else {
        $errores++;
    }
}

fclose($handle);

header(
    "Location: ../admin/productos.php?mensaje=importados&cantidad=$insertados&errores=$errores"
);
exit();
?>
