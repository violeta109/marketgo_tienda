<?php

session_start();

require_once __DIR__ . '/conexion.php';


if (
    !isset($_SESSION['usuario_id']) ||
    ($_SESSION['rol'] ?? '') !== 'ADMIN'
) {
    header('Location: ../login.php');
    exit();
}


$codigo = trim($_POST['codigo'] ?? '');
$nombre = trim($_POST['nombre'] ?? '');
$ruc = trim($_POST['ruc'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');
$contacto = trim($_POST['contacto'] ?? '');


if ($codigo === '' || $nombre === '') {

    $_SESSION['proveedor_error'] =
        'El código y el nombre son obligatorios.';

    header('Location: ../admin/proveedores.php');
    exit();
}


$stmt = $conn->prepare(
    "SELECT id
     FROM proveedores
     WHERE codigo = ?
     LIMIT 1"
);

$stmt->bind_param(
    's',
    $codigo
);

$stmt->execute();


if ($stmt->get_result()->num_rows > 0) {

    $_SESSION['proveedor_error'] =
        'El código del proveedor ya existe.';

    header('Location: ../admin/proveedores.php');
    exit();
}



$stmt = $conn->prepare(
    "INSERT INTO proveedores
    (
        codigo,
        nombre,
        ruc,
        telefono,
        correo,
        direccion,
        contacto,
        estado
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, 'ACTIVO')"
);


$stmt->bind_param(
    'sssssss',
    $codigo,
    $nombre,
    $ruc,
    $telefono,
    $correo,
    $direccion,
    $contacto
);


if ($stmt->execute()) {

    $_SESSION['proveedor_exito'] =
        'Proveedor registrado correctamente.';

} else {

    $_SESSION['proveedor_error'] =
        'No se pudo registrar el proveedor: ' .
        $stmt->error;

}


header('Location: ../admin/proveedores.php');

exit();