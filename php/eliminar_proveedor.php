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


$codigo = trim(
    $_POST['codigo'] ?? ''
);


if ($codigo === '') {

    $_SESSION['proveedor_error'] =
        'No se indicó el proveedor.';

    header('Location: ../admin/proveedores.php');
    exit();
}


$stmt = $conn->prepare(
    "UPDATE proveedores
     SET estado = 'INACTIVO'
     WHERE codigo = ?"
);


$stmt->bind_param(
    's',
    $codigo
);


if ($stmt->execute()) {

    if ($stmt->affected_rows > 0) {

        $_SESSION['proveedor_exito'] =
            'Proveedor desactivado correctamente.';

    } else {

        $_SESSION['proveedor_error'] =
            'No se encontró el proveedor o ya estaba inactivo.';

    }

} else {

    $_SESSION['proveedor_error'] =
        'No se pudo desactivar el proveedor: ' .
        $stmt->error;

}


header('Location: ../admin/proveedores.php');

exit();