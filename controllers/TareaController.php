<?php

namespace Controllers;

use Model\Proyecto;
use Model\Tarea;

class TareaController {

    public static function index() {
        $proyectoId = $_GET['id'];
        if(!$proyectoId) header('Location: /dashboard');
        $proyecto = Proyecto::where('url', $proyectoId);
        session_start();
        if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) header('Location: /404');
        $tareas = Tarea::belongsTo('proyectoId', $proyecto->id);
        echo json_encode(['tareas' => $tareas]);
    }

    public static function crear() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();

            $proyecto = Proyecto::where('url', $_POST['proyectoId']);
            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'HUBO UN ERROR AL CREAR LA TAREA'
                ];
                echo json_encode($respuesta);
                return;
            }
            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;
            $resultado = $tarea->guardar();
            $respuesta = [
                'tipo' => 'exito',
                'id' => $resultado['id'],
                'mensaje' => 'TAREA CREADA EXITOSAMENTE',
                'proyectoId' => $proyecto->id
            ];
            echo json_encode($respuesta);
        }
    }

    public static function actualizar() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            $proyecto = Proyecto::where('url', $_POST['proyectoId']);
            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'HUBO UN ERROR AL ACTUALIZAR LA TAREA'
                ];
                echo json_encode($respuesta);
                return;
            }
            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;
            $resultado = $tarea->guardar();
            if($resultado) {
                $respuesta = [
                    'tipo' => 'exito',
                    'id' => $tarea->id,
                    'proyectoId' => $proyecto->id,
                    'mensaje' => 'ACTUALIZADO CORRECTAMENTE'
                ];
                echo json_encode(['respuesta' => $respuesta]);
            }
        }
    }

    public static function eliminar() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            $proyecto = Proyecto::where('url', $_POST['proyectoId']);
            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'HUBO UN ERROR AL ELIMINAR LA TAREA'
                ];
                echo json_encode($respuesta);
                return;
            }
            $tarea = new Tarea($_POST);
            $resultado = $tarea->eliminar();
            $resultado = [
                'resultado' => $resultado,
                'mensaje' => 'TAREA ELIMINADA CON EXITO',
                'tipo' => 'exito'
            ];
            echo json_encode($resultado);
        }
    }
}