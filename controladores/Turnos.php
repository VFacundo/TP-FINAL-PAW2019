<?php
namespace UNLu\PAW\Controladores;
use UNLu\PAW\Libs\VIstaHTML;
use UNLu\PAW\Modelos\users;
use UNLu\PAW\Modelos\turnosdb;

class Turnos extends \UNLu\PAW\Libs\Controlador{

  public function misturnos(){
    sesion::startSession();
    if(sesion::is_login()){
      $dbTurnos = new turnosdb();
      $this->pasarVariableAVista("misturnos",$dbTurnos->buscarMisTurnos(sesion::getId()));
    }else {
      $this->redireccionarA($_SERVER['REQUEST_URI'],'/login');
    }

  }
}
