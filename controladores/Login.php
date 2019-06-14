<?php
namespace UNLu\PAW\Controladores;
use UNLu\PAW\Libs\VIstaHTML;
use UNLu\PAW\Modelos\users;

class Login extends \UNLu\PAW\Libs\Controlador{

  public function iniciarSesion($mensaje=null){
    session_start();
    if(isset($_SESSION['mensaje'])){
      $mensaje = $_SESSION['mensaje'];
      unset($_SESSION['mensaje']);
    }
    $this->pasarVariableAVista('mensaje',$mensaje);
  }

  public function registro($mensaje=null){
    session_start();
    if(isset($_SESSION['mensaje'])){
      $mensaje = $_SESSION['mensaje'];
      unset($_SESSION['mensaje']);
    }
    $this->pasarVariableAVista('mensaje',$mensaje);
  }

  public function registroUser($mensaje=null){
    //carga la vista default
    session_start();
    $action = '';
    $db = new users();
    ///OBTENGO LOS DATOS///
    $mail = filter_input(INPUT_POST,'email',FILTER_SANITIZE_EMAIL);
    $pass = filter_input(INPUT_POST,'pass',FILTER_SANITIZE_STRING);
    $nombre = filter_input(INPUT_POST,'nombre',FILTER_SANITIZE_STRING);
    $username = filter_input(INPUT_POST,'username',FILTER_SANITIZE_STRING);
    $edad = filter_input(INPUT_POST,'edad',FILTER_SANITIZE_NUMBER_INT);
    ///FIN DATOS////
      if(($db->buscarUser($mail)===FALSE) && ($db->buscarUser($username)===FALSE)){
          $db->newUser($pass,$mail,$nombre,$username,$edad);
          $_SESSION['mensaje'] = 'Registrado';
          $this->redireccionarA($_SERVER['REQUEST_URI'],'/login');
      }else{
          $_SESSION['mensaje'] = 'Ya Existe';
          $this->redireccionarA($_SERVER['REQUEST_URI'],'/login/registro');
      }
  }

  public function loginUser(){
    //carga la vista default
    session_start();
    $action = '';
    $db = new users();
    $ident = filter_input(INPUT_POST,'email',FILTER_SANITIZE_EMAIL);
    $pass = filter_input(INPUT_POST,'pass',FILTER_SANITIZE_STRING);
    if($db->buscarUser($ident)===TRUE){
      if($db->loginUser($ident,$pass)===TRUE){
        $_SESSION['mensaje'] = 'Logueado';
        $this->redireccionarA($_SERVER['REQUEST_URI'],'/perfil');
      }else{
        $_SESSION['mensaje'] = 'Contraseña Incorrecta';
        $this->redireccionarA($_SERVER['REQUEST_URI'],'/login');
      }
    }else{
        $_SESSION['mensaje'] = 'Error NO log';
        $this->redireccionarA($_SERVER['REQUEST_URI'],'/login');
    }
  }


}
