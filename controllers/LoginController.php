<?php  

namespace Controllers;

use Classes\Email;
use Model\Usuarios;
use MVC\Router;

class LoginController {
    public static function login(Router $router) {
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuarios($_POST);

            $alertas = $auth->validarLogin();
            if(empty($alertas)) {
                //Comprobar que exitsta el email en la BD
                $usuario = Usuarios::where('email', $auth->email);
                
                if($usuario) {
                    //Verificar el password
                    if($usuario->comprobarPassword($auth->password)) {
                        //Autenticar si todo esta bien
                        session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        //Redireccionamiento si es Admin o no
                        if($usuario->admin === '1') {
                            //Es Admin
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        } else {
                            //Es Cliente
                            header('Location: /citas');
                        }
                    };
                } else {
                    Usuarios::setAlerta('error', 'Usuario no registrado aún');
                }
            }
        }

        $alertas = Usuarios::getAlertas();

        $router->render("auth/login", [
            'alertas' => $alertas
        ]);
    }

    public static function logout() {
        session_start();
        $_SESSION = [];
        header('Location: /');
    }
    
    public static function olvide(Router $router) {
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuarios($_POST);
            $alertas = $auth->validarEmail();

            if(empty($alertas)) {
                //Buscando el email ingresado en la BD
                $usuario = Usuarios::where('email', $auth->email);
                //Buscando q ese usuario este confirmado
                if($usuario && $usuario->confirmado === '1') {

                    //Generar un token
                    $usuario->crearToken();
                    $usuario->guardar();

                    //Mandar Email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    //Mostrar alerta de se mando un mensaje al mail
                    Usuarios::setAlerta('exito', 'Revisa tu email');
                } else {
                    //Ponen cualquier email o no esta confirmado aun
                    Usuarios::setAlerta('error', 'El email ingresado no esta confirmado aún o no existe');
                    
                }
            }
        }
        $alertas = Usuarios::getAlertas();
        $router->render("auth/olvide-password", [
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router) {
        $alertas = [];
        $error = false;

        $token = s($_GET['token']);

        //Buscar usuario por token
        $usuario = Usuarios::where('token', $token);    

        if(empty($usuario)) {
            Usuarios::setAlerta('error', 'Token no válido');
            $error = true;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Leer el nuevo password y guardarlo
            $password = new Usuarios($_POST);
            $alertas = $password->validarPasswordNuevo();

            if(empty($alertas)) {
                //Eliminamos la password vieja
                $usuario->password = null;

                //Hacemos que tome la password que el usuario escribio
                $usuario->password = $password->password;

                //La hasheamos
                $usuario->hashPassword();

                //Eliminamos el token temporal
                $usuario->token = null;

                //Al resultado los guardamos y si lo escribio correctamente en la BD lo redirigimos a iniciar sesion
                $resultado = $usuario->guardar();
                if($resultado) {
                    header('Location: /password-restablecido');
                }
            }

        }

        $alertas = Usuarios::getAlertas();
        $router->render("auth/recuperar-password", [
            'alertas' => $alertas,
            'error' =>$error
        ]);
    }

    public static function passwordRestablecido(Router $router) {
        $alertas = [];

        Usuarios::setAlerta('exito', 'Hemos Restablecido tu password, ahora puedes iniciar nuevamente sesión');

        $alertas = Usuarios::getAlertas();
        $router->render("auth/password-restablecido", [
            'alertas' => $alertas
        ]);
    }

    public static function crear(Router $router) {

        $usuario = new Usuarios;

        //alertas vacias
        $alertas = [];
        
        if($_SERVER['REQUEST_METHOD'] == 'POST'){ 
            $usuario -> sincronizar($_POST);
            $alertas = $usuario -> validarNuevaCuenta();    

            //Revisar que alertas este vacio
            if(empty($alertas)) {
                //Verificar que el usuario no este registrado
                $resultado = $usuario->existeUsuario();

                if($resultado->num_rows) {
                    $alertas = Usuarios::getAlertas();
                } else {
                    //Si el usuario no existe

                    //Hashear el pasword
                    $usuario->hashPassword();

                    //Generar token unico
                    $usuario->crearToken();

                    //Enviar Email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();

                    // Crear el usuario
                    $resultado = $usuario->guardar();
                    if($resultado) {
                        header('Location: /mensaje');
                    }
                    
                }
            }
        }

        $router->render("auth/crear-cuenta", [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router) {
        $router->render("auth/mensaje");
    }

    public static function confirmar(Router $router) {
        $alertas = [];

        $token = s($_GET['token']);

        $usuario = Usuarios::where('token', $token);
       
        if(empty($usuario)) {
            // Mostrar mensaje de error
            Usuarios::setAlerta('error', 'Token no válido');
        } else {
            // Mostrar a usuario authenticado
            $usuario->confirmado = "1";
            $usuario->token = null;
            $usuario->guardar();
            Usuarios::setAlerta('exito', 'La cuenta se activo correctamente');
        }
        $alertas = Usuarios::getAlertas();
        $router->render("auth/confirmar-cuenta", [
            'alertas' => $alertas
        ]);
    }
}