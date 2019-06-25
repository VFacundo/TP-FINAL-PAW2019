<?php
namespace UNLu\PAW\Modelos;
use UNLu\PAW\Modelos\dbconnect;
use PDO;

class equipos{
  private $db;
    public function __construct(){
      $this->db = new dbconnect();
    }



public function getJugadoresEquipo($id_equipo){//Input id Equipo Return Todos los jugadores
  $sql = "SELECT e.logo,e.nombre,j.nombre,j.img_src,j.edad,u.user FROM equipo e
	         INNER JOIN jugador_equipo je on (je.id_equipo = e.id)
     	        INNER JOIN jugador j on (j.id = je.id_jugador)
                	LEFT JOIN usuario u on (u.id = j.id_usuario)
                   WHERE e.id = '$id_equipo'";
  $result = $this->db->conn->query($sql);
  if(!$result===FALSE){
    $result = $result->fetchAll();
  }
  return $result;
}

public function verifyNombre($nombre){
  $this->sanitizeString($nombre);
  $sql = $sql = "SELECT id FROM equipo WHERE nombre='$nombre'";
  $result = $this->db->conn->query($sql);
  if(!$result===FALSE){
    $result = $result->fetch();
  }
  return $result;
}

public function subirLogo($datosImg){
  $directorioImagenes = dirname(__DIR__) . '/img/equipos/';
  if(is_uploaded_file($datosImg['imgFile']['tmp_name'])){
    $tipoImagen = $datosImg['imgFile']['type'];
        if(($tipoImagen == "image/jpeg") || ($tipoImagen == "image/png")){
          $infoImg = getimagesize($datosImg['imgFile']['tmp_name']);
            if(($datosImg['imgFile']['size'])<10000000){
                $archivoImagen = time() . basename($_FILES["imgFile"]["name"]);
                move_uploaded_file($datosImg["imgFile"]["tmp_name"],$directorioImagenes . $archivoImagen);
                $archivoImagen = 'equipos/' . $archivoImagen;
            }
        }
    }
    return $archivoImagen;
}

public function getIdJugador($id){//id usuario
  $sql = "SELECT id FROM jugador WHERE id_usuario='$id'";
  $result = $this->db->conn->query($sql);
  if(!$result===FALSE){
    $result = $result->fetch();
    $result = $result['id'];
  }
  return $result;
}

public function sanitizeString($string){
  $string = strtolower(preg_replace('/[^a-z0-9-]+/i',' ',$string));
  return $string;
}
public function sanitizeInt($int){
  $int = preg_replace('/[^0-9]+/i',' ',$int);
  return $int;
}

public function getIdUser($user){//Retorna el id de jugador de un usuario
  $sql = "SELECT id FROM usuario WHERE user='$user'";
  $result = $this->db->conn->query($sql);
  if(!$result===FALSE){
    $result = $result->fetch();
    $result = $result['id'];
    $sql = "SELECT id FROM jugador WHERE id_usuario='$result'";
    $result = $this->db->conn->query($sql);
      if(!$result===FALSE){
        $result = $result->fetch();
        $result = $result['id'];
      }
  }
  return $result;
}

public function newEquipo($nombre_equipo,$img_equipo,$nombre_j,$edad_j,$usr_j,$id){
  $nombre_equipo = $this->sanitizeString($nombre_equipo);
  if(!empty($img_equipo)){
    $img_equipo = $this->subirLogo($img_equipo);
  }else {
    $img_equipo = 'avatar' . rand(1,3) . ".svg";;//aca van los avatares default
  }
  $id_capitan = $this->getIdJugador($id);
  if($id_capitan){
    $sql = "INSERT INTO equipo(id,nombre,logo,id_capitan) VALUES (?,?,?,?)";
    $resultado = $this->db->conn->prepare($sql)->execute([NULL,$nombre_equipo,$img_equipo,$id_capitan]);
  }/////FIN Inset Equipo

  if(!$resultado===FALSE){
    $id_equipo = $this->db->conn->lastInsertId();

    for ($i=0; $i < sizeof($nombre_j) ; $i++) {
      $nombre_jugador = $this->sanitizeString($nombre_j[$i]);
      $edad_jugador = $this->sanitizeInt($edad_j[$i]);
        if(empty($usr_j[$i])){
          $imgJugador = 'avatar' . rand(1,3) . ".svg";
          $sql = "INSERT INTO jugador(id,nombre,img_src,edad) VALUES(?,?,?,?)";
          $resultado = $this->db->conn->prepare($sql)->execute([NULL,$nombre_jugador,$imgJugador,$edad_jugador]);
              if(!$resultado===FALSE){
                $id_jugador = $this->db->conn->lastInsertId();
              }
        }else{
          $id_jugador = $this->getIdUser($usr_j[$i]);
        }
          $sql = "INSERT INTO jugador_equipo(id_jugador,id_equipo) VALUES(?,?)";
          $this->db->conn->prepare($sql)->execute([$id_jugador,$id_equipo]);
    }
  }
}

public function getEquipoNombre($nombre_equipo){
  $sql = "SELECT id FROM equipo WHERE nombre='$nombre_equipo'";
  $result = $this->db->conn->query($sql);
  if(!$result===FALSE){
    $result = $result->fetch();
    $result = $result['id'];
  }
  return $result;
}

public function getEquipo($id){//Regresa el id de equipo Propio
 $id_jugador = $this->getIdJugador($id);
 $sql = "SELECT * FROM equipo WHERE id_capitan='$id_jugador'";
 $result = $this->db->conn->query($sql);
 if(!$result===FALSE){
   $result = $result->fetch();
 }
 return $result;
}

}
