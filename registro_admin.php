<?php

$error = $_GET['error'] ?? '';

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Registro de administrador - MarketGo
    </title>

    <link
        rel="stylesheet"
        href="css/style.css"
    >

<link rel="stylesheet" href="css/page-registro_admin.css">
</head>

<body class="auth-page">

<div
   
  
 class="auth-card mg-inline-1">

    <div
       
      
     class="brand-title mg-inline-2">

        <span class="brand-mark">
            🛒
        </span>

        Market<spanclass="mg-inline-3">
            Go
        </span>

    </div>


    <h2>
        Registro de administrador
    </h2>

    <p class="subtitle">
        Crea una cuenta de administrador para controlar el sistema.
    </p>


    <?php if ($error): ?>

        <div class="message error">

            <?php

            echo match ($error) {

                'complete' =>
                'Completa todos los campos.',

                'correo' =>
                'Ingresa un correo válido.',

                'password' =>
                'La contraseña debe tener mínimo 6 caracteres.',

                'existe' =>
                'El usuario, DNI o correo ya está registrado.',

                default =>
                'Revisa los datos ingresados.'
            };

            ?>

        </div>

    <?php endif; ?>


    <form
        class="auth-form"
        action="php/registrar_admin.php"
        method="POST"
    >

        <div class="form-grid">


            <div class="field">

                <label>
                    Nombre *
                </label>

                <input
                    name="nombre"
                    placeholder="Tu nombre"
                    required
                >

            </div>


            <div class="field">

                <label>
                    Apellido *
                </label>

                <input
                    name="apellido"
                    placeholder="Tu apellido"
                    required
                >

            </div>


            <div class="field">

                <label>
                    DNI *
                </label>

                <input
                    name="identificacion"
                    placeholder="Tu DNI"
                    required
                >

            </div>


            <div class="field">

                <label>
                    Correo electrónico *
                </label>

                <input
                    type="email"
                    name="correo"
                    placeholder="Tu correo electrónico"
                    required
                >

            </div>


            <div class="field">

                <label>
                    Usuario *
                </label>

                <input
                    name="usuario"
                    placeholder="Tu usuario"
                    required
                >

            </div>


            <div class="field">

                <label>
                    Contraseña *
                </label>

                <input
                    type="password"
                    name="password"
                    placeholder="Tu contraseña"
                    minlength="6"
                    required
                >

            </div>


            <div class="field full">

                <label>
                    Rol
                </label>

                <select disabled>

                    <option>
                        Administrador
                    </option>

                </select>

                <input
                    type="hidden"
                    name="rol"
                    value="ADMIN"
                >

            </div>

        </div>


        <div class="role-note">

            <strong>
                Administrador
            </strong>

            Tendrá control de modulos.

        </div>


        <button type="submit">

            Registrar

        </button>

    </form>


    <div class="auth-footer">

        <a href="login.php">
            Volver a iniciar sesión
        </a>

    </div>

</div>

<script src="js/ui.js" defer></script>
</body>

</html>