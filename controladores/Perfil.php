<?php
namespace UNLu\PAW\Controladores;
use UNLu\PAW\Libs\VIstaHTML;
use UNLu\PAW\Modelos\users;

class Perfil extends \UNLu\PAW\Libs\Controlador{

  private static $initialized = false;
  private static $db;

  private static function initialize(){
    if(self::$initialized)
      return true;
      self::$db = new users();
    self::$initialized=true;
  }

public function perfil(){
  self::initialize();
  sesion::startSession();
  if(sesion::is_login()){
    $datosUser = self::$db->datosUserJug($_SESSION['id']);
    $this->pasarVariableAVista('datos',$datosUser);
    $this->pasarVariableAVista('img','img/' . $datosUser['img_src']);
    sesion::refreshTime();
  }else{
    $this->redireccionarA($_SERVER['REQUEST_URI'],'/login');
  }
}

public function editarPerfil(){
  self::initialize();
  sesion::startSession();
  sesion::refreshTime();
  if(sesion::is_login()){
    $nombre = filter_input(INPUT_POST,'nombre',FILTER_SANITIZE_STRING);
    $username = filter_input(INPUT_POST,'username',FILTER_SANITIZE_STRING);
    $edad = filter_input(INPUT_POST,'edad',FILTER_SANITIZE_NUMBER_INT);
    $tel = filter_input(INPUT_POST,'tel',FILTER_SANITIZE_STRING);
    $datosJug = self::$db->datosUserJug(sesion::getId());
      if($datosJug['user'] == $username){//Si es el mismo user lo edito
        self::$db->editUser($nombre,null,$edad,$tel,sesion::getId());
      }else {//Si lo cambio me fijo si esta
        if(empty(self::$db->buscarUser($username))){
            self::$db->editUser($nombre,$username,$edad,$tel,sesion::getId());
        }
      }
  }
  $this->redireccionar('/perfil');
}

public function subirImagen(){
  sesion::startSession();
  sesion::refreshTime();
  self::initialize();
  if(sesion::is_login()){
    $img = $_FILES;
    if(!empty($img)){
      self::$db->imgUser($img,sesion::getId());
      echo "OK";
    }else{
      echo "";
    }
  }
  exit();
}

public function setImg(){
  sesion::startSession();
  self::initialize();
  $ruta = $_POST['img_src'];
  self::$db->setImg(sesion::getId(),$ruta);
  echo('OK');
  exit();
}

public function getImg(){//devuelve img perfil
  sesion::startSession();
  self::initialize();
  $result = self::$db->getImg(sesion::getId());
   if(!$result===FALSE){
     echo($result['img_src']);
     exit();
   }else {
      echo('error');
      exit();
   }
}

}
?>
