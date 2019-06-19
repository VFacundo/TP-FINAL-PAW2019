<?php
namespace UNLu\PAW\Controladores;

class sesion{

  private function __construct(){}
  private static $initialized = false;
  private static $timeout = 200;
  private static $sName = "USRSSN";

    private static function initialize(){
      if(self::$initialized)
        return;
      session_name(self::$sName);
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

    public static function getId(){
      if(self::is_login()){
        return $_SESSION['id'];
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
      $login = TRUE;
      if(isset($_SESSION['is_login'])){
        if(($_SESSION['is_login']!==TRUE)||(self::sesionActiva()!==TRUE)){
          $login = FALSE;
        }
      }else{
          $login = FALSE;
        }
        return $login;
      }

    public static function redireccion(){
      $request = explode('/',$_SERVER['REQUEST_URI']);//La separo por "/"
      if(!empty($request[0]))
        header('Location:/'. $request[1] . '/login');
      else {
        header("Location: /login");
      }
      exit();
    }

    public static function log_out(){
      self::startSession();
      session_destroy();
      setcookie (self::$sName, "", 10000);
      self::redireccion();
    }

    public static function sesionActiva(){//Antes de Realizar alguna accion se solicita este metodo
      $sa = TRUE;
      if(isset($_SESSION['timeout'])){
        $sessionTimeOut = time() - $_SESSION['timeout'];
        if($sessionTimeOut>self::$timeout){
          session_destroy();
          $sa = FALSE;
        }
      }
      return $sa;
    }

}
?>
