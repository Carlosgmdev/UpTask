<?php 

namespace Model;

class Usuario extends ActiveRecord {

    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id','nombre','email','password','token','confirmado'];

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
    }

    public function validarNuevaCuenta() {

        if(!$this->nombre) {
            self::$alertas['error'][] = 'EL NOMBRE ES OBLIGATORIO';
        }

        if(!$this->email) {
            self::$alertas['error'][] = 'EL EMAIL ES OBLIGATORIO';
        }

        if(!$this->password) {
            self::$alertas['error'][] = 'EL PASSWORD ES OBLIGATORIO';
        }

        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'EL PASSWORD DEBE CONTENER AL MENOS 6 CARACTERES';
        }

        if($this->password !== $this->password2) {
            self::$alertas['error'][] = 'EL PASSWORD NO COINCIDE';
        }

        return self::$alertas;
    }

    public function hashPassword() {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function generarToken() {
        $this->token = md5(uniqid());
    }

    public function validarEmail() {
        if(!$this->email) {
            self::$alertas['error'][] = 'EL EMAIL ES OBLIGATORIO';
        }

        if(!filter_var($this->email,FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'INTRODUCE UN EMAIL VALIDO';
        }

        return self::$alertas;
    }

    public function validarPassword() {
        if(!$this->password) {
            self::$alertas['error'][] = 'EL PASSWORD ES OBLIGATORIO';
        }

        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'EL PASSWORD DEBE CONTENER AL MENOS 6 CARACTERES';
        }

        return self::$alertas;
    }

    public function validarLogin() {

        if(!$this->email) {
            self::$alertas['error'][] = 'EL EMAIL ES OBLIGATORIO';
        }

        if(!filter_var($this->email,FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'INTRODUCE UN EMAIL VALIDO';
        }

        if(!$this->password) {
            self::$alertas['error'][] = 'EL PASSWORD ES OBLIGATORIO';
        }

        return self::$alertas;
    }

    public function validar_perfil() {
        
        if(!$this->nombre) {
            self::$alertas['error'][] = 'EL NOMBRE ES OBLIGATORIO';
        }

        if(!$this->email) {
            self::$alertas['error'][] = 'EL EMAIL ES OBLIGATORIO';
        }

        if(!filter_var($this->email,FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'INTRODUCE UN EMAIL VALIDO';
        }

        return self::$alertas;
    }
}