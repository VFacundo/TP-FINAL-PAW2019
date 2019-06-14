<?php
namespace UNLu\PAW\Modelos;
use PDO;

class dbconnect{
  private $parameters;
  public $conn;

  public function __construct(){
    $rutaArchivo = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'dbparameters.json';
      if(file_exists($rutaArchivo)){
        $fileContents = file_get_contents($rutaArchivo);
        $json_array = json_decode($fileContents,true);
        $this->parameters = $json_array[0];
      }else{
        echo "Archivo de Parametros NO EXISTE!";
      }
    /* parametros para la conexion */
    $dbname = $this->parameters['dbname'];
    $dbhost = $this->parameters['dbhost'];
    $dbport = $this->parameters['dbport'];
    $username = $this->parameters['username'];
    $pass = $this->parameters['pass'];

    $dsn = 'mysql:host=' . $dbhost . ';dbname=' . $dbname . ';port=' .$dbport;
    $nombre_usuario = $username;
    $contraseña = $pass;

    try {
      /* Conexion */
      $opciones = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
      );
      $this->conn = new PDO($dsn, $nombre_usuario, $contraseña,$opciones);
      //echo "Conexion Exitosa!";

    } catch (\Exception $e) {
      /* Error Conexion */
      echo "ERROR PDO: " . $e;
      die();
    }
  }
/*fin clase */
}
?>
