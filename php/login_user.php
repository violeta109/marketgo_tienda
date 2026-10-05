<?php
session_start();

require_once __DIR__ . '/conexion.php';

// Recibir datos del formulario
$identificador = trim($_POST['identificacion'] ?? '');
$password = $_POST['password'] ?? '';

// Validar campos
if ($identificador === '' || $password === '') {
    $_SESSION['login_error'] = 'Ingrese su correo o identificación y contraseña.';
    header('Location: ../login.php');
    exit;
}

// Buscar usuario por correo o identificación
$sql = "SELECT id, identificacion, nombre, apellido, correo, password, rol, estado
        FROM usuarios
        WHERE correo = ? OR identificacion = ?
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $identificador, $identificador);
$stmt->execute();

$resultado = $stmt->get_result();

if (!$resultado || $resultado->num_rows === 0) {
    $_SESSION['login_error'] = 'Correo, identificación o contraseña incorrectos.';
    $stmt->close();
    header('Location: ../login.php');
    exit;
}

$usuario = $resultado->fetch_assoc();
$stmt->close();

// Verificar estado
if (strtoupper($usuario['estado']) !== 'ACTIVO') {
    $_SESSION['login_error'] = 'La cuenta se encuentra inactiva.';
    header('Location: ../login.php');
    exit;
}

// Verificar contraseña
if (!password_verify($password, $usuario['password'])) {
    $_SESSION['login_error'] = 'Correo, identificación o contraseña incorrectos.';
    header('Location: ../login.php');
    exit;
}

// Validar rol
$rol = strtoupper($usuario['rol']);

if (!in_array($rol, ['ADMIN', 'EMPLEADO', 'CLIENTE'], true)) {
    $_SESSION['login_error'] = 'El rol de usuario no es válido.';
    header('Location: ../login.php');
    exit;
}

// Crear sesión
session_regenerate_id(true);

$_SESSION['usuario_id'] = (int) $usuario['id'];
$_SESSION['identificacion'] = $usuario['identificacion'];
$_SESSION['nombre'] = $usuario['nombre'];
$_SESSION['apellido'] = $usuario['apellido'];
$_SESSION['correo'] = $usuario['correo'];
$_SESSION['rol'] = $rol;

unset($_SESSION['login_error']);

// Redirigir según el rol
if ($rol === 'ADMIN') {
    header('Location: ../admin/dashboard.php');
} elseif ($rol === 'EMPLEADO') {
    header('Location: ../empleado/dashboard.php');
} else {
    header('Location: ../cliente/dashboard.php');
}

exit;
?>