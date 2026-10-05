<?php
session_start();
require_once __DIR__ . "/conexion.php";

if (($_SESSION['rol'] ?? '') !== 'ADMIN') {
    header('Location: ../login.php');
    exit();
}

$identificacion = trim($_GET['identificacion'] ?? $_POST['identificacion'] ?? '');

if ($identificacion === '') {
    $_SESSION['usuario_error'] = 'No se indicó el usuario.';
    header('Location: ../admin/usuarios.php');
    exit();
}

if ((string)$_SESSION['usuario_id'] === $identificacion) {
    $_SESSION['usuario_error'] =
        'No puedes eliminar el usuario con el que estás conectado.';
    header('Location: ../admin/usuarios.php');
    exit();
}


$stmt = $conn->prepare(
    "UPDATE usuarios SET estado = 'INACTIVO'
     WHERE identificacion = ?"
);
$stmt->bind_param("s", $identificacion);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    $_SESSION['usuario_exito'] =
        'Usuario desactivado correctamente.';
} else {
    $_SESSION['usuario_error'] =
        'No se encontró el usuario o ya estaba inactivo.';
}

header('Location: ../admin/usuarios.php');
exit();
?>
