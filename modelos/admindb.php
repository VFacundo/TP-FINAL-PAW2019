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

    public function addCancha($array_cancha){
      $sql = "INSERT INTO cancha VALUES(id,nombre,direccion,telefono,horario_cierre,horario_apertura,duracion_turno)
                  VALUES (:nombre_cancha,:direccion_cancha,:telefono_cancha,:horario_apertura,:horario_cierre,:duracion_turno)";
      $result = $this->db->conn->prepare($sql)->execute($array_cancha);
      return $result;
    }

    public function borrarCancha($id_cancha){
      $sql = "DELETE FROM cancha WHERE id='$id_cancha'";
      $result = $this->db->conn->prepare($sql)->execute();
    return $result;
    }

    public function borrarTurno($id_turno){
      $sql = "DELETE FROM turno WHERE id='$id_turno'";
      $result = $this->db->conn->prepare($sql)->execute();
    return $result;
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

  public function turnos_reservados(){
    $formato = '%H:%i';
    $sql = "SELECT t.id, t.tipo_turno, t.fecha,t.id_solicitante,t.origen_turno,time_format(t.horario_turno, '$formato') AS horario_turno,c.nombre,c.direccion,u.mail FROM turno t
              INNER JOIN cancha c on t.id_cancha = c.id
                INNER JOIN usuario u on t.id_solicitante = u.id
                WHERE CONCAT(fecha,' ',horario_turno)>=NOW()
                 ORDER BY CONCAT(fecha,' ',horario_turno) DESC ";
    $result = $this->db->conn->query($sql);
    $arrayResultado = [];
      if(!$result===FALSE){
        $result = $result->fetchAll();
          foreach($result as $value){
                  switch ($value['tipo_turno']) {
                    case '0':
                      $tipo_turno = "Turno Simple";
                      break;
                      case '1':
                        $tipo_turno = "Turno TVT";
                        break;
                        case '3':
                          $tipo_turno = "Buscar Partido";
                          break;
                  }
                    $arrayResultado[] =[
                          'id_turno' => $value['id'],
                          'fecha_turno' => $value['fecha'],
                          'horario_turno' => $value['horario_turno'],
                          'nombre_cancha' => $value['nombre'],
                          'direccion_cancha' => $value['direccion'],
                          'tipo_turno' => $tipo_turno,
                          'origen_turno' => $value['origen_turno'],
                          'mail_solicitante' => $value['mail'],
                    ];
          }
  }
  return $arrayResultado;
}

}
?>
