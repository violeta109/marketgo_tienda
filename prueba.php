<?php

echo "<h2>Diagnóstico de MarketGo</h2>";

echo "Versión PHP: " . PHP_VERSION . "<br>";

if (extension_loaded('mysqli')) {
    echo "MySQLi: HABILITADO correctamente.<br>";
} else {
    echo "MySQLi: NO está habilitado.<br>";
}

if (class_exists('mysqli')) {
    echo "Clase mysqli: DISPONIBLE.<br>";
} else {
    echo "Clase mysqli: NO DISPONIBLE.<br>";
}

echo "<br>Archivo php.ini utilizado:<br>";
echo php_ini_loaded_file();