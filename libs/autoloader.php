<?php

spl_autoload_register(function ($class) {

    // Namespace
    $prefix = 'UNLu\\PAW\\';

    // Directorio base donde se encuentran los archivos del namespace
    $base_dir = __DIR__ . '/..';

    // Verifico si se está intentando carga una clase de este espacio de nombres
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // En caso que no, paso al siguiente autoloader
        return;
    }

    // Obtengo el nombre relativo de la clase
    $relative_class = substr($class, $len);      
    
    list($dir, $cn) = explode('\\', $relative_class);

    // Mapeo el nombre de espacios a una ruta del sistema de archivos
    $file = $base_dir . '/' . strtolower($dir) . '/' .str_replace('\\', '/', $cn) . '.php';
   
    // Verifico si existe el archivo
    if (file_exists($file)) {        
        require $file;
    }
});