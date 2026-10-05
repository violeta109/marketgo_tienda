<?php
session_start();

require_once "../php/auth.php";
require_once "../php/conexion.php";

exigir_rol("ADMIN", "../login.php");

$id = intval($_GET["id"] ?? 0);

$sql = "SELECT * FROM productos WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    die("Producto no encontrado.");
}

$producto = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar producto</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<h1>Editar producto</h1>

<form action="../php/editar_producto.php" method="POST">

    <input type="hidden" name="id"
           value="<?= $producto["id"] ?>">

    <label>Nombre:</label><br>
    <input type="text"
           name="nombre"
           value="<?= htmlspecialchars($producto["nombre"]) ?>"
           required>

    <br><br>

    <label>Descripción:</label><br>

    <textarea name="descripcion"><?= htmlspecialchars($producto["descripcion"]) ?></textarea>

    <br><br>

    <label>Precio:</label><br>

    <input type="number"
           name="precio"
           step="0.01"
           value="<?= $producto["precio"] ?>"
           required>

    <br><br>

    <label>Stock:</label><br>

    <input type="number"
           name="stock"
           value="<?= $producto["stock"] ?>"
           required>

    <br><br>

    <label>Categoría:</label><br>

    <input type="text"
           name="categoria"
           value="<?= htmlspecialchars($producto["categoria"]) ?>">

    <br><br>

    <button type="submit">
        Guardar cambios
    </button>

</form>

<br>

<a href="productos.php">← Volver</a>

<script src="../js/ui.js" defer></script>
</body>
</html>