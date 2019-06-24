<?php
namespace UNLu\PAW\Controladores;
use UNLu\PAW\Libs\VIstaHTML;
use UNLu\PAW\Modelos\users;

class Perfil extends \UNLu\PAW\Libs\Controlador{

public function perfil(){
  $db = new users();
  sesion::startSession();
  if(sesion::is_login()){
    $datosUser = $db->datosUserJug($_SESSION['id']);
    $this->pasarVariableAVista('datos',$datosUser);
    $this->pasarVariableAVista('img','img/' . $datosUser['img_src']);
    sesion::refreshTime();
  }else{
    $this->redireccionarA($_SERVER['REQUEST_URI'],'/login');
  }
}

public function editarPerfil(){
  sesion::startSession();
  sesion::refreshTime();
  if(sesion::is_login()){
    $nombre = filter_input(INPUT_POST,'nombre',FILTER_SANITIZE_STRING);
    $username = filter_input(INPUT_POST,'username',FILTER_SANITIZE_STRING);
    $edad = filter_input(INPUT_POST,'edad',FILTER_SANITIZE_NUMBER_INT);
    $tel = filter_input(INPUT_POST,'tel',FILTER_SANITIZE_STRING);
    $db = new users();
    $datosJug = $db->datosUserJug(sesion::getId());
      if($datosJug['user'] == $username){//Si es el mismo user lo edito
        $db->editUser($nombre,null,$edad,$tel,sesion::getId());
      }else {//Si lo cambio me fijo si esta
        if(empty($db->buscarUser($username))){
            $db->editUser($nombre,$username,$edad,$tel,sesion::getId());
        }
      }
  }
  $this->redireccionar('/perfil');
}

public function subirImagen(){
  sesion::startSession();
  sesion::refreshTime();
  if(sesion::is_login()){
    $img = $_FILES;
    if(!empty($img)){
      $db = new users();
      $db->imgUser($img,sesion::getId());
      $this->redireccionarA($_SERVER['REQUEST_URI'],'/response/responsepage/OK');
    }
  }
}

public function setImg(){
  sesion::startSession();
  $db = new users();
  $ruta = $_POST['img_src'];
  $db->setImg(sesion::getId(),$ruta);
  echo('ok');
  exit();
}

public function getImg(){//devuelve img perfil
  sesion::startSession();
  $db = new users();
  $result = $db->getImg(sesion::getId());
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
