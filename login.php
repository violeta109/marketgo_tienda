
<?php
session_start();

// Redireccionar usuarios que ya iniciaron sesión.
if (isset($_SESSION['usuario_id'])) {

    $rol = $_SESSION['rol'] ?? '';

    if ($rol === 'ADMIN') {
        header('Location: admin/dashboard.php');
        exit();
    }

    if ($rol === 'EMPLEADO') {
        header('Location: empleado/dashboard.php');
        exit();
    }

    if ($rol === 'CLIENTE') {
        header('Location: cliente/dashboard.php');
        exit();
    }
}

$error = $_GET['error'] ?? '';
$registro = $_GET['registro'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Iniciar sesión - MarketGo</title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/page-login.css">
</head>

<body class="auth-page login-bg">

<div class="auth-layout">

    <!-- PARTE IZQUIERDA -->
    <section class="auth-brand">

        <div class="brand-title">
            <span class="brand-mark">🛒</span>

            <span>
                Market<span class="mg-inline-1">Go</span>
            </span>
        </div>

        <div class="benefits">

            <div class="benefit">
                <div class="benefit-icon">✓</div>
                <div>
                    <strong>Productos de calidad</strong>
                </div>
            </div>

            <div class="benefit">
                <div class="benefit-icon">$</div>
                <div>
                    <strong>Precios accesibles</strong>
                </div>
            </div>

            <div class="benefit">
                <div class="benefit-icon">🛍</div>
                <div>
                    <strong>Compra fácil y segura</strong>
                </div>
            </div>

        </div>

    </section>

    <!-- FORMULARIO -->
    <section class="auth-card">

        <h2>Iniciar sesión</h2>

        <!-- MENSAJES DE ERROR -->
        <?php if ($error): ?>

            <div class="message error">

                <?php
                echo match ($error) {

                    'complete' =>
                    'Completa todos los campos.',

                    'datos' =>
                    'Correo, identificación o contraseña incorrectos.',

                    'rol' =>
                    'El rol del usuario no es válido.',

                    'inactivo' =>
                    'Tu cuenta se encuentra inactiva.',

                    default =>
                    'Ocurrió un error. Inténtalo nuevamente.'
                };
                ?>

            </div>

        <?php endif; ?>

        <!-- MENSAJE DE REGISTRO EXITOSO -->
        <?php if ($registro === 'ok'): ?>

            <div class="message ok">
                Registro realizado correctamente. Ya puedes iniciar sesión.
            </div>

        <?php endif; ?>

        <!-- FORMULARIO DE ACCESO -->
        <form
            class="auth-form"
            action="php/login_user.php"
            method="POST"
        >

            <label for="identificacion">
                Correo electrónico o identificación
            </label>

            <input
                type="text"
                id="identificacion"
                name="identificacion"
                placeholder="Ingresa tu correo o identificación"
                required
                autocomplete="username"
                autofocus
            >

            <label for="password">
                Contraseña
            </label>

            <div class="password-wrapper">

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Ingresa tu contraseña"
                    required
                    autocomplete="current-password"
                >

                <button
                    type="button"
                    id="togglePassword"
                    class="password-toggle"
                    aria-label="Mostrar contraseña"
                    aria-pressed="false"
                >
                    👁
                </button>

            </div>

            <button type="submit">
                Ingresar
            </button>

        </form>

        <div class="auth-footer">

            ¿No tienes una cuenta?

            <a href="registro.php">
                Regístrate aquí
            </a>

        </div>

    </section>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const passwordInput = document.getElementById('password');
    const toggleButton = document.getElementById('togglePassword');

    toggleButton.addEventListener('click', function () {

        const mostrar = passwordInput.type === 'password';

        passwordInput.type = mostrar ? 'text' : 'password';

        toggleButton.textContent = mostrar ? '👁' : '👁';

        toggleButton.setAttribute(
            'aria-label',
            mostrar ? 'Ocultar contraseña' : 'Mostrar contraseña'
        );

        toggleButton.setAttribute('aria-pressed', String(mostrar));
    });

});
</script>

<script src="js/ui.js" defer></script>

</body>
</html>