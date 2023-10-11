<?php 

namespace Controllers;

use MVC\Router;

class CitasController {
    public static function index(Router $router) {

        session_start();

        isAuth();

        $router->render('citas/index', [
            'nombre' => $_SESSION['nombre'],
            'id' => $_SESSION['id']
        ]);
    }
}