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
      $miEquipo = $db->getEquipo(sesion::getId());
      $this->pasarVariableAVista("equipo",$miEquipo);
      $this->pasarVariableAVista("datosUserJug",$dbu->datosUserJug(sesion::getId()));
        if($miEquipo){
          $jugadoresEquipo = $db->getJugadoresEquipo($miEquipo['id']);
          $this->pasarVariableAVista("logo_equipo",$jugadoresEquipo[0]['logo']);
          $this->pasarVariableAVista("nombre_equipo",$jugadoresEquipo[0][1]);
          $this->pasarVariableAVista("jugadores",$jugadoresEquipo);
        }
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
          echo('Equipo Creado!');
        }else {
          echo('Ya existe un Equipo Con ese Nombre!');
        }
      exit();
    }
  }
}
