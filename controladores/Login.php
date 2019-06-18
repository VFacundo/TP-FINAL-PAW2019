<?php
namespace UNLu\PAW\Controladores;
use UNLu\PAW\Libs\VIstaHTML;
use UNLu\PAW\Modelos\users;
require_once 'vendor/autoload.php';

class Login extends \UNLu\PAW\Libs\Controlador{

  public function iniciarSesion($mensaje=null){
    //session_start();
    sesion::startSession();
    if(sesion::is_login()){
      $this->redireccionar('/perfil');
    }
    if(isset($_SESSION['mensaje'])){
      $mensaje = $_SESSION['mensaje'];
      unset($_SESSION['mensaje']);
    }
    $this->pasarVariableAVista('mensaje',$mensaje);
  }

  public function glogin(){
    $id_token = $_POST['id_token'];
    $mail = $_POST['umail'];

    $db = new users();
      if(empty($db->buscarUser($mail))){
        $db->newGoogleUser($id_token,$mail);
    }
    sesion::startSession();
    sesion::inicializarSesion($db->buscarUser($mail));
  }

  public function registro($mensaje=null){
    sesion::startSession();
    if(isset($_SESSION['mensaje'])){
      $mensaje = $_SESSION['mensaje'];
      unset($_SESSION['mensaje']);
    }
    $this->pasarVariableAVista('mensaje',$mensaje);
  }

  public function registroUser($mensaje=null){
    //carga la vista default
    sesion::startSession();
    $action = '';
    $db = new users();
    ///OBTENGO LOS DATOS///
    $mail = filter_input(INPUT_POST,'email',FILTER_SANITIZE_EMAIL);
    $pass = filter_input(INPUT_POST,'pass',FILTER_SANITIZE_STRING);
    $nombre = filter_input(INPUT_POST,'nombre',FILTER_SANITIZE_STRING);
    $username = filter_input(INPUT_POST,'username',FILTER_SANITIZE_STRING);
    $edad = filter_input(INPUT_POST,'edad',FILTER_SANITIZE_NUMBER_INT);
    $tel = filter_input(INPUT_POST,'tel',FILTER_SANITIZE_STRING);
    ///FIN DATOS////
      if(empty($db->buscarUser($mail)) && (empty($db->buscarUser($username)))){
          $db->newUser($pass,$mail,$nombre,$username,$edad,$tel);
          $this->redireccionar('/login');
      }else{
          $_SESSION['mensaje'] = 'Ya Existe';
          $this->redireccionarA($_SERVER['REQUEST_URI'],'/registro');
      }
  }

  public function loginUser(){
    //carga la vista default
    //session_start();
    sesion::startSession();
    $action = '';
    $db = new users();
    $ident = filter_input(INPUT_POST,'email',FILTER_SANITIZE_EMAIL);
    $pass = filter_input(INPUT_POST,'pass',FILTER_SANITIZE_STRING);
    if(!empty($db->buscarUser($ident))){
      if($db->loginUser($ident,$pass)===TRUE){
        sesion::inicializarSesion($db->buscarUser($ident));
        $this->redireccionar('/perfil');
      }else{
        $_SESSION['mensaje'] = 'Contraseña Incorrecta';
        $this->redireccionarA($_SERVER['REQUEST_URI'],'/login');
      }
    }else{
        $_SESSION['mensaje'] = 'Error NO log';
        $this->redireccionarA($_SERVER['REQUEST_URI'],'/login');
    }
  }

  public function logout(){
    sesion::log_out();
  }
}
