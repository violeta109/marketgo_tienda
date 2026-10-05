<?php
session_start();

$error = $_SESSION['registro_error'] ?? '';
$exito = $_SESSION['registro_exito'] ?? '';

unset($_SESSION['registro_error']);
unset($_SESSION['registro_exito']);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Registro - MarketGo</title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/page-registro.css">
</head>

<body class="auth-page">

    <div class="auth-card">

        <div class="brand-title">
            <span class="brand-mark">🛒</span>
            Market<span class="mg-inline-3">Go</span>
        </div>

        <h2>Crear cuenta</h2>

        <?php if ($error): ?>
            <div class="message error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if ($exito): ?>
            <div class="message success">
                <?php echo htmlspecialchars($exito); ?>
            </div>
        <?php endif; ?>

        <form
            class="auth-form"
            action="php/registrar.php"
            method="POST"
        >

            <div class="form-grid">

                <!-- IDENTIFICACIÓN -->
                <div class="field">
                    <label for="identificacion">Identificación </label>

                    <input
                        type="text"
                        id="identificacion"
                        name="identificacion"
                        placeholder="Ingresa tu identificación"
                        required
                    >
                </div>

                <!-- NOMBRE -->
                <div class="field">
                    <label for="nombre">Nombre </label>

                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        placeholder="Tu nombre"
                        required
                    >
                </div>

                <!-- APELLIDO -->
                <div class="field">
                    <label for="apellido">Apellido </label>

                    <input
                        type="text"
                        id="apellido"
                        name="apellido"
                        placeholder="Tu apellido"
                        required
                    >
                </div>

                <!-- CORREO -->
                <div class="field">
                    <label for="correo">Correo electrónico </label>

                    <input
                        type="email"
                        id="correo"
                        name="correo"
                        placeholder="Tu correo electrónico"
                        required
                    >
                </div>

                <!-- CONTRASEÑA CON OJO -->
                <div class="field">
                    <label for="password">Contraseña </label>

                    <div class="password-wrapper">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Tu contraseña"
                            minlength="6"
                            autocomplete="new-password"
                            required
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            onclick="togglePassword()"
                            aria-label="Mostrar contraseña"
                            title="Mostrar contraseña"
                        >👁</button>
                    </div>
                </div>

                <!-- ROL -->
                <div class="field">
                    <label for="rol">Rol </label>

                    <select
                        name="rol"
                        id="rol"
                        required
                    >
                        <option value="">Selecciona un rol</option>
                        <option value="ADMIN">Administrador</option>
                        <option value="EMPLEADO">Empleado</option>
                        <option value="CLIENTE">Cliente</option>
                    </select>
                </div>

            </div>

            <!-- INFORMACIÓN DEL ROL -->
            <div class="role-note" id="roleNote"></div>

            <button type="submit">
                Registrarse
            </button>

        </form>

        <div class="auth-footer">
            ¿Ya tienes una cuenta?
            <a href="login.php">Inicia sesión</a>
        </div>

    </div>

    <script>
        // Mostrar u ocultar contraseña
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.querySelector('.password-toggle');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleButton.textContent = '👁';
                toggleButton.setAttribute('aria-label', 'Ocultar contraseña');
                toggleButton.setAttribute('title', 'Ocultar contraseña');
            } else {
                passwordInput.type = 'password';
                toggleButton.textContent = '👁';
                toggleButton.setAttribute('aria-label', 'Mostrar contraseña');
                toggleButton.setAttribute('title', 'Mostrar contraseña');
            }
        }

        // Información del rol
        const rol = document.getElementById('rol');
        const roleNote = document.getElementById('roleNote');

        rol.addEventListener('change', function () {
            if (this.value === 'ADMIN') {
                roleNote.innerHTML = '<strong>Administrador</strong>';
            } else if (this.value === 'EMPLEADO') {
                roleNote.innerHTML = '<strong>Empleado</strong>';
            } else if (this.value === 'CLIENTE') {
                roleNote.innerHTML = '<strong>Cliente</strong>';
            } else {
                roleNote.innerHTML =
                    '<strong>Selecciona un rol</strong><br>Elige el tipo de usuario que tendrá esta cuenta.';
            }
        });
    </script>

    <script src="js/ui.js" defer></script>

</body>
</html>