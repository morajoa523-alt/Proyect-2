<?php

class Router
{
    public $rutas = [];
    public $rutasController = [];

    public function __construct()
    {
        $this->rutas = ['inicio', 'user','preferencias','datosTotales', 'update'];
        $this->rutasController = ['interfacesController', 'userController','preferenciasController',
    'totalesPorcentajesController', 'insertAnio'];
    }
}

?>
