<?php
echo "Verificando ZipArchive...\n";

if (class_exists('ZipArchive')) {
    echo "✓ ZipArchive está disponible\n";
    
    $zip = new ZipArchive();
    echo "✓ ZipArchive se puede instanciar correctamente\n";
} else {
    echo "✗ ZipArchive NO está disponible\n";
}

echo "\nExtensiones PHP cargadas:\n";
$extensions = get_loaded_extensions();
foreach ($extensions as $ext) {
    if (strpos($ext, 'zip') !== false) {
        echo "- $ext\n";
    }
}

echo "\nInformación de PHP:\n";
echo "PHP Version: " . phpversion() . "\n";
echo "SAPI: " . php_sapi_name() . "\n";
?> 