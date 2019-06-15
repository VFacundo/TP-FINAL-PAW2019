<?php
namespace UNLu\PAW\Controladores;
use UNLu\PAW\Libs\VIstaHTML;
use UNLu\PAW\Modelos\users;

class Perfil extends \UNLu\PAW\Libs\Controlador{

public function perfil(){
  $sesion = new sesion();
  $db = new users();
  if($sesion->is_login()){
    //var_dump($sesion->sesionActiva());
    //var_dump($_SESSION);
    //var_dump($db->datosUserJug($_SESSION['id']));
    $this->pasarVariableAVista('datos',$db->datosUserJug($_SESSION['id']));
    $sesion->refreshTime();
    //var_dump($_SESSION);
  }
}



}
?>
