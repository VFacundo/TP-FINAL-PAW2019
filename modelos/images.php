<?php
namespace UNLu\PAW\Modelos;
use UNLu\PAW\Modelos\dbconnect;
use PDO;

/*
CREATE TABLE IF NOT EXISTS images (
  id int NOT NULL auto_increment,
  tipo char(15) NOT NULL,
  imagen mediumblob NOT NULL,
  PRIMARY KEY (id)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
*/

class images{
    private $db;
      public function __construct(){
          $this->db = new dbconnect("dbturnos");
      }

      public function cargarImagen($datosImg){//Recibe nombre img y exten.
        $id = -1;
        if(is_uploaded_file($datosImg['imgTurno']['tmp_name'])){
          $tipoImagen = $datosImg['imgTurno']['type'];
              if(($tipoImagen == "image/jpeg") || ($tipoImagen == "image/png")){
                $infoImg = getimagesize($datosImg['imgTurno']['tmp_name']);
                  if(($datosImg['imgTurno']['size'])<10000000){
                      $imagen = $this->db->conn->quote(file_get_contents($datosImg['imgTurno']['tmp_name']));
                      $imagen = addslashes($imagen);
                      $sql = "INSERT INTO images(id,tipo,imagen) VALUES (NULL,'$tipoImagen','$imagen')";
                      $resultado = $this->db->conn->query($sql);
                      $id = $this->db->conn->lastInsertId();
                  }
              }
          }
          return $id;
        }/*Fin Cargar Imagen */

        public function selectImagen($id){
          $sql = "SELECT * FROM images WHERE id='$id'";
          echo $id;
          //$result = $this->db->conn->prepare($sql)->execute();
          $result = $this->db->conn->query($sql);
          //$result = $this->db->conn->execute();
          $result = $result->fetch(PDO::FETCH_ASSOC);
          return stripslashes($result['imagen']);
        }
}
