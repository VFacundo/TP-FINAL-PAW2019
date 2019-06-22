<?php
namespace UNLu\PAW\Controladores;
use UNLu\PAW\Libs\VIstaHTML;
use UNLu\PAW\Modelos\users;

class Login extends \UNLu\PAW\Libs\Controlador{

  public function iniciarSesion($mensaje=null){
    //session_start();
    sesion::startSession();
    if(sesion::is_login()){
      $this->redireccionarA($_SERVER['REQUEST_URI'],'/perfil');
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
    //$archivoActual = $_SERVER['PHP_SELF'];
      //header("refresh:1;url=" + $archivoActual);
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
    if(!data::verify_mail($mail)){
      $_SESSION['mensaje'] = 'Correo electronico invalido. '.$mail;
      $this->redireccionarA($_SERVER['REQUEST_URI'],'registro');
      if(!data::verify_pass($pass)){
        $_SESSION['mensaje'] = 'Contraseña invalida.';
        $this->redireccionarA($_SERVER['REQUEST_URI'],'registro');
        if(!data::verify_name($nombre)){
          $_SESSION['mensaje'] = 'Nombre invalido. No puede tener numeros o caracteres.';
          $this->redireccionarA($_SERVER['REQUEST_URI'],'registro');
          if(!data::verify_username($username)){
            $_SESSION['mensaje'] = 'Usario invalido. No puede tener caracteres especiales.';
            $this->redireccionarA($_SERVER['REQUEST_URI'],'registro');
            if(!data::verify_age($edad)){
              $_SESSION['mensaje'] = 'Edad invalida.';
              $this->redireccionarA($_SERVER['REQUEST_URI'],'registro');
              if(!data::verify_phone($tel)){
                $_SESSION['mensaje'] = 'Telefono invalido.';
                $this->redireccionarA($_SERVER['REQUEST_URI'],'registro');
              }
            }
          }
        }
      }
    }
    //Verifico todos los Datos//
    if(empty($db->buscarUser($mail)) && (empty($db->buscarUser($username)))){
        $db->newUser($pass,$mail,$nombre,$username,$edad,$tel);
        $this->redireccionarA($_SERVER['REQUEST_URI'],'/login');
    }else{
        $_SESSION['mensaje'] = 'Ya Existe';
        $this->redireccionarA($_SERVER['REQUEST_URI'],'registro');
    }
  }

  public function loginUser(){
    //carga la vista default
    sesion::startSession();
    $action = '';
    $db = new users();
    $ident = filter_input(INPUT_POST,'email',FILTER_SANITIZE_EMAIL);
    $pass = filter_input(INPUT_POST,'pass',FILTER_SANITIZE_STRING);
    if((data::verify_mail($ident)||data::verify_username($ident))&&data::verify_pass($pass)){
    //Verifico los Datos..//
      if(!empty($db->buscarUser($ident))){
        if($db->loginUser($ident,$pass)===TRUE){
          sesion::inicializarSesion($db->buscarUser($ident));
          $this->redireccionarA($_SERVER['REQUEST_URI'],'/perfil');
        }else{
          $_SESSION['mensaje'] = 'Contraseña Incorrecta';
          $this->redireccionarA($_SERVER['REQUEST_URI'],'/login');
        }
      }else{
          $_SESSION['mensaje'] = 'Datos Ingresados NO Validos! no user';
          $this->redireccionarA($_SERVER['REQUEST_URI'],'/login');
      }
    }else {
      $_SESSION['mensaje'] = 'Datos Ingresados NO Validos!' . 'mail:'. data::verify_mail($ident) .'usr:'.  data::verify_username($ident) . 'pwd:'. data::verify_pass($pass);
      $this->redireccionarA($_SERVER['REQUEST_URI'],'/login');
    }
  }

  public function logout(){
    sesion::log_out();
  }
}
