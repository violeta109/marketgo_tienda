
<?php
session_start();
require_once __DIR__ . "/conexion.php";

if (($_SESSION['rol'] ?? '') !== 'ADMIN') {
    header('Location: ../login.php');
    exit();
}

$nombre      = trim($_POST['nombre'] ?? '');
$estado      = trim($_POST['estado'] ?? 'ACTIVA');
$descripcion = trim($_POST['descripcion'] ?? '');

if ($nombre === '') {
    $_SESSION['categoria_error'] = 'El nombre es obligatorio.';
    header('Location: ../admin/categorias.php');
    exit();
}

if (!in_array($estado, ['ACTIVA', 'INACTIVA'], true)) {
    $_SESSION['categoria_error'] = 'El estado no es válido.';
    header('Location: ../admin/categorias.php');
    exit();
}

$stmt = $conn->prepare("SELECT id FROM categorias WHERE nombre = ? LIMIT 1");
$stmt->bind_param("s", $nombre);
$stmt->execute();

if ($stmt->get_result()->num_rows > 0) {
    $_SESSION['categoria_error'] = 'Ya existe una categoría con ese nombre.';
    header('Location: ../admin/categorias.php');
    exit();
}

/* Generar código C001, C002, C003... */
$resultado = $conn->query(
    "SELECT MAX(CAST(SUBSTRING(codigo, 2) AS UNSIGNED)) AS ultimo
     FROM categorias
     WHERE codigo REGEXP '^C[0-9]+$'"
);
$fila = $resultado->fetch_assoc();
$siguiente = ((int)($fila['ultimo'] ?? 0)) + 1;
$codigo = 'C' . str_pad($siguiente, 3, '0', STR_PAD_LEFT);

$stmt = $conn->prepare(
    "INSERT INTO categorias (codigo, nombre, descripcion, estado)
     VALUES (?, ?, ?, ?)"
);
$stmt->bind_param("ssss", $codigo, $nombre, $descripcion, $estado);

if ($stmt->execute()) {
    $_SESSION['categoria_exito'] = 'Categoría registrada correctamente.';
} else {
    $_SESSION['categoria_error'] = 'No se pudo registrar la categoría.';
}

header('Location: ../admin/categorias.php');
exit();
?>
