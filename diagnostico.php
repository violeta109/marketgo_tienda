<?php
echo "<h3>Diagnóstico de PHP</h3>";

echo "Versión de PHP: " . PHP_VERSION . "<br>";
echo "Archivo php.ini cargado: " . (php_ini_loaded_file() ?: "No encontrado") . "<br>";
echo "Carpeta de extensiones: " . ini_get('extension_dir') . "<br>";
echo "MySQLi cargado: " . (extension_loaded('mysqli') ? "SÍ" : "NO") . "<br>";

echo "Clase mysqli disponible: " . (class_exists('mysqli') ? "SÍ" : "NO") . "<br>";
?>