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


$codigoOriginal = trim(
    $_POST['codigo_original'] ?? ''
);

$codigo = trim(
    $_POST['codigo'] ?? ''
);

$nombre = trim(
    $_POST['nombre'] ?? ''
);

$ruc = trim(
    $_POST['ruc'] ?? ''
);

$telefono = trim(
    $_POST['telefono'] ?? ''
);

$correo = trim(
    $_POST['correo'] ?? ''
);

$direccion = trim(
    $_POST['direccion'] ?? ''
);

$contacto = trim(
    $_POST['contacto'] ?? ''
);

$estado = trim(
    $_POST['estado'] ?? 'ACTIVO'
);


if (
    $codigoOriginal === '' ||
    $codigo === '' ||
    $nombre === ''
) {

    $_SESSION['proveedor_error'] =
        'El código y el nombre son obligatorios.';

    header('Location: ../admin/proveedores.php');
    exit();
}


if (!in_array(
    $estado,
    ['ACTIVO', 'INACTIVO'],
    true
)) {

    $_SESSION['proveedor_error'] =
        'El estado seleccionado no es válido.';

    header('Location: ../admin/proveedores.php');
    exit();
}



$stmt = $conn->prepare(
    "SELECT id
     FROM proveedores
     WHERE codigo = ?
     AND codigo <> ?
     LIMIT 1"
);

$stmt->bind_param(
    'ss',
    $codigo,
    $codigoOriginal
);

$stmt->execute();


if ($stmt->get_result()->num_rows > 0) {

    $_SESSION['proveedor_error'] =
        'El nuevo código ya pertenece a otro proveedor.';

    header('Location: ../admin/proveedores.php');
    exit();
}


$stmt = $conn->prepare(
    "UPDATE proveedores
     SET
        codigo = ?,
        nombre = ?,
        ruc = ?,
        telefono = ?,
        correo = ?,
        direccion = ?,
        contacto = ?,
        estado = ?
     WHERE codigo = ?"
);


$stmt->bind_param(
    'sssssssss',
    $codigo,
    $nombre,
    $ruc,
    $telefono,
    $correo,
    $direccion,
    $contacto,
    $estado,
    $codigoOriginal
);


if ($stmt->execute()) {

    $_SESSION['proveedor_exito'] =
        'Proveedor actualizado correctamente.';

} else {

    $_SESSION['proveedor_error'] =
        'No se pudo actualizar el proveedor: ' .
        $stmt->error;

}


header('Location: ../admin/proveedores.php');

exit();