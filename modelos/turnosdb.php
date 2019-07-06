<?php
namespace UNLu\PAW\Modelos;
use UNLu\PAW\Modelos\dbconnect;
use UNLu\PAW\Modelos\equipos;
use PDO;

class turnosdb{
  private $db;
  private $dbEquipo;
  private $directorioImagenes;
    public function __construct(){
      $this->db = new dbconnect();
      $this->dbEquipo = new equipos();
    }

public function horariosDisponibles($cancha_turno,$fecha_turno){
  $sql = "SELECT t.horario_turno, c.horario_apertura, c.horario_cierre, c.duracion_turno FROM turno t
	          INNER JOIN cancha c on t.id_cancha = c.id
	            	WHERE fecha = '$fecha_turno' AND id_cancha = '$cancha_turno'";
  $result = $this->db->conn->query($sql);
  if(!$result===FALSE){
      $result = $result->fetchAll();
  }
  return $result;
}

public function data_cancha($id_cancha){
  $sql = "SELECT * FROM cancha WHERE id = '$id_cancha'";
  $result = $this->db->conn->query($sql);
  if(!$result===FALSE){
    $result = $result->fetchAll();
  }
  return $result;
}

public function select_canchas(){
  $sql = "SELECT id,nombre,direccion FROM cancha";
  $result = $this->db->conn->query($sql);
  if(!$result===FALSE){
    $result = $result->fetchAll();
  }
  return $result;
}

public function buscarTurnoHD($fecha,$horario){//Buscar por Hora y Dia
  $sql = "SELECT id FROM turno WHERE fecha='$fecha' AND horario_turno=$horario";
  $result = $this->db->conn->query($sql);
  if(!$result===FALSE){
    $result = $result->fetch();
  }
  return $result;
}

public function newTurno($tipoTurno,$fecha,$horario,$cancha,$equipo_rival,$id){
  $sql = "INSERT INTO turno(id,fecha,horario_turno,id_solicitante,id_equipo_rival,id_cancha,tipo_turno,origen_turno) VALUES (?,?,?,?,?,?,?,?)";

  switch ($tipoTurno) {
    case 0://Turno NORMAL
      $id_solicitante = $id;//Guardo ID USER
      $id_equipo_rival = NULL;
      break;
    case 1://Mi Equipo vs Otro Equipo
      $id_solicitante = $id;
      //$nombreEquipoRival = $this->dbEquipo->getEquipoNombre($equipo_rival);
      $idCapitanRival = $this->dbEquipo->getCapitan($equipo_rival);
      $id_equipo_rival = $idCapitanRival['id_capitan'];
      break;
    case 2://Mi Equipo vs Invitado
      $id_solicitante = $id;
      $id_equipo_rival = NULL;
      break;
    case 3://Mi Equipo Lista de Espera
      $id_solicitante = $id;
      $id_equipo_rival = NULL;
      break;
  }
  $resultado = $this->db->conn->prepare($sql)->execute([NULL,$fecha,$horario,$id_solicitante,$id_equipo_rival,1,$tipoTurno,'web']);
  $info = $this->db->conn->errorCode();
}

public function buscarMisTurnos($id){//Id user
    $sql = "SELECT t.id, t.tipo_turno, t.fecha,t.id_equipo_rival, t.horario_turno,c.nombre,c.direccion FROM turno t
	            INNER JOIN cancha c on t.id_cancha=c.id
                WHERE id_solicitante='$id'";
    $result = $this->db->conn->query($sql);
    $arrayResultado = [];
    if(!$result===FALSE){
      $result = $result->fetchAll();
      foreach($result as $value){

        switch ($value['tipo_turno']) {
          case 0://Turno NORMAL
            $simple[] = [
                      'id' => $value['id'],
                      'fecha' => $value['fecha'],
                      'hora' => $value['horario_turno'],
                      'direccion' => $value['direccion'],
                      'cancha' => $value['nombre'],
              ];
            break;
          case 1://Mi Equipo vs Otro Equipo
            $idEquipoRival = $this->dbEquipo->getEquipo($value['id_equipo_rival']);
            $jugadores = $this->dbEquipo->getJugadoresEquipo($idEquipoRival['id']);
            $capitan = $this->dbEquipo->datosJugador($value['id_equipo_rival']);
            $promedio_edad = 0;
              foreach ($jugadores as $valueJug) {
                  $promedio_edad += $valueJug['edad'];
              }
              $nro = count($jugadores);
              $promedio_edad += $capitan[0]['edad'];
              $promedio_edad = $promedio_edad / (count($jugadores) + 1);
            $tvt[] = [
                          'id' => $value['id'],
                          'equipo' =>  $idEquipoRival['nombre'],
                          'img_equipo' => $idEquipoRival['logo'],
                          'capitan' => $capitan,
                          'jugadores' => $jugadores,
                          'edad' => round($promedio_edad,2),
                          'fecha' => $value['fecha'],
                          'hora' => $value['horario_turno'],
                          'direccion' => $value['direccion'],
                          'cancha' => $value['nombre'],
            ];
            break;
          case 2://Mi Equipo vs Invitado

            break;
          case 3://Mi Equipo Lista de Espera
          $lista[] = [
                    'id' => $value['id'],
                    'fecha' => $value['fecha'],
                    'hora' => $value['horario_turno'],
                    'direccion' => $value['direccion'],
                    'cancha' => $value['nombre'],
            ];
            break;
      }
    }
    if(isset($simple)){
      $arrayResultado = $arrayResultado + array('simple'=>$simple);
    }
    if(isset($tvt)){
      $arrayResultado = $arrayResultado + array('tvt'=>$tvt);
    }
    if(isset($tvi)){
      $arrayResultado = $arrayResultado + array('tvi'=>$tvi);
    }
    if(isset($lista)){
      $arrayResultado = $arrayResultado + array('tSolicitudesEnviadas'=>$lista);
    }
  }
  return $arrayResultado;
  }

  public function misTurnosJugador($id){
    $misEquiposJugador = $this->dbEquipo->misEquiposJugador($id);
      if($misEquiposJugador){
          foreach($misEquiposJugador as $value){
            $turnosEquipo = $this->buscarMisTurnos($value['id_capitan']);
              if($turnosEquipo){
                $partidos[] = [
                    'miequipo' => $value,
                    'rival' => $turnosEquipo,
                ];
              }
          }
      }
      if(empty($partidos)){
        $partidos = FALSE;
      }
      return $partidos;
  }
}
