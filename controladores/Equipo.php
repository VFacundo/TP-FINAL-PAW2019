<?php
namespace UNLu\PAW\Controladores;
use UNLu\PAW\Libs\VIstaHTML;
use UNLu\PAW\Modelos\users;
use UNLu\PAW\Modelos\equipos;

class Equipo extends \UNLu\PAW\Libs\Controlador{

  public function miequipo(){
    $db = new equipos();
    sesion::startSession();
    if(sesion::is_login()){
      $this->pasarVariableAVista("equipo",$db->getEquipo(sesion::getId()));
    }else{
      $this->redireccionarA($_SERVER['REQUEST_URI'],'/login');
    }
  }
}
