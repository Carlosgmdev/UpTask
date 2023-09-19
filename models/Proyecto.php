<?php

namespace Model;

class Proyecto extends ActiveRecord {
    
    protected static $tabla = 'proyectos';
    protected static $columnasDB = ['id','proyecto','url','propietarioId'];

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->proyecto = $args['proyecto'] ?? '';
        $this->url = $args['url'] ?? '';
        $this->propietarioId = $args['propietarioId'] ?? '';
    }

    public function validarProyecto() {
        if(!$this->proyecto) {
            self::$alertas['error'][] = 'DEBES INTRODUCIR UN NOMBRE PARA EL PROYECTO.';
        }
        return self::$alertas;
    }

    public function generarUrl() {
        $this->url = md5(uniqid());
    }

}