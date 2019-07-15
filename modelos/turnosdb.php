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

    public function borrarTurno($id,$id_turno){
      $result = FALSE;
      date_default_timezone_set('America/Argentina/Buenos_Aires');
      $turno = $this->getTurno($id_turno,$id);
      if($turno){
        $cancel_turno = date_interval_create_from_date_string('45' .' minutes');
        $fecha = $turno[0]['fecha'];
        $horario = $turno[0]['horario_turno'];
        $fecha_hora = date_create_from_format('Y-m-d H:i:s', $fecha . $horario);
        //$actual = date('Y-m-d H:i:s');
        $actual = new \DateTime("now");
        echo '';
        $actual->add($cancel_turno);
          if($fecha_hora>$actual){
              $sql = "DELETE FROM turno WHERE id='$id_turno' AND id_solicitante='$id'";
              $result = $this->db->conn->prepare($sql)->execute();
          }
      }
    return $result;
    }
      /*
        9:00
        8:15
      */
    public function aceptarDesafio($id,$id_desafio){
      $data_desafio = $this->verificarDesafio($id,$id_desafio);
      $result = NULL;
      if($data_desafio){
        $id_turno = $data_desafio['id_turno'];
          $sql = "DELETE FROM desafio WHERE id_turno='$id_turno'";
          $result = $this->db->conn->prepare($sql)->execute();
            if($result){
              $idCapitanRival = $this->dbEquipo->dataCapitan($data_desafio['id_equipo']);
              $id_equipo_rival = $this->dbEquipo->getIdUsuario($idCapitanRival[0]['id'])[0];
              $sql = "UPDATE turno SET id_equipo_rival='$id_equipo_rival',tipo_turno='1' WHERE id = '$id_turno'";
                $result = $this->db->conn->prepare($sql)->execute();
            }
      }
      return $result;
    }

    public function borrarDesafio($id,$id_desafio){
      if($this->verificarDesafio($id,$id_desafio)){
        $sql = "DELETE FROM desafio WHERE id='$id_desafio'";
        $result = $this->db->conn->prepare($sql)->execute();
        return $result;
      }
    }

    public function verificarDesafio($id,$id_desafio){
      $sql = "SELECT * FROM turno t
	              INNER JOIN desafio d on t.id = d.id_turno
                    WHERE t.id_solicitante='$id' AND t.tipo_turno='3' AND d.id = '$id_desafio'";
      $result = $this->db->conn->query($sql);
      if(!$result===FALSE){
        $result = $result->fetch();
        }
      return $result;
    }

    public function buscarDesafio($id_turno,$id){
      $id_equipo = $this->dbEquipo->getEquipo($id)['id'];
      $sql = "SELECT * FROM desafio WHERE id_equipo = '$id_equipo' AND id_turno = '$id_turno'";
      $result = $this->db->conn->query($sql);
        if(!$result===FALSE){
          $result = $result->fetch();
        }
      return $result;
    }

    public function desafios($id){
      $formato = '%H:%i';
      $sql = "SELECT d.id,t.fecha,time_format(t.horario_turno, '$formato') AS horario_turno,t.id_cancha,d.id_equipo,c.direccion,c.nombre FROM turno t
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
      $formato = '%H:%i';
      $sql = "SELECT t.id, t.tipo_turno, t.fecha,t.id_equipo_rival,time_format(t.horario_turno, '$formato') AS horario_turno,c.nombre,c.direccion FROM turno t
                INNER JOIN cancha c on t.id_cancha=c.id
                  WHERE id_solicitante='$id' AND (fecha<CURDATE())";
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


        public function getMailDataTurno($id_turno){
          $sql = "SELECT t.horario_turno,t.id_cancha, t.fecha, u.mail FROM turno t
    				       INNER JOIN usuario u on t.id_solicitante = u.id
    	            	WHERE t.id = '$id_turno'";
          $result = $this->db->conn->query($sql);
            if(!$result===FALSE){
              $result = $result->fetch();
    		  $cancha = $this->data_cancha($result['id_cancha']);
    		  $result = $result + array('cancha' => $cancha[0]['nombre'].' - '.$cancha[0]['direccion']);
            }
          return $result;
        }

        public function getTurnoFromIdDesafio($id_desafio){
          $sql = "SELECT id_turno,id_equipo FROM desafio WHERE id='$id_desafio'";
          $result = $this->db->conn->query($sql);
            if(!$result===FALSE){
              $result = $result->fetch();
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
       $sql = "SELECT * FROM turno WHERE CONCAT(fecha,' ',horario_turno)>=NOW() AND tipo_turno = 3";
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
                           "horario_turno" => date('H:i',strtotime($value['horario_turno'])),
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

public function horariosDisponibles($cancha_turno,$fecha_turno,$tipo_turno){
  if($tipo_turno == 3){
    $sql = "SELECT t.horario_turno, c.horario_apertura, c.horario_cierre, c.duracion_turno FROM turno t
              INNER JOIN cancha c on t.id_cancha = c.id
                  WHERE fecha = '$fecha_turno' AND id_cancha = '$cancha_turno'";
  }else {
    $sql = "SELECT t.horario_turno, c.horario_apertura, c.horario_cierre, c.duracion_turno FROM turno t
  	          INNER JOIN cancha c on t.id_cancha = c.id
  	            	WHERE fecha = '$fecha_turno' AND id_cancha = '$cancha_turno' AND tipo_turno != 3";
  }
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
  $sql = "SELECT * FROM cancha";
  $result = $this->db->conn->query($sql);
  if(!$result===FALSE){
    $result = $result->fetchAll();
  }
  return $result;
}

public function buscarTurnoHD($fecha,$horario){//Buscar por Hora y Dia
  $sql = "SELECT id,tipo_turno,id_solicitante,fecha,horario_turno FROM turno WHERE fecha='$fecha' AND horario_turno='$horario'";
  $result = $this->db->conn->query($sql);
  if(!$result===FALSE){
    $result = $result->fetch();
  }
  return $result;
}

public function newTurno($tipoTurno,$fecha,$horario,$cancha,$equipo_rival,$id,$origen){
  $sql = "INSERT INTO turno(id,fecha,horario_turno,id_solicitante,id_equipo_rival,id_cancha,tipo_turno,origen_turno) VALUES (?,?,?,?,?,?,?,?)";

  switch ($tipoTurno) {
    case 0://Turno NORMAL
      $id_solicitante = $id;//Guardo ID USER
      $id_equipo_rival = NULL;
      break;
    case 1://Mi Equipo vs Otro Equipo
      $id_solicitante = $id;
      $idCapitanRival = $this->dbEquipo->getCapitan($equipo_rival);
      $id_capitan = $this->dbEquipo->getIdUsuario($idCapitanRival['id_capitan']);
      $id_equipo_rival = $id_capitan[0];
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
  $resultado = $this->db->conn->prepare($sql)->execute([NULL,$fecha,$horario,$id_solicitante,$id_equipo_rival,$cancha,$tipoTurno,$origen]);
  //$info = $this->db->conn->errorCode();
  $id_turno = $this->db->conn->lastInsertId();

if($resultado){
    if($tipoTurno==1){
        //ADD Asistir-No Asistir
        $id_equipo = $this->dbEquipo->getEquipo($id_solicitante);
        $id_equipo = $id_equipo['id'];
        $jugadores = $this->dbEquipo->getJugadoresEquipo($id_equipo);
          foreach($jugadores as $valueJug){
            if(!empty($valueJug['user'])){
              $sql = "INSERT INTO asistir_turno (id_jugador,id_turno,estado) VALUES (?,?,?)";
              $this->db->conn->prepare($sql)->execute([$valueJug['id'],$id_turno,2]);//VALUES 0 No ASISTE | 1 ASISTE | 2 No CONFIRM
            }
          }
    }
  }
}

public function asistir_turno($id_turno){
  $sql = "SELECT COUNT(IF(estado=0,estado,null)) AS no_asisten,
		        COUNT(IF(estado=1,estado,null)) AS asisten,
              COUNT(IF(estado=2,estado,null)) AS no_confirmo
          FROM asistir_turno
          WHERE id_turno = '$id_turno'";
  $result = $this->db->conn->query($sql);
    if(!$result===FALSE){
      $result = $result->fetch();
    }
  return $result;
}

public function buscarMisTurnos($id){//Id user
    $formato = '%H:%i';
    $sql = "SELECT t.id, t.tipo_turno, t.fecha,t.id_equipo_rival,time_format(t.horario_turno, '$formato') AS horario_turno,c.nombre,c.direccion FROM turno t
	            INNER JOIN cancha c on t.id_cancha=c.id
                WHERE id_solicitante='$id' AND CONCAT(t.fecha,' ',t.horario_turno)>=NOW()";
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
            $id_capitan = $this->dbEquipo->getIdJugador($value['id_equipo_rival']);
            $capitan = $this->dbEquipo->datosJugador($id_capitan);
            $asisten = $this->asistir_turno($value['id']);
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
                          'asisten' => $asisten,
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

  public function getAsisto($id_jugador,$id_turno){
    $sql = "SELECT estado FROM asistir_turno WHERE id_jugador='$id_jugador' AND id_turno='$id_turno'";
    $result = $this->db->conn->query($sql);
      if(!$result===FALSE){
        $result = $result->fetch();
      }
    return $result;
  }

  public function asistencia($id,$id_turno,$asisto){
      if($asisto === "true"){
        $asisto = 1;
      }else {
        $asisto = 0;
      }
      $id = $this->dbEquipo->getIdJugador($id);
      $sql = "UPDATE asistir_turno SET estado='$asisto' WHERE id_jugador='$id' AND id_turno='$id_turno'";
      $result = $this->db->conn->prepare($sql)->execute();
    return $result;
  }

  public function misTurnosJugador($id){
    $misEquiposJugador = $this->dbEquipo->misEquiposJugador($id);
    $id_jugador = $this->dbEquipo->getIdJugador($id);
      if($misEquiposJugador){
          foreach($misEquiposJugador as $value){
            $id_capitan = $this->dbEquipo->getIdUsuario($value['id_capitan']);
            $turnosEquipo = $this->buscarMisTurnos($id_capitan);
              if(isset($turnosEquipo['tvt'])){
                foreach($turnosEquipo['tvt'] as $turnosTvt){
                  $asisto = $this->getAsisto($id_jugador,$turnosTvt['id']);
                  $partidos[] = [
                      'miequipo' => $value,
                      'rival' => $turnosTvt,
                      'asisto' => $asisto['estado'],
                  ];
                }
              }
          }
      }
      if(empty($partidos)){
        $partidos = FALSE;
      }
      return $partidos;
  }
}
