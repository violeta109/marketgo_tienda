<?php
session_start();
require_once __DIR__ . "/conexion.php";

if (($_SESSION['rol'] ?? '') !== 'ADMIN') {
    header('Location: ../login.php');
    exit();
}

$identificacion = trim($_POST['identificacion'] ?? '');
$nombre         = trim($_POST['nombre'] ?? '');
$apellido       = trim($_POST['apellido'] ?? '');
$correo         = trim($_POST['correo'] ?? '');
$rol            = trim($_POST['rol'] ?? '');
$estado         = trim($_POST['estado'] ?? 'ACTIVO');

if ($identificacion === '' || $nombre === '' || $apellido === '' || $correo === '' || $rol === '') {
    $_SESSION['usuario_error'] = 'Completa todos los campos obligatorios.';
    header('Location: ../admin/usuarios.php');
    exit();
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['usuario_error'] = 'El correo no es válido.';
    header('Location: ../admin/usuarios.php');
    exit();
}

if (!in_array($rol, ['ADMIN','EMPLEADO','CLIENTE'], true)) {
    $_SESSION['usuario_error'] = 'El rol no es válido.';
    header('Location: ../admin/usuarios.php');
    exit();
}

if (!in_array($estado, ['ACTIVO','INACTIVO'], true)) {
    $_SESSION['usuario_error'] = 'El estado no es válido.';
    header('Location: ../admin/usuarios.php');
    exit();
}

$stmt = $conn->prepare(
    "SELECT id FROM usuarios
     WHERE (correo = ? OR identificacion = ?)
       AND identificacion <> ?
     LIMIT 1"
);
$stmt->bind_param("sss", $correo, $identificacion, $identificacion);
$stmt->execute();

if ($stmt->get_result()->num_rows > 0) {
    $_SESSION['usuario_error'] =
        'El correo o identificación ya pertenece a otro usuario.';
    header('Location: ../admin/usuarios.php');
    exit();
}

$stmt = $conn->prepare(
    "UPDATE usuarios
     SET nombre = ?, apellido = ?, correo = ?, rol = ?, estado = ?
     WHERE identificacion = ?"
);
$stmt->bind_param(
    "ssssss",
    $nombre,
    $apellido,
    $correo,
    $rol,
    $estado,
    $identificacion
);
$stmt->execute();

if ($stmt->affected_rows >= 0) {
    $_SESSION['usuario_exito'] = 'Usuario actualizado correctamente.';

    if ((string)$_SESSION['usuario_id'] === $identificacion) {
        $_SESSION['nombre']   = $nombre;
        $_SESSION['apellido'] = $apellido;
        $_SESSION['correo']   = $correo;
        $_SESSION['rol']      = $rol;
    }
} else {
    $_SESSION['usuario_error'] = 'No se pudo actualizar el usuario.';
}

header('Location: ../admin/usuarios.php');
exit();
?>
