<?php
session_start();
require_once __DIR__ . "/conexion.php";

if (!in_array($_SESSION['rol'] ?? '', ['ADMIN','EMPLEADO'], true)) {
    header('Location: ../login.php');
    exit();
}

$codigo      = trim($_POST['codigo'] ?? '');
$estado      = trim($_POST['estado'] ?? '');
$observacion = trim($_POST['observacion'] ?? '');
$origen      = trim($_POST['origen'] ?? 'admin');

$paginaRegreso = $origen === 'empleado'
    ? '../empleado/pedidos.php'
    : '../admin/pedidos.php';

$estadosPermitidos = [
    'PENDIENTE',
    'EN PROCESO',
    'PREPARANDO',
    'ENVIADO',
    'ENTREGADO',
    'CANCELADO'
];

if ($codigo === '' || !in_array($estado, $estadosPermitidos, true)) {
    $_SESSION['pedido_error'] = 'Los datos del pedido no son válidos.';
    header('Location: ' . $paginaRegreso);
    exit();
}

$conn->begin_transaction();

try {
    $stmt = $conn->prepare(
        "SELECT id, estado FROM pedidos WHERE codigo = ? FOR UPDATE"
    );
    $stmt->bind_param("s", $codigo);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) {
        throw new Exception('No se encontró el pedido.');
    }

    $pedido = $res->fetch_assoc();
    $pedido_id = (int)$pedido['id'];
    $estadoAnterior = $pedido['estado'];

    $stmt = $conn->prepare(
        "UPDATE pedidos SET estado = ?, observacion = ? WHERE id = ?"
    );
    $stmt->bind_param("ssi", $estado, $observacion, $pedido_id);
    $stmt->execute();

    if ($estadoAnterior !== $estado || $observacion !== '') {
        $usuario_id = (int)$_SESSION['usuario_id'];

        $stmt = $conn->prepare(
            "INSERT INTO historial_pedidos
             (pedido_id, estado_anterior, estado_nuevo, observacion, usuario_id)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            "isssi",
            $pedido_id,
            $estadoAnterior,
            $estado,
            $observacion,
            $usuario_id
        );
        $stmt->execute();
    }

    $conn->commit();

    $_SESSION['pedido_exito'] =
        "El pedido $codigo fue actualizado correctamente.";

} catch (Throwable $e) {
    $conn->rollback();
    $_SESSION['pedido_error'] = $e->getMessage();
}

header('Location: ' . $paginaRegreso);
exit();
?>
