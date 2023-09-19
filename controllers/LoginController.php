<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {

    public static function login(Router $router) {

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();
            if(empty($alertas)) {
                $usuario = Usuario::where('email',$auth->email);
                if(!$usuario || !$usuario->confirmado) {
                    Usuario::setAlerta('error', 'EL USUARIO NO EXISTE O NO ESTA CONFIRMADO.');
                } else {
                    if(password_verify($_POST['password'], $usuario->password)) {
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;
                        header('Location: /dashboard');
                    } else {
                        Usuario::setAlerta('error', 'CONTRASEÑA INCORRECTA.');
                    }
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
            'titulo' => 'Iniciar sesión',
            'alertas' => $alertas
        ]);
    }

    public static function logout() {
        session_start();
        session_destroy();
        header('Location: /');
    }

    public static function create(Router $router) {
        
        $alertas = [];
        $usuario = new Usuario;

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();
            if(empty($alertas)) {
                $existeUsuario = Usuario::where('email',$usuario->email);
                if($existeUsuario) {
                    Usuario::setAlerta('error', 'EL CORREO YA ESTA REGISTRADO EN OTRA CUENTA');
                } else {
                    $usuario->hashPassword();
                    unset($usuario->password2);
                    $usuario->generarToken();
                    $resultado = $usuario->guardar();
                    $email = new Email($usuario->email,$usuario->nombre,$usuario->token);
                    $email->enviarConfirmacion();
                    if($resultado) {
                        header('Location: /message');
                    }
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/create', [
            'titulo' => 'Crear cuenta',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function forgot(Router $router) {
        
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();
            if(empty($alertas)) {
                $usuario = Usuario::where('email',$usuario->email);
                if($usuario && $usuario->confirmado === '1') {
                    $usuario->generarToken();
                    unset($usuario->password2);
                    $usuario->guardar();
                    $email = new Email($usuario->email,$usuario->nombre,$usuario->token);
                    $email->enviarInstrucciones();
                    Usuario::setAlerta('exito', 'REVISA TU EMAIL, HEMOS ENVIADO LAS INSTRUCCIONES.');
                } else {
                    Usuario::setAlerta('error','EL EMAIL NO ESTA ASOCIADO A NINGUNA CUENTA O NO ESTA CONFIRMADO.');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/forgot', [
            'titulo' => 'Olvidé mi contraseña',
            'alertas' => $alertas
        ]);
    }

    public static function recovery(Router $router) {
        
        $alertas = [];
        $mostrar = true;
        $token = s($_GET['token']);

        if(!$token) {
            header('Location: /');
        }

        $usuario = Usuario::where('token',$token);

        if(empty($usuario)) {
            Usuario::setAlerta('error', 'LO SENTIMOS, TOKEN NO VALIDO.');
            $mostrar = false;
        } 

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarPassword();
            if(empty($alertas)) {
                $usuario->hashPassword();
                $usuario->token = null;
                unset($usuario->password2);
                $resultado = $usuario->guardar();
                if($resultado) {
                    header('Location: /');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/recovery', [
            'titulo' => 'Reestablecer contraseña',
            'alertas' => $alertas,
            'mostrar' => $mostrar
        ]);
    }

    public static function message(Router $router) {
        

        $router->render('auth/message', [
            'titulo' => 'Revisa tu email.'
        ]);
    }

    public static function confirm(Router $router) {
        
        $token = s($_GET['token']);

        if(!$token) {
            header('Location: /');
        }

        $usuario = Usuario::where('token', $token);

        if(empty($usuario)) {
            Usuario::setAlerta('error', 'LO SENTIMOS, TOKEN INVALIDO.');
        } else {
            $usuario->confirmado = '1';
            $usuario->token = null;
            unset($usuario->password2);
            $usuario->guardar();
            Usuario::setAlerta('exito', 'CUENTA CREADA Y CONFIRMADA EXITOSAMENTE, YA PUEDES INICIAR SESION.');
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/confirm', [
            'titulo' => 'Confirma tu cuenta',
            'alertas' => $alertas
        ]);
    }

}