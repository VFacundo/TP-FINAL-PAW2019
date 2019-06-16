<?php
namespace UNLu\PAW\Controladores;

class sesion{

  private function __construct(){}
  private static $initialized = false;
  private static $timeout = 20;

    private static function initialize(){
      if(self::$initialized)
        return;
      session_name("USRSSN");
      ini_set("session.cookie_lifetime","7200");
      ini_set("session.gc_maxlifetime","7200");
      self::$initialized=true;
    }

    public static function startSession(){
        self::initialize();
        if(!isset($_SESSION)){
          session_start();
        }
    }

    public static function refreshTime(){
      self::is_login();
      $_SESSION['timeout'] = time();
    }

    public static function inicializarSesion($id){
        $_SESSION['timeout'] = time();
        $_SESSION['is_login'] = TRUE;
        $_SESSION['id'] = $id;
        session_regenerate_id();
    }

    public static function is_login(){
      if(isset($_SESSION['is_login'])){
        if(($_SESSION['is_login']!==TRUE)||(self::sesionActiva()!==TRUE)){
          self::redireccion();
        }
      }else{
          self::redireccion();
        }
        return TRUE;
      }

    public static function redireccion(){
      $request = explode('/',$_SERVER['REQUEST_URI']);//La separo por "/"
      header('Location:/'. $request[1] . '/login');
      exit();
    }

    public static function log_out(){
      session_destroy();
      self::redireccion();
    }

    public static function sesionActiva(){//Antes de Realizar alguna accion se solicita este metodo
      if(isset($_SESSION['timeout'])){
        $sessionTimeOut = time() - $_SESSION['timeout'];
        if($sessionTimeOut>self::$timeout){
          session_destroy();
          self::redireccion();
        }
      }
      return TRUE;
    }
}
?>
