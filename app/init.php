<?php

require_once 'config/config.php';

spl_autoload_register(function($class){

    // Carpetas donde buscar clases automáticamente
    $directories = [
       __DIR__ . '/controllers/',
        __DIR__ . '/lib/',
        __DIR__ . '/helpers/',
        __DIR__ . '/config/',
         __DIR__ . '/models/'
    ];

    // Buscar la clase en cada directorio
    foreach ($directories as $dir) {
        $path = $dir . $class . '.php';

        if (is_readable($path)) {
            require_once $path;
            return; // Detener después de cargarlo
        }
    }

    // Mensaje opcional (útil si estás depurando)
    // echo "Clase no encontrada: $class<br>";
});
