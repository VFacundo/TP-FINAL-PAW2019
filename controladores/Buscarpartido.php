<?php
namespace UNLu\PAW\Controladores;
use UNLu\PAW\Libs\VIstaHTML;
use UNLu\PAW\Modelos\turnosdb;
//use UNLu\PAW\Modelos\equipos;

class Buscarpartido extends \UNLu\PAW\Libs\Controlador{

  private static $initialized = false;
  private static $dbTurnos;
  //private static $dbu;

  private static function initialize(){
    if(self::$initialized)
      return true;
      self::$dbTurnos = new turnosdb();
      //self::$dbu = new users();
    self::$initialized=true;
  }

public function buscar(){
  sesion::startSession();
  if(sesion::is_login()){
      self::initialize();
      $this->pasarVariableAVista("listaPartidos",self::$dbTurnos->buscarPartido());
      sesion::refreshTime();
  }
}

public function desafiar(){
  sesion::startSession();
    if(sesion::is_login()){
      self::initialize();
      $id_turno = $_POST['id_turno'];
        if(!self::$dbTurnos->getTurno($id_turno,sesion::getId())){
            //registrar desafio!
            self::$dbTurnos->registrarDesafio($id_turno,sesion::getId());
            echo "Desafio Registrado!";
        }else {
          echo "No Puedes Desafiar TU Propio Turno!";
        }
    }
    exit();
}

}