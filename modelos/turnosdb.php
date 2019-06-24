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
