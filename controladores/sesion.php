<?php
namespace UNLu\PAW\Controladores;

class sesion{
private $timeout = 20;
    public function __construct(){
      session_start();
    }

    public function refreshTime(){
      $this->is_login();
      $_SESSION['timeout'] = time();
    }

    public function inicializarSesion($id){
        $_SESSION['timeout'] = time();
        $_SESSION['is_login'] = TRUE;
        $_SESSION['id'] = $id;
        session_regenerate_id();
    }

    public function is_login(){
      if(isset($_SESSION['is_login'])){
        if(($_SESSION['is_login']!==TRUE)&&($this->sesionActiva)){
          $this->redireccion();
        }
      }else{
          $this->redireccion();
        }
        return TRUE;
      }

    public function redireccion(){
      $request = explode('/',$_SERVER['REQUEST_URI']);//La separo por "/"
      header('Location:/'. $request[1] . '/login');
      exit();
    }

    public function log_out(){
      session_destroy();
      $this->redireccion();
    }

    public function sesionActiva(){//Antes de Realizar alguna accion se solicita este metodo
      if(isset($_SESSION['timeout'])){
        $sessionTimeOut = time() - $_SESSION['timeout'];
        if($sessionTimeOut>$this->timeout){
          session_destroy();
          $this->redireccion();
        }
      }
    }
}
?>
