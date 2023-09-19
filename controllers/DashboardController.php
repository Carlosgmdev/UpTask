<?php 

namespace Controllers;

use MVC\Router;
use Model\Usuario;
use Model\Proyecto;

class DashboardController {

    public static function index(Router $router) {

        session_start();
        isAuth();

        $proyectos = Proyecto::belongsTo('propietarioId', $_SESSION['id']);
        
        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
        ]);
    }

    public static function crear(Router $router) {

        session_start();
        isAuth();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $proyecto = new Proyecto($_POST);
            $alertas = $proyecto->validarProyecto();
            if(empty($alertas)) {
                $proyecto->generarUrl();
                $proyecto->propietarioId = $_SESSION['id'];
                $proyecto->guardar();
                header('Location: /proyecto?id='.$proyecto->url);
            }
        }


        $router->render('dashboard/crear-proyecto',[
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas
        ]);
    }

    public static function perfil(Router $router) {

        session_start();
        isAuth();
        $alertas = [];
        $usuario = Usuario::find($_SESSION['id']);

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validar_perfil();
            if(empty($alertas)) {
                $existeUsuario = Usuario::where('email',$usuario->email);
                if($existeUsuario && $existeUsuario->id !== $usuario->id) {
                    Usuario::setAlerta('error', 'EL CORREO YA ESTA REGISTRADO EN OTRA CUENTA');
                    $alertas = Usuario::getAlertas();
                } else {
                    $usuario->guardar();
                    Usuario::setAlerta('exito', 'CAMBIOS GUARDADOS');
                    $alertas = Usuario::getAlertas();
                    $_SESSION['nombre'] = $usuario->nombre;
                }
            }
        }

        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function proyecto(Router $router) {

        session_start();
        isAuth();

        $token = $_GET['id'];
        if(!$token) header('Location: /dashboard');
        $proyecto = Proyecto::where('url',$token);
        if($proyecto->propietarioId !== $_SESSION['id']) {
            header('Location: /dashboard');
        }
        
        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto
        ]);
    }

    public static function cambiar_password(Router $router) {
        session_start();
        isAuth();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = Usuario::find($_SESSION['id']);
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarPassword();
            if(empty($alertas)) {
                $usuario->hashPassword();
                $usuario->guardar();
                Usuario::setAlerta('exito', 'CONTRASEÑA ACTUALIZADA CON EXITO');
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('dashboard/cambiar-password', [
            'titulo' => 'Cambiar Contraseña',
            'alertas' => $alertas
        ]);
    }
}