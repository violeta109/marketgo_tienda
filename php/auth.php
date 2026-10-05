<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function usuario_autenticado(): bool
{
    return isset($_SESSION["usuario_id"]);
}

function rol_actual(): string
{
    return $_SESSION["rol"] ?? "";
}

function exigir_login(string $rutaSalida = "../login.php"): void
{
    if (!usuario_autenticado()) {
        header("Location: " . $rutaSalida);
        exit();
    }
}

function exigir_rol(string $rol, string $rutaSalida = "../login.php"): void
{
    exigir_login($rutaSalida);

    if (strtoupper(rol_actual()) !== strtoupper($rol)) {
        header("Location: " . $rutaSalida);
        exit();
    }
}

?>