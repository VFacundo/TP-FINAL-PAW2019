<?php
namespace UNLu\PAW\Controladores;
use UNLu\PAW\Libs\VIstaHTML;
use UNLu\PAW\Modelos\dbsearch;

class Instantsearch extends \UNLu\PAW\Libs\Controlador{

private static $initialized = false;
private static $dbSearch;

private static function initialize(){
  if(self::$initialized)
    return true;
    self::$dbSearch = new dbsearch();
  self::$initialized=true;
}

  public function search(){
    self::initialize();
    $tableName = $_POST['tableName'];
    $cadena_busqueda = $_POST['cadena_busqueda'];
    $result = self::$dbSearch->search($cadena_busqueda,$tableName);
    $mensaje = null;
      foreach($result as $value){
        $mensaje.= '<option value="' . $value[0] . '">';
      }
      echo($mensaje);
    exit();
  }

}
