<?php
namespace UNLu\PAW\Controladores;
use UNLu\PAW\Libs\VIstaHTML;
use UNLu\PAW\Modelos\turnosdb;
use UNLu\PAW\Modelos\equipos;

class Buscarpartido extends \UNLu\PAW\Libs\Controlador{

  private static $initialized = false;
  private static $dbTurnos;
  private static $dbEquipos;

  private static function initialize(){
    if(self::$initialized)
      return true;
      self::$dbTurnos = new turnosdb();
      self::$dbEquipos = new equipos();
    self::$initialized=true;
  }

public function buscar(){
  sesion::startSession();
  if(sesion::is_login()){
      self::initialize();
      $this->pasarVariableAVista("listaPartidos",self::$dbTurnos->buscarPartido());
      sesion::refreshTime();
  }else {
    $this->redireccionarA($_SERVER['REQUEST_URI'],'/login');
  }
}

public function desafiar(){
  sesion::startSession();
    if(sesion::is_login()){
      self::initialize();
      $id_turno = $_POST['id_turno'];
        if(!self::$dbTurnos->getTurno($id_turno,sesion::getId())){
            if(!self::$dbTurnos->buscarDesafio($id_turno,sesion::getId())){
              //registrar desafio!
			  $datos = self::$dbTurnos->getMailDataTurno($id_turno);
              if(self::$dbTurnos->registrarDesafio($id_turno,sesion::getId())){
				$rival = self::$dbEquipos->getEquipo(sesion::getId());
				mail::sendRequestDesafio($datos['mail'],$datos['fecha'].' - '.$datos['horario_turno'],$datos['cancha'], $rival['nombre']);
                echo "El desafio se registro correctamente. Se te notificará por mail cuando el capitan del equipo responda la solicitud de desafio!";
              }else{
                echo "El desafio no se registro. Antes de desafiar otros equipos debes crear un equipo";
              }
            }else{
               echo "Ya desafiaste ese turno. Porfavor, ten paciencia te avisaremos por mail cuando el capitan responda tu solicitud";
            }
        }else {
          echo "No puedes desafiar tu propio turno";
        }
    }
    exit();
}

}
