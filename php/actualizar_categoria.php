<?php
session_start();
require_once __DIR__ . "/conexion.php";

if (($_SESSION['rol'] ?? '') !== 'ADMIN') {
    header('Location: ../login.php');
    exit();
}

$codigo      = trim($_POST['codigo'] ?? '');
$nombre      = trim($_POST['nombre'] ?? '');
$estado      = trim($_POST['estado'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');

if ($codigo === '' || $nombre === '' || $estado === '') {
    $_SESSION['categoria_error'] = 'Completa los campos obligatorios.';
    header('Location: ../admin/categorias.php');
    exit();
}

if (!in_array($estado, ['ACTIVA', 'INACTIVA'], true)) {
    $_SESSION['categoria_error'] = 'El estado no es válido.';
    header('Location: ../admin/categorias.php');
    exit();
}

$stmt = $conn->prepare(
    "SELECT id FROM categorias WHERE nombre = ? AND codigo <> ? LIMIT 1"
);
$stmt->bind_param("ss", $nombre, $codigo);
$stmt->execute();

if ($stmt->get_result()->num_rows > 0) {
    $_SESSION['categoria_error'] = 'Ya existe otra categoría con ese nombre.';
    header('Location: ../admin/categorias.php');
    exit();
}

$stmt = $conn->prepare(
    "UPDATE categorias
     SET nombre = ?, descripcion = ?, estado = ?
     WHERE codigo = ?"
);
$stmt->bind_param("ssss", $nombre, $descripcion, $estado, $codigo);

if ($stmt->execute() && $stmt->affected_rows >= 0) {
    $_SESSION['categoria_exito'] = 'Categoría actualizada correctamente.';
} else {
    $_SESSION['categoria_error'] = 'No se pudo actualizar la categoría.';
}

header('Location: ../admin/categorias.php');
exit();
?>
