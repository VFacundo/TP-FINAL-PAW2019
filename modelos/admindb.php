<?php
namespace UNLu\PAW\Modelos;
use UNLu\PAW\Modelos\dbconnect;
use UNLu\PAW\Controladores\sesion;
use PDO;


class admindb{
  private $db;
    public function __construct(){
      $this->db = new dbconnect();
    }

	public function buscarCanchas(){
		$formato = '%H:%i';
		$sql= "SELECT id, nombre, direccion, telefono, time_format(horario_cierre, '$formato') as horario_cierre, time_format(horario_apertura, '$formato') as horario_apertura, duracion_turno FROM `cancha`";
		$result = $this->db->conn->query($sql);
		$arrayResultado;
		if(!$result===FALSE){
		  $result = $result->fetchAll();
			if(!$result===FALSE){
				foreach($result as $value){
					$arrayResultado[] = [
						'id' => $value['id'],
						'nombre' => $value['nombre'],
						'direccion' => $value['direccion'],
						'telefono' => $value['telefono'],
						'horaC' => $value['horario_cierre'],
						'horaA' => $value['horario_apertura'],
						'duracion' => $value['duracion_turno'],
					];
				}
			}
		}
		return $arrayResultado;
  }
  
}
?>
