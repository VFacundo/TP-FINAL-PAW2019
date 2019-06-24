<?php
namespace UNLu\PAW\Controladores;
use UNLu\PAW\Libs\VIstaHTML;
use UNLu\PAW\Modelos\users;
use UNLu\PAW\Modelos\equipos;

class Equipo extends \UNLu\PAW\Libs\Controlador{

  public function miequipo(){
    $db = new equipos();
    $dbu = new users();
    sesion::startSession();
    if(sesion::is_login()){
      $this->pasarVariableAVista("equipo",$db->getEquipo(sesion::getId()));
      $this->pasarVariableAVista("datosUserJug",$dbu->datosUserJug(sesion::getId()));
      sesion::refreshTime();
    }else{
      $this->redireccionarA($_SERVER['REQUEST_URI'],'/login');
    }
  }

  public function nuevoequipo(){
    sesion::startSession();
    if(sesion::is_login()){
      sesion::refreshTime();
      $db = new equipos();
      $nombre_equipo = $_POST['nombreEquipo'];
      $img_equipo = $_FILES;
      $nombre_j = $_POST['nombre'];
      $edad_j = $_POST['edad'];
      $usr_j = $_POST['user'];
        if($db->verifyNombre($nombre_equipo)===FALSE){
          $db->newEquipo($nombre_equipo,$img_equipo,$nombre_j,$edad_j,$usr_j,sesion::getId());
          echo('ok');
        }else {
          echo('Ya existe un Equipo Con ese Nombre');
        }
      exit();
    }
  }
}
