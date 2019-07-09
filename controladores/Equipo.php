<?php
namespace UNLu\PAW\Controladores;
use UNLu\PAW\Libs\VIstaHTML;
use UNLu\PAW\Modelos\users;
use UNLu\PAW\Modelos\equipos;
use UNLu\PAW\Modelos\turnosdb;

class Equipo extends \UNLu\PAW\Libs\Controlador{

  private static $initialized = false;
  private static $db;
  private static $dbu;
  private static $dbTurnos;

  private static function initialize(){
    if(self::$initialized)
      return true;
      self::$db = new equipos();
      self::$dbu = new users();
      self::$dbTurnos = new turnosdb();
    self::$initialized=true;
  }

  public function miequipo(){
    sesion::startSession();
    self::initialize();
    if(sesion::is_login()){
      $miEquipo = self::$db->getEquipo(sesion::getId());
      $this->pasarVariableAVista("equipo",$miEquipo);
      $this->pasarVariableAVista("datosUserJug",self::$dbu->datosUserJug(sesion::getId()));
        if($miEquipo){
          $jugadoresEquipo = self::$db->getJugadoresEquipo($miEquipo['id']);
          $this->pasarVariableAVista("logo_equipo",$jugadoresEquipo[0]['logo']);
          $this->pasarVariableAVista("nombre_equipo",$jugadoresEquipo[0][1]);
          $this->pasarVariableAVista("jugadores",$jugadoresEquipo);
          $this->pasarVariableAVista("solicitudesPartidos", self::$dbTurnos->desafios(sesion::getId()));
        }
        $this->pasarVariableAVista("equiposComoJugador",self::$db->misEquiposJugador(sesion::getId()));
      sesion::refreshTime();
    }else{
      $this->redireccionarA($_SERVER['REQUEST_URI'],'/login');
    }
  }

  public function nuevoequipo(){
    sesion::startSession();
    self::initialize();
    if(sesion::is_login()){
      sesion::refreshTime();
      $nombre_equipo = $_POST['nombreEquipo'];
      $img_equipo = $_FILES;
      $nombre_j = $_POST['nombre'];
      $edad_j = $_POST['edad'];
      $usr_j = $_POST['user'];
        if(self::$db->verifyNombre($nombre_equipo)===FALSE){
          self::$db->newEquipo($nombre_equipo,$img_equipo,$nombre_j,$edad_j,$usr_j,sesion::getId());
          echo('Equipo Creado!');
        }else {
          echo('Ya existe un Equipo Con ese Nombre!');
        }
      exit();
    }
  }

  public function cambiarimg(){
    sesion::startSession();
    self::initialize();
      if(sesion::is_login()){
        $img_equipo = $_FILES['imgFile'];
          $result = self::$db->cambiarimg($img_equipo,sesion::getId());
            if($result){
                echo $result;
            }else {
              echo "400";
            }
      }
      exit();
  }

  public function editarJugadorEquipo(){
    sesion::startSession();
    self::initialize();
      if(sesion::is_login()){
        if(isset($_POST['id_jugador'])){
            $id_jugador = $_POST['id_jugador'];
        }else{
          $id_jugador = NULL;
        }
        $nombre_jugador = $_POST['name'];
        $edad_jugador = $_POST['edad'];
        $usr_jugador = $_POST['user'];
          if(self::$db->editarJugadorEquipo(sesion::getId(),$id_jugador,$nombre_jugador,$edad_jugador,$usr_jugador)){
            echo "200";
          }
      }else {
        echo "404";
      }
      exit();
  }

  public function editnombreequipo(){
    sesion::startSession();
    self::initialize();
      if(sesion::is_login()){
        $nombre_equipo = $_POST['nombre_equipo'];
        if(self::$db->verifyNombre($nombre_equipo)===FALSE){
          if(self::$db->changeTeamName($nombre_equipo,sesion::getId())){
            echo($nombre_equipo);
          }else {
            echo("400");
          }
        }else {
          echo("400");
        }
      }
      exit();
  }

  public function borrarjugadorequipo(){
    sesion::startSession();
    self::initialize();
      if(sesion::is_login()){
          $id_jugador = $_POST['id_jugador'];
        if(self::$db->borrarJugadorEquipo($id_jugador,sesion::getId())){
            echo "200";
        } else{
            echo "404";
        }
      }else {
        echo "404";
      }
      exit();
  }

}
