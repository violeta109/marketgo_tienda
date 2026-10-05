<?php
session_start();
require_once __DIR__ . "/conexion.php";

if (($_SESSION['rol'] ?? '') !== 'ADMIN') {
    header('Location: ../login.php');
    exit();
}

$codigo = trim($_GET['codigo'] ?? $_POST['codigo'] ?? '');

if ($codigo === '') {
    $_SESSION['categoria_error'] = 'No se indicó la categoría.';
    header('Location: ../admin/categorias.php');
    exit();
}

$stmt = $conn->prepare(
    "SELECT COUNT(*) AS cantidad
     FROM productos p
     INNER JOIN categorias c ON c.id = p.categoria_id
     WHERE c.codigo = ?"
);
$stmt->bind_param("s", $codigo);
$stmt->execute();
$cantidad = (int)$stmt->get_result()->fetch_assoc()['cantidad'];

if ($cantidad > 0) {
    $stmt = $conn->prepare(
        "UPDATE categorias SET estado = 'INACTIVA' WHERE codigo = ?"
    );
    $stmt->bind_param("s", $codigo);
    $stmt->execute();
    $_SESSION['categoria_exito'] =
        'La categoría tiene productos y fue marcada como INACTIVA.';
} else {
    $stmt = $conn->prepare("DELETE FROM categorias WHERE codigo = ?");
    $stmt->bind_param("s", $codigo);
    $stmt->execute();

    $_SESSION['categoria_exito'] =
        $stmt->affected_rows > 0
        ? 'Categoría eliminada correctamente.'
        : 'No se encontró la categoría.';
}

header('Location: ../admin/categorias.php');
exit();
?>
