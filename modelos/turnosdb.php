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

    public function desafios($id){
      $sql = "SELECT d.id,t.fecha,t.horario_turno,t.id_cancha,d.id_equipo,c.direccion,c.nombre FROM turno t
	                INNER JOIN desafio d on t.id = d.id_turno
    	              INNER JOIN cancha c on t.id_cancha = c.id
		                	WHERE t.id_solicitante = '$id'";
      $result = $this->db->conn->query($sql);
      $arrayResultado = [];
        if(!$result===FALSE){
          $result = $result->fetchAll();
            foreach ($result as $value) {
                $jugadores = $this->dbEquipo->getJugadoresEquipo($value['id_equipo']);
                $capitan = $this->dbEquipo->dataCapitan($value['id_equipo']);
                $edad_promedio = $this->calc_promedio_edad($jugadores,$capitan);
                $arrayResultado[] = [
                    "id_desafio" => $value['id'],
                    "fecha_turno" => $value['fecha'],
                    "horario_turno" => $value['horario_turno'],
                    "direccion_cancha" => $value['direccion'],
                    "cancha" => $value['nombre'],
                    "jugadores" => $jugadores,
                    "capitan" => $capitan,
                    "nombre_equipo" => $jugadores[0][1],
                    "logo_equipo" => $jugadores[0]['logo'],
                    "edad_promedio" => $edad_promedio,
                    ];
            }
        }
      return $arrayResultado;
    }

    public function historial($id){
      $sql = "SELECT t.id, t.tipo_turno, t.fecha,t.id_equipo_rival, t.horario_turno,c.nombre,c.direccion FROM turno t
                INNER JOIN cancha c on t.id_cancha=c.id
                  WHERE id_solicitante='$id' AND (fecha<=CURDATE() AND horario_turno<=CURTIME())";
      $result = $this->db->conn->query($sql);
      $arrayResultado = [];
        if(!$result===FALSE){
          $result = $result->fetchAll();
            foreach($result as $value){
                if($value['tipo_turno']==0 || $value['tipo_turno']==3){
                    $equipo_rival = null;
                    $tipo_turno = ($value['tipo_turno']==0)?"Turno Simple":(($value['tipo_turno']==3)?"Turno TVT":$value['tipo_turno']);
                  if(!empty($value['id_equipo_rival'])){
                      $idEquipoRival = $this->dbEquipo->getEquipo($value['id_equipo_rival']);
                      $equipo_rival = $idEquipoRival['nombre'];
                  }
                      $arrayResultado[] =[
                            'fecha_turno' => $value['fecha'],
                            'horario_turno' => $value['horario_turno'],
                            'nombre_cancha' => $value['nombre'],
                            'direccion_cancha' => $value['direccion'],
                            'tipo_turno' => $tipo_turno,
                            'equipo_rival' => $equipo_rival,
                      ];
                    }
            }
    }
    return $arrayResultado;
  }

    public function registrarDesafio($id_turno,$id){
      $id_equipo = $this->dbEquipo->getEquipo($id)['id'];
      $result;
        if(!empty($id_equipo)){
          $sql = "INSERT INTO desafio(id,id_equipo,id_turno,fecha_registro,horario_registro,estado) VALUES (NULL,$id_equipo,$id_turno,CURDATE(),CURTIME(),'publicado')";
          $resultado = $this->db->conn->prepare($sql)->execute();
          $info = $this->db->conn->errorCode();
          $result = TRUE;
        }else {
          $result = FALSE;
        }
        return $result;
    }

    public function getTurno($id_turno,$id){//In idTurno,idusr return si el turno es d el usr
      $sql = "SELECT * FROM turno WHERE id='$id_turno' AND id_solicitante='$id'";
      $result = $this->db->conn->query($sql);
        if(!$result===FALSE){
          $result = $result->fetchAll();
        }
      return $result;
    }

    public function buscarPartido(){
       $sql = "SELECT * FROM turno WHERE (fecha>=CURDATE() AND horario_turno>=CURTIME()) AND tipo_turno = 3";
       $result = $this->db->conn->query($sql);
       $arrayResultado = [];
         if(!$result===FALSE){
           $result = $result->fetchAll();
             foreach ($result as $value) {
                   $datos_solicitante = $this->dbEquipo->getIdJugador($value['id_solicitante']);
                   $datos_solicitante = $this->dbEquipo->datosJugador($datos_solicitante);
                   $id_equipo = $this->dbEquipo->getEquipo($value['id_solicitante']);
                   $datos_equipo = $id_equipo;
                   $datos_cancha = $this->data_cancha($value['id_cancha']);
                   $jugadores = $this->dbEquipo->getJugadoresEquipo($datos_equipo['id']);
                   $promedio_edad = $this->calc_promedio_edad($jugadores,$datos_solicitante);
                 $arrayResultado[] = [
                           "id_turno" => $value['id'],
                           "nombre_equipo" => $datos_equipo['nombre'],
                           "logo_equipo" => $datos_equipo['logo'],
                           "promedio_edad" => $promedio_edad,
                           "fecha_turno" => $value['fecha'],
                           "horario_turno" => $value['horario_turno'],
                           "nombre_cancha" => $datos_cancha[0]['nombre'],
                           "direccion_cancha" => $datos_cancha[0]['direccion'],
                           "capitan" => $datos_solicitante,
                           "jugadores" => $jugadores,
                 ];
             }
           }
       return $arrayResultado;
    }

    public function calc_promedio_edad($jugadores,$capitan){
      $promedio_edad = 0;
        foreach ($jugadores as $valueJug) {
            $promedio_edad += $valueJug['edad'];
        }
        $nro = count($jugadores);
        $promedio_edad += $capitan[0]['edad'];
        $promedio_edad = $promedio_edad / (count($jugadores) + 1);
      return round($promedio_edad,2);
    }

    public function listaBuscarPartido(){
       $sql = "SELECT * FROM turno WHERE (fecha>=CURDATE() AND horario_turno>=CURTIME()) AND tipo_turno = 3";
       $result = $this->db->conn->query($sql);
         if(!$result===FALSE){
           $result = $result->fetchAll();
           }
       return $result;
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
              if(isset($turnosEquipo['tvt'])){
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
