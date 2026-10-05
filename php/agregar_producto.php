<?php
session_start();
require_once __DIR__ . "/conexion.php";

if (($_SESSION['rol'] ?? '') !== 'ADMIN') {
    header('Location: ../login.php');
    exit();
}

$nombre      = trim($_POST['nombre'] ?? '');
$categoria   = trim($_POST['categoria'] ?? '');
$precio      = (float)($_POST['precio'] ?? 0);
$stock       = (int)($_POST['stock'] ?? 0);
$descripcion = trim($_POST['descripcion'] ?? '');

// La imagen se guarda como archivo; MySQL conserva únicamente su ruta.
$imagenRuta = null;
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE) {
    $archivo = $_FILES['imagen'];
    if ($archivo['error'] !== UPLOAD_ERR_OK || $archivo['size'] > 5 * 1024 * 1024) {
        $_SESSION['producto_error'] = 'La imagen no se pudo subir o supera 5 MB.';
        header('Location: ../admin/productos.php'); exit();
    }
    $info = @getimagesize($archivo['tmp_name']);
    $mime = $info['mime'] ?? '';
    $extensiones = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    if (!$info || !isset($extensiones[$mime])) {
        $_SESSION['producto_error'] = 'Formato no válido. Sube una imagen JPG, PNG o WEBP.';
        header('Location: ../admin/productos.php'); exit();
    }
    $directorio = dirname(__DIR__) . '/uploads/productos';
    if (!is_dir($directorio) && !mkdir($directorio, 0755, true) && !is_dir($directorio)) {
        $_SESSION['producto_error'] = 'No se pudo preparar la carpeta de imágenes.';
        header('Location: ../admin/productos.php'); exit();
    }
    $nombreArchivo = bin2hex(random_bytes(16)) . '.' . $extensiones[$mime];
    if (!move_uploaded_file($archivo['tmp_name'], $directorio . '/' . $nombreArchivo)) {
        $_SESSION['producto_error'] = 'No se pudo guardar la fotografía.';
        header('Location: ../admin/productos.php'); exit();
    }
    $imagenRuta = 'uploads/productos/' . $nombreArchivo;
}

if ($nombre === '' || $categoria === '' || $precio < 0 || $stock < 0) {
    $_SESSION['producto_error'] = 'Los datos del producto no son válidos.';
    header('Location: ../admin/productos.php');
    exit();
}

/* El formulario envía el nombre de la categoría. */
$stmt = $conn->prepare(
    "SELECT id FROM categorias
     WHERE (codigo = ? OR nombre = ?) AND estado = 'ACTIVA'
     LIMIT 1"
);
$stmt->bind_param("ss", $categoria, $categoria);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    $_SESSION['producto_error'] = 'La categoría no existe o está inactiva.';
    header('Location: ../admin/productos.php');
    exit();
}

$categoria_id = (int)$res->fetch_assoc()['id'];

$resultado = $conn->query(
    "SELECT MAX(CAST(SUBSTRING(codigo, 2) AS UNSIGNED)) AS ultimo
     FROM productos
     WHERE codigo REGEXP '^P[0-9]+$'"
);
$fila = $resultado->fetch_assoc();
$siguiente = ((int)($fila['ultimo'] ?? 0)) + 1;
$codigo = 'P' . str_pad($siguiente, 3, '0', STR_PAD_LEFT);

$stmt = $conn->prepare(
    "INSERT INTO productos
     (codigo, nombre, descripcion, categoria_id, precio, stock, imagen, estado)
     VALUES (?, ?, ?, ?, ?, ?, ?, 'ACTIVO')"
);
$stmt->bind_param(
    "sssidis",
    $codigo,
    $nombre,
    $descripcion,
    $categoria_id,
    $precio,
    $stock,
    $imagenRuta
);

if (!$stmt->execute()) {
    $_SESSION['producto_error'] = 'No se pudo registrar el producto.';
    header('Location: ../admin/productos.php');
    exit();
}

$producto_id = $conn->insert_id;

if ($stock > 0) {
    $usuario_id = (int)$_SESSION['usuario_id'];
    $stmt = $conn->prepare(
        "INSERT INTO movimientos_stock
         (producto_id, usuario_id, tipo, cantidad, stock_anterior, stock_nuevo, referencia, motivo)
         VALUES (?, ?, 'ENTRADA', ?, 0, ?, ?, 'Stock inicial')"
    );
    $stmt->bind_param("iiiis", $producto_id, $usuario_id, $stock, $stock, $codigo);
    $stmt->execute();
}

$_SESSION['producto_exito'] =
    'Producto ' . $codigo . ' registrado correctamente.';

header('Location: ../admin/productos.php');
exit();
?>
