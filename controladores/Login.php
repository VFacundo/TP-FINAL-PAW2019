<?php
namespace UNLu\PAW\Controladores;
use UNLu\PAW\Libs\VIstaHTML;
use UNLu\PAW\Modelos\users;

class Login extends \UNLu\PAW\Libs\Controlador{

  public function iniciarSesion($mensaje=null){
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
    $nombre = $_POST['fullName'];
    //google::verify_token($id_token);
    $db = new users();
    $tokenPayload = $db->newGoogleUser($id_token);
    if($tokenPayload){
      if(empty($db->buscarUser($mail))){
        $db->newUser($tokenPayload,$mail,$nombre,'',0,'');
        $db->googleToken($mail,$tokenPayload);
		$db->setMailVerificado($mail);
      }
        sesion::startSession();
        sesion::inicializarSesion($db->buscarUser($mail));
		echo '200';
      }else{
		  echo 'error';
      }
	  exit();
  }

  public function flogin(){
    $id_token = $_POST['id_token'];
    $mail = $_POST['umail'];
    $nombre = $_POST['fullName'];
    $db = new users();
	$db->newUser($id_token,$mail,$nombre,'',0,'');
	$db->setMailVerificado($mail);
	sesion::startSession();
	sesion::inicializarSesion($db->buscarUser($mail));
	echo '200';
	exit();
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
    if(data::verify_mail($mail)){
      if(data::verify_pass($pass)){
        if(data::verify_name($nombre)){
          if(data::verify_username($username)){
            if(data::verify_age($edad)){
              if(data::verify_phone($tel)){
                //Verifico todos los Datos//
                if(empty($db->buscarUser($mail)) && (empty($db->buscarUser($username)))){
                    if(isset($_SESSION['mensaje'])){
                      unset($_SESSION['mensaje']);
                    }
                    $db->newUser($pass,$mail,$nombre,$username,$edad,$tel);
                    mail::sendConfirmMail($mail);
                    $this->redireccionarA($_SERVER['REQUEST_URI'],"/verify/verifymail/" . $mail);
                }else{
                    $_SESSION['mensaje'] = 'Ya Existe';
                    $this->redireccionarA($_SERVER['REQUEST_URI'],'registro');
                }
                //Verifico todos los Datos//
              }else{
                $_SESSION['mensaje'] = 'Telefono invalido.';
                $this->redireccionarA($_SERVER['REQUEST_URI'],'registro');
              }
            }else{
              $_SESSION['mensaje'] = 'Edad invalida.';
              $this->redireccionarA($_SERVER['REQUEST_URI'],'registro');
            }
          }else{
            $_SESSION['mensaje'] = 'Usario invalido. No puede tener caracteres especiales.';
            $this->redireccionarA($_SERVER['REQUEST_URI'],'registro');
          }
        }else{
          $_SESSION['mensaje'] = 'Nombre invalido. No puede tener numeros o caracteres.';
          $this->redireccionarA($_SERVER['REQUEST_URI'],'registro');
        }
      }else{
        $_SESSION['mensaje'] = 'Contraseña invalida.';
        $this->redireccionarA($_SERVER['REQUEST_URI'],'registro');
      }
    }else{
      $_SESSION['mensaje'] = 'Correo electronico invalido. '.$mail;
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
		  if(sesion::isAdmin()){
  			$this->redireccionarA($_SERVER['REQUEST_URI'],'/admin');
  			exit();
		  }else{
			  $this->redireccionarA($_SERVER['REQUEST_URI'],'/perfil');
		  }
        }else{
          $_SESSION['mensaje'] = 'Usuario o Contraseña Incorrecto';
          $this->redireccionarA($_SERVER['REQUEST_URI'],'/login');
        }
      }else{
          $_SESSION['mensaje'] = 'Usuario o Contraseña Incorrecto';
          $this->redireccionarA($_SERVER['REQUEST_URI'],'/login');
      }
    }else {
      $_SESSION['mensaje'] = 'Usuario o Contraseña Incorrecto';
      $this->redireccionarA($_SERVER['REQUEST_URI'],'/login');
    }
  }

  public function logout(){
    sesion::log_out();
  }
}
