<?php

session_start();

if (!isset($_SESSION["usuario_id"])) {

    header("Location: login.php");

    exit();
}

$rol = strtoupper($_SESSION["rol"]);

switch ($rol) {

    case "ADMIN":

        header("Location: admin/dashboard.php");

        break;


    case "EMPLEADO":

        header("Location: empleado/dashboard.php");

        break;


    case "CLIENTE":

        header("Location: cliente/dashboard.php");

        break;


    default:

        session_destroy();

        header("Location: login.php");

        break;
}

exit();

?>