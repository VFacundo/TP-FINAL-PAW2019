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

  public function nuevoTurno(){
    sesion::startSession();
    if(sesion::is_login()){
      $dbTurnos = new turnosdb();
      $tipoTurno = $_POST['tipo_turno'];
      $fecha = $_POST['fecha_turno'];
      $horario = $_POST['horario_turno'];
      $cancha = $_POST['cancha_turno'];
      $equipo_rival = isset($_POST['equipo_rival'])?$_POST['equipo_rival']:FALSE;
        if(!$dbTurnos->buscarTurnoHD($fecha,$horario)){
          echo("Fecha/Hora No disponibles!");
        }else {
          $dbTurnos->newTurno($tipoTurno,$fecha,$horario,$cancha,$equipo_rival,sesion::getId());
        }
      sesion::refreshTime();
      exit();
    }
  }


}
