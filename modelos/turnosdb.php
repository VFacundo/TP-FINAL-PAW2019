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
      $id_solicitante = $this->dbEquipo->getEquipo($id);
      $id_solicitante = $id_solicitante['id'];
      $id_equipo_rival = $this->dbEquipo->getEquipoNombre($equipo_rival);
      break;
    case 2://Mi Equipo vs Invitado
      $id_solicitante = $this->dbEquipo->getEquipo($id);
      $id_solicitante = $id_solicitante['id'];
      $id_equipo_rival = NULL;
      break;
    case 3://Mi Equipo Lista de Espera
      $id_solicitante = $this->dbEquipo->getEquipo($id);
      $id_solicitante = $id_solicitante['id'];
      $id_equipo_rival = NULL;
      break;
  }
  $resultado = $this->db->conn->prepare($sql)->execute([NULL,$fecha,$horario,$id_solicitante,$id_equipo_rival,1,$tipoTurno,'web']);
  $info = $this->db->conn->errorCode();
}

public function buscarMisTurnos($id){//Id user
    $id_equipo = $this->dbEquipo->getEquipo($id);//Return Id equipo
    $id_equipo = $id_equipo['id'];
    $sql = "SELECT * FROM turno WHERE id_equipo_solicitante='$id_equipo'";
    $result = $this->db->conn->query($sql);
    if(!$result===FALSE){
      $result = $result->fetch();
    }
    return $result;
  }


}
