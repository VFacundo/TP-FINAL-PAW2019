<?php
namespace UNLu\PAW\Modelos;
use PDO;

class imagen{
  private $directorioEquipos;
  private $directorioJugadores;
    public function __construct(){
      $this->directorioEquipos = dirname(__DIR__) . '/img/equipos/';
      $this->directorioJugadores = dirname(__DIR__) . '/img/userImg/';
    }

    public function subirImagen($datosImg,$user){
        if($user){
          $directorioImagenes = $this->directorioJugadores;
        }else {
          $directorioImagenes = $this->directorioEquipos;
        }
        if(is_uploaded_file($datosImg['imgFile']['tmp_name'])){
          $tipoImagen = $datosImg['imgFile']['type'];
              if(($tipoImagen == "image/jpeg") || ($tipoImagen == "image/png")){
                $infoImg = getimagesize($datosImg['imgFile']['tmp_name']);
                  if(($datosImg['imgFile']['size'])<3000000){
                      //////////////
                      $tipo_funcion = [
                        'image/jpeg' => 'imagecreatefromjpeg',
                        'image/jpg' => 'imagecreatefromjpeg',
                        'image/png' => 'imagecreatefrompng',
                        'image/gif' => 'imagecreatefromgif'
                      ];
                      $new_img = call_user_func($tipo_funcion[$tipoImagen],$datosImg["imgFile"]["tmp_name"]);
                      $archivoImagen = time() . basename($_FILES["imgFile"]["name"]);
                      $destino = $directorioImagenes . $archivoImagen;
                      imagejpeg($new_img, $destino, 10);
                      //////////////

                      //$archivoImagen = time() . basename($_FILES["imgFile"]["name"]);
                      //move_uploaded_file($datosImg["imgFile"]["tmp_name"],$directorioImagenes . $archivoImagen);
                      $folder = explode("/",$directorioImagenes);
                      $archivoImagen =  $folder[count($folder)-2] . '/' . $archivoImagen;
                  }
              }
          }
          return $archivoImagen;
  }
}
