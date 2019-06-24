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

public function buscarTurnoHD($fecha,$horario){//Buscar por Hora y Dia
  $sql = "SELECT id FROM turno WHERE fecha='$fecha' AND horario_turno=$horario";
  $result = $this->db->conn->query($sql);
  if(!$result===FALSE){
    $result = $result->fetch();
  }
  return $result;
}

public function newTurno($tipoTurno,$fecha,$horario,$cancha,$equipo_rival,$id){
  $sql = "INSERT INTO turno(id,fecha,horario_turno,id_equipo_solicitante,id_equipo_rival,id_cancha,id_tipo,origen_turno) VALUES (?,?,?,?,?,?,?,?)";

  switch ($tipoTurno) {
    case 0://Turno NORMAL
      $id_equipo_solicitante = $id;//Guardo ID USER
      $id_equipo_rival = NULL;
      break;
    case 1://Mi Equipo vs Otro Equipo
      $id_equipo_solicitante = $this->dbEquipo->getEquipo($id);
      $id_equipo_solicitante = $id_equipo_solicitante['id'];
      $id_equipo_rival = $this->dbEquipo->getEquipoNombre($equipo_rival);
      break;
    case 2://Mi Equipo vs Invitado
      $id_equipo_solicitante = $this->dbEquipo->getEquipo($id);
      $id_equipo_solicitante = $id_equipo_solicitante['id'];
      $id_equipo_rival = NULL;
      break;
    case 3://Mi Equipo Lista de Espera
      $id_equipo_solicitante = $this->dbEquipo->getEquipo($id);
      $id_equipo_solicitante = $id_equipo_solicitante['id'];
      $id_equipo_rival = NULL;
      break;
  }
  $resultado = $this->db->conn->prepare($sql)->execute([NULL,$fecha,$horario,$id_equipo_solicitante,$id_equipo_rival,0,$tipoTurno,'web']);
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
