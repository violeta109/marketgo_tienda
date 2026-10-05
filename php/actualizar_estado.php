<?php

session_start();


$codigo      = trim($_POST['codigo'] ?? '');
$nombre      = trim($_POST['nombre'] ?? '');
$categoria   = trim($_POST['categoria'] ?? '');
$precio      = trim($_POST['precio'] ?? '');
$stock       = trim($_POST['stock'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');



if (
    empty($codigo) ||
    empty($nombre) ||
    empty($categoria) ||
    $precio === '' ||
    $stock === ''
) {
    $_SESSION['producto_error'] =
        'Completa todos los campos obligatorios.';

    header('Location: ../admin/productos.php');
    exit();
}

if (!is_numeric($precio) || $precio < 0) {

    $_SESSION['producto_error'] =
        'El precio ingresado no es válido.';

    header('Location: ../admin/productos.php');
    exit();
}


if (!is_numeric($stock) || $stock < 0 || floor($stock) != $stock) {

    $_SESSION['producto_error'] =
        'El stock debe ser un número entero válido.';

    header('Location: ../admin/productos.php');
    exit();
}



if (!isset($_SESSION['productos_registrados'])) {

    $_SESSION['productos_registrados'] = [];
}


$productoEncontrado = false;

foreach ($_SESSION['productos_registrados'] as $indice => $producto) {

    if ($producto['codigo'] === $codigo) {

        $_SESSION['productos_registrados'][$indice] = [
            'codigo'      => $codigo,
            'nombre'      => $nombre,
            'categoria'   => $categoria,
            'precio'      => (float)$precio,
            'stock'       => (int)$stock,
            'descripcion' => $descripcion
        ];

        $productoEncontrado = true;

        break;
    }
}


if ($productoEncontrado) {

    $_SESSION['producto_exito'] =
        'Producto actualizado correctamente.';

} else {

    $_SESSION['producto_error'] =
        'No se encontró el producto seleccionado.';
}


header('Location: ../admin/productos.php');
exit();

?>