<?php
namespace UNLu\PAW\Modelos;
use UNLu\PAW\Modelos\dbconnect;
use PDO;

class equipos{
  private $db;
    public function __construct(){
      $this->db = new dbconnect();
    }

public function getEquipo($id){
 $sql = "SELECT * FROM equipo WHERE id_capitan='$id'";
 $result = $this->db->conn->query($sql);
 if(!$result===FALSE){
   $result = $result->fetch();
 }
 return $result;
}

}
