<?php

class Controller
{
    protected $layout = 'default';
    protected $title = '';

    protected function render($view, $data = [])
    {
        // 1. Extraer variables para que estén disponibles en la vista
        // EXTR_SKIP evita que se sobrescriban variables internas del método
        extract($data, EXTR_SKIP);

        // 2. Ruta al archivo de la vista
        $viewPath = "../app/views/pages/" . $view . ".php";

        if (file_exists($viewPath)) {
            // 3. (Opcional) Almacenar contenido en un buffer si usas Layouts
            ob_start();
            require $viewPath;
            $content = ob_get_clean();

            // 4. Cargar el layout principal y pasarle el contenido
            $layoutPath = "../app/views/layouts/" . $this->layout . ".php";
            
            if (file_exists($layoutPath)) {
                require $layoutPath;
            } else {
                // Si no hay layout, imprimimos el contenido directamente
                echo $content;
            }
        } else {
            die("Error: La vista '$view' no se encuentra en $viewPath");
        }
    }
}