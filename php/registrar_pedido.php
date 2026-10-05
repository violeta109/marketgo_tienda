<?php
session_start();
require_once __DIR__ . "/conexion.php";

if (($_SESSION['rol'] ?? '') !== 'CLIENTE') {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../cliente/carrito.php');
    exit();
}

$carrito = $_POST['carrito'] ?? '';
$productosCarrito = json_decode($carrito, true);

if (!is_array($productosCarrito) || count($productosCarrito) === 0) {
    $_SESSION['pedido_error'] = 'El carrito está vacío.';
    header('Location: ../cliente/carrito.php');
    exit();
}

$conn->begin_transaction();

try {
    $usuario_id = (int)$_SESSION['usuario_id'];
    $productosPedido = [];
    $total = 0;

    foreach ($productosCarrito as $item) {
        $codigo = trim($item['codigo'] ?? '');
        $cantidad = (int)($item['cantidad'] ?? 0);

        if ($codigo === '' || $cantidad < 1) {
            continue;
        }

        $stmt = $conn->prepare(
            "SELECT id, nombre, precio, stock
             FROM productos
             WHERE codigo = ? AND estado = 'ACTIVO'
             FOR UPDATE"
        );
        $stmt->bind_param("s", $codigo);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 0) {
            throw new Exception("El producto $codigo no existe o está inactivo.");
        }

        $producto = $res->fetch_assoc();

        if ((int)$producto['stock'] < $cantidad) {
            throw new Exception(
                'Stock insuficiente para ' . $producto['nombre'] .
                '. Disponible: ' . $producto['stock']
            );
        }

        $precio = (float)$producto['precio'];
        $subtotal = $precio * $cantidad;
        $total += $subtotal;

        $productosPedido[] = [
            'id' => (int)$producto['id'],
            'codigo' => $codigo,
            'nombre' => $producto['nombre'],
            'precio' => $precio,
            'cantidad' => $cantidad,
            'subtotal' => $subtotal,
            'stock_anterior' => (int)$producto['stock']
        ];
    }

    if (count($productosPedido) === 0) {
        throw new Exception('No hay productos válidos en el carrito.');
    }

    /* Generar código de pedido */
    $res = $conn->query(
        "SELECT MAX(CAST(SUBSTRING(codigo, 2) AS UNSIGNED)) AS ultimo
         FROM pedidos
         WHERE codigo REGEXP '^P[0-9]+$'"
    );
    $fila = $res->fetch_assoc();
    $siguiente = ((int)($fila['ultimo'] ?? 0)) + 1;
    $codigoPedido = 'P' . str_pad($siguiente, 3, '0', STR_PAD_LEFT);

    $stmt = $conn->prepare(
        "INSERT INTO pedidos (codigo, usuario_id, total, estado, observacion)
         VALUES (?, ?, ?, 'PENDIENTE', '')"
    );
    $stmt->bind_param("sid", $codigoPedido, $usuario_id, $total);
    $stmt->execute();
    $pedido_id = $conn->insert_id;

    foreach ($productosPedido as $producto) {
        $stmt = $conn->prepare(
            "INSERT INTO detalle_pedido
             (pedido_id, producto_id, cantidad, precio, subtotal)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            "iiidd",
            $pedido_id,
            $producto['id'],
            $producto['cantidad'],
            $producto['precio'],
            $producto['subtotal']
        );
        $stmt->execute();

        $stockNuevo = $producto['stock_anterior'] - $producto['cantidad'];

        $stmt = $conn->prepare(
            "UPDATE productos SET stock = ? WHERE id = ?"
        );
        $stmt->bind_param("ii", $stockNuevo, $producto['id']);
        $stmt->execute();

        $stmt = $conn->prepare(
            "INSERT INTO movimientos_stock
             (producto_id, usuario_id, tipo, cantidad, stock_anterior, stock_nuevo, referencia, motivo)
             VALUES (?, ?, 'SALIDA', ?, ?, ?, ?, 'Venta/pedido')"
        );
        $stmt->bind_param(
            "iiiiis",
            $producto['id'],
            $usuario_id,
            $producto['cantidad'],
            $producto['stock_anterior'],
            $stockNuevo,
            $codigoPedido
        );
        $stmt->execute();
    }

    $stmt = $conn->prepare(
        "INSERT INTO ventas (pedido_id, usuario_id, total, estado)
         VALUES (?, ?, ?, 'REGISTRADA')"
    );
    $stmt->bind_param("iid", $pedido_id, $usuario_id, $total);
    $stmt->execute();
    $venta_id = $conn->insert_id;

    foreach ($productosPedido as $producto) {
        $stmt = $conn->prepare(
            "INSERT INTO detalle_venta
             (venta_id, producto_id, cantidad, precio, subtotal)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            "iiidd",
            $venta_id,
            $producto['id'],
            $producto['cantidad'],
            $producto['precio'],
            $producto['subtotal']
        );
        $stmt->execute();
    }

    $stmt = $conn->prepare(
        "INSERT INTO historial_pedidos
         (pedido_id, estado_anterior, estado_nuevo, observacion, usuario_id)
         VALUES (?, NULL, 'PENDIENTE', '', ?)"
    );
    $stmt->bind_param("ii", $pedido_id, $usuario_id);
    $stmt->execute();

    $conn->commit();

    $_SESSION['pedido_exito'] =
        "Compra realizada correctamente. Pedido $codigoPedido.";

    header('Location: ../cliente/pedidos_c.php');
    exit();

} catch (Throwable $e) {
    $conn->rollback();

    $_SESSION['pedido_error'] = $e->getMessage();
    header('Location: ../cliente/carrito.php');
    exit();
}
?>
