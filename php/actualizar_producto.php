<?php
session_start();
require_once __DIR__ . "/conexion.php";

if (($_SESSION['rol'] ?? '') !== 'ADMIN') {
    header('Location: ../login.php');
    exit();
}

$codigo      = trim($_POST['codigo'] ?? '');
$nombre      = trim($_POST['nombre'] ?? '');
$categoria   = trim($_POST['categoria'] ?? '');
$precio      = (float)($_POST['precio'] ?? 0);
$stockNuevo  = (int)($_POST['stock'] ?? 0);
$descripcion = trim($_POST['descripcion'] ?? '');

$imagenRuta = null;
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE) {
    $archivo = $_FILES['imagen'];
    $info = ($archivo['error'] === UPLOAD_ERR_OK && $archivo['size'] <= 5 * 1024 * 1024) ? @getimagesize($archivo['tmp_name']) : false;
    $tipos = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    $mime = $info['mime'] ?? '';
    if (!$info || !isset($tipos[$mime])) {
        $_SESSION['producto_error'] = 'Imagen inválida: usa JPG, PNG o WEBP de máximo 5 MB.';
        header('Location: ../admin/productos.php'); exit();
    }
    $dir = dirname(__DIR__) . '/uploads/productos';
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    $fileName = bin2hex(random_bytes(16)) . '.' . $tipos[$mime];
    if (!move_uploaded_file($archivo['tmp_name'], $dir . '/' . $fileName)) {
        $_SESSION['producto_error'] = 'No se pudo guardar la nueva fotografía.';
        header('Location: ../admin/productos.php'); exit();
    }
    $imagenRuta = 'uploads/productos/' . $fileName;
}

if ($codigo === '' || $nombre === '' || $categoria === '' || $precio < 0 || $stockNuevo < 0) {
    $_SESSION['producto_error'] = 'Los datos enviados no son válidos.';
    header('Location: ../admin/productos.php');
    exit();
}

$conn->begin_transaction();

try {
    $stmt = $conn->prepare(
        "SELECT id FROM categorias
         WHERE (codigo = ? OR nombre = ?) AND estado = 'ACTIVA'
         LIMIT 1"
    );
    $stmt->bind_param("ss", $categoria, $categoria);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) {
        throw new Exception('Categoría no encontrada.');
    }

    $categoria_id = (int)$res->fetch_assoc()['id'];

    $stmt = $conn->prepare(
        "SELECT id, stock FROM productos WHERE codigo = ? FOR UPDATE"
    );
    $stmt->bind_param("s", $codigo);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) {
        throw new Exception('Producto no encontrado.');
    }

    $producto = $res->fetch_assoc();
    $producto_id = (int)$producto['id'];
    $stockAnterior = (int)$producto['stock'];

    if ($imagenRuta !== null) {
        $stmt = $conn->prepare(
            "UPDATE productos SET nombre = ?, descripcion = ?, categoria_id = ?, precio = ?, stock = ?, imagen = ? WHERE id = ?"
        );
        $stmt->bind_param("ssidisi", $nombre, $descripcion, $categoria_id, $precio, $stockNuevo, $imagenRuta, $producto_id);
    } else {
        $stmt = $conn->prepare(
            "UPDATE productos SET nombre = ?, descripcion = ?, categoria_id = ?, precio = ?, stock = ? WHERE id = ?"
        );
        $stmt->bind_param("ssidii", $nombre, $descripcion, $categoria_id, $precio, $stockNuevo, $producto_id);
    }
    $stmt->execute();

    if ($stockAnterior !== $stockNuevo) {
        $diferencia = abs($stockNuevo - $stockAnterior);
        $tipo = $stockNuevo > $stockAnterior ? 'ENTRADA' : 'SALIDA';
        $usuario_id = (int)$_SESSION['usuario_id'];

        $stmt = $conn->prepare(
            "INSERT INTO movimientos_stock
             (producto_id, usuario_id, tipo, cantidad, stock_anterior, stock_nuevo, referencia, motivo)
             VALUES (?, ?, ?, ?, ?, ?, ?, 'Actualización de producto')"
        );
        $stmt->bind_param(
            "iisiiis",
            $producto_id,
            $usuario_id,
            $tipo,
            $diferencia,
            $stockAnterior,
            $stockNuevo,
            $codigo
        );
        $stmt->execute();
    }

    $conn->commit();
    $_SESSION['producto_exito'] = 'Producto actualizado correctamente.';
} catch (Throwable $e) {
    $conn->rollback();
    $_SESSION['producto_error'] = $e->getMessage();
}

header('Location: ../admin/productos.php');
exit();
?>
