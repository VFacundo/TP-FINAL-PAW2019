<?php
namespace UNLu\PAW\Modelos;
use UNLu\PAW\Modelos\dbconnect;
use PDO;

class dbsearch{
  private $db;
    public function __construct(){
      $this->db = new dbconnect();
    }

    public function search($cadena_busqueda,$tableName){

        switch ($tableName) {
          case 'usuario':
            $campo = 'user';
            break;
          case 'equipo':
              $campo = 'nombre';
            break;
        }
        $cadena_busqueda = strtolower(preg_replace('/[^a-z0-9-]+/i',' ',$cadena_busqueda));
        $sql = "SELECT $campo FROM $tableName WHERE $campo LIKE '$cadena_busqueda%'";
        $result = $this->db->conn->query($sql);
        if(!$result===FALSE){
            $result = $result->fetchAll();
        }
        return $result;
    }
}
