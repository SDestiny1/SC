<?php
echo "<h2>Verificando ZipArchive en el servidor web</h2>";

if (class_exists('ZipArchive')) {
    echo "<p style='color: green;'>✓ ZipArchive está disponible</p>";
    
    try {
        $zip = new ZipArchive();
        echo "<p style='color: green;'>✓ ZipArchive se puede instanciar correctamente</p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ Error al instanciar ZipArchive: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p style='color: red;'>✗ ZipArchive NO está disponible</p>";
}

echo "<h3>Extensiones PHP relacionadas con ZIP:</h3>";
$extensions = get_loaded_extensions();
foreach ($extensions as $ext) {
    if (strpos($ext, 'zip') !== false) {
        echo "<p>- $ext</p>";
    }
}

echo "<h3>Información de PHP:</h3>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>SAPI: " . php_sapi_name() . "</p>";
echo "<p>php.ini location: " . php_ini_loaded_file() . "</p>";
?> 