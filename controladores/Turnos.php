<?php
namespace UNLu\PAW\Controladores;
use UNLu\PAW\Libs\VIstaHTML;
use UNLu\PAW\Modelos\users;
use UNLu\PAW\Modelos\turnosdb;
use UNLu\PAW\Modelos\equipos;

class Turnos extends \UNLu\PAW\Libs\Controlador{


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

  public function misturnos(){
    self::initialize();
    sesion::startSession();
    if(sesion::is_login()){
      $this->pasarVariableAVista("miequipo",self::$dbEquipos->getEquipo(sesion::getId()));
      $misTurnos = self::$dbTurnos->buscarMisTurnos(sesion::getId());
      $this->pasarVariableAVista("turno",$misTurnos);
      $this->pasarVariableAVista("misturnosjugador",self::$dbTurnos->misturnosjugador(sesion::getId()));
      $this->pasarVariableAVista("historial",self::$dbTurnos->historial(sesion::getId()));
    }else {
      $this->redireccionarA($_SERVER['REQUEST_URI'],'/login');
    }

  }

  public function nuevoTurno(){
    self::initialize();
    sesion::startSession();
    if(sesion::is_login()){
      $tipoTurno = $_POST['tipo_turno'];
      $fecha = $_POST['fecha_turno'];
      $horario = $_POST['horario_turno'];
      $cancha = $_POST['cancha_turno'];
      $equipo_rival = isset($_POST['equipo_rival'])?$_POST['equipo_rival']:FALSE;
      $origen_turno = isset($_POST['origen_turno'])?$_POST['origen_turno']:"web";
      $data_turno = self::$dbTurnos->buscarTurnoHD($fecha,$horario);
        if($data_turno!=FALSE){
          if($data_turno['tipo_turno']==3){//Si el horario esta ocupado, pero es un turno buscar lo sobreescribo!
            $data_cancha = self::$dbTurnos->getMailDataTurno($data_turno['id']);
            self::$dbTurnos->borrarTurno($data_turno['id_solicitante'],$data_turno['id']);
            mail::sendRejectTurno($data_cancha['mail'],$data_turno['fecha'] . ' - ' . $data_turno['horario_turno'],$data_cancha['cancha']);
            self::$dbTurnos->newTurno($tipoTurno,$fecha,$horario,$cancha,$equipo_rival,sesion::getId(),$origen_turno);
          }else {
            echo("Fecha/Hora No disponibles!");
          }
        }else {
          self::$dbTurnos->newTurno($tipoTurno,$fecha,$horario,$cancha,$equipo_rival,sesion::getId(),$origen_turno);
        }
      sesion::refreshTime();
      exit();
    }
  }

  public function borrarTurno(){
    self::initialize();
    sesion::startSession();
    if(sesion::is_login()){
      $id_turno = $_POST['id_turno'];
        if(self::$dbTurnos->borrarTurno(sesion::getId(),$id_turno)){
          echo 200;
        }else {
          echo 400;
        }
    }else {
      echo 400;
    }
    exit();
  }

  public function getCanchas(){
    self::initialize();
    $canchas = self::$dbTurnos->select_canchas();
    $mensaje = null;
    foreach ($canchas as $value) {
      $mensaje.= '<option data-dir="' . $value['direccion'] . '" data-hora-cierre="' . $value['horario_cierre'] . '" data-hora-apertura="' . $value['horario_apertura'] . '" data-tel="' . $value['telefono'] . '" data-duracion="' . $value['duracion_turno'] . '" value="' . $value['id'] . '">' . $value['nombre'] . '<label></label>' . '</option>';
    }
      echo $mensaje;
      exit();
  }

  public function horariosDisponibles(){
    self::initialize();
    sesion::startSession();
    sesion::refreshTime();
    /*
    tipo turnos
    cancha_turno
    fecha_turno
    horario_turno
    */
    $cancha_turno = $_POST['cancha_turno'];
    $fecha_turno = $_POST['fecha_turno'];
    $tipo_turno = $_POST['tipo_turno'];
    $result = self::$dbTurnos->horariosDisponibles($cancha_turno,$fecha_turno,$tipo_turno);
    $horarios = null;
    $array_horario = [];
      if(!empty($result)){
          foreach($result as $value){
            $array_horario[] = $value['horario_turno'];
          }
        }else{
          $result = self::$dbTurnos->data_cancha($cancha_turno);
        }
        $horario_apertura = $result[0]['horario_apertura'];
        $horario_cierre = $result[0]['horario_cierre'];
        $duracion_turno = $result[0]['duracion_turno'];
        $formato = 'Y-m-d H:i:s';
        $horario_apertura = date_create_from_format($formato,$fecha_turno . $horario_apertura);
        $horario_cierre = date_create_from_format($formato,$fecha_turno . $horario_cierre);
        $duracion_turno = date_interval_create_from_date_string($duracion_turno .' minutes');

        while($horario_apertura < $horario_cierre) {
            if(!in_array($horario_apertura->format('H:i:s'),$array_horario)){
              $horarios .= '<option value="' . $horario_apertura->format('H:i') . '">' . $horario_apertura->format('H:i') . '</option>' . PHP_EOL;
            }
            $horario_apertura->add($duracion_turno);
        }
    echo($horarios);
    exit();
  }

}
