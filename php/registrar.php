
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once __DIR__ . "/conexion.php";

// Evitar que errores técnicos se muestren al usuario.
ini_set('display_errors', '0');
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$db = $conexion ?? $conn ?? null;

// Función para regresar al formulario con un mensaje.
function regresarRegistro($mensaje) {
    $_SESSION['registro_error'] = $mensaje;
    header("Location: ../registro.php");
    exit();
}

// Verificar conexión.
if (!$db instanceof mysqli) {
    error_log("MarketGo: No se encontró una conexión MySQL válida.");
    regresarRegistro("No se pudo conectar con la base de datos.");
}

$identificacion = trim($_POST['identificacion'] ?? '');
$nombre         = trim($_POST['nombre'] ?? '');
$apellido       = trim($_POST['apellido'] ?? '');
$correo         = trim($_POST['correo'] ?? '');
$password       = $_POST['password'] ?? '';

$rol = 'CLIENTE';

if (
    $identificacion === '' ||
    $nombre === '' ||
    $apellido === '' ||
    $correo === '' ||
    $password === ''
) {
    regresarRegistro("Todos los campos son obligatorios.");
}

// Validar correo.
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    regresarRegistro("El correo electrónico no es válido.");
}

// Validar contraseña.
if (strlen($password) < 6) {
    regresarRegistro("La contraseña debe tener al menos 6 caracteres.");
}


try {

    $stmt = $db->prepare(
        "SELECT id
         FROM usuarios
         WHERE identificacion = ? OR correo = ?
         LIMIT 1"
    );

    $stmt->bind_param("ss", $identificacion, $correo);
    $stmt->execute();

    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();

        regresarRegistro(
            "La identificación o el correo ya se encuentran registrados."
        );
    }

    $stmt->close();

    // Proteger contraseña mediante hash.
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    if ($passwordHash === false) {
        throw new Exception("No se pudo generar el hash de contraseña.");
    }

    $stmt = $db->prepare(
        "INSERT INTO usuarios
        (
            identificacion,
            nombre,
            apellido,
            correo,
            password,
            rol,
            estado
        )
        VALUES (?, ?, ?, ?, ?, ?, 'ACTIVO')"
    );

    $stmt->bind_param(
        "ssssss",
        $identificacion,
        $nombre,
        $apellido,
        $correo,
        $passwordHash,
        $rol
    );

    $stmt->execute();

    $stmt->close();


    $_SESSION['registro_exito'] =
        "Registro realizado correctamente. Ya puedes iniciar sesión.";

    header("Location: ../login.php");
    exit();

} catch (Throwable $e) {

    error_log(
        "Error en registro MarketGo: " . $e->getMessage()
    );

    regresarRegistro(
        "No se pudo completar el registro. Verifica los datos e inténtalo nuevamente."
    );
}
?>