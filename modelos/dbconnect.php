<?php
namespace UNLu\PAW\Modelos;
use PDO;

class dbconnect{
  private $parameters;
  public $conn;

  public function __construct(){
    $rutaArchivo = __DIR__ . DIRECTORY_SEPARATOR . 'dbparameters.json';
      if(file_exists($rutaArchivo)){
        $fileContents = file_get_contents($rutaArchivo);
        $json_array = json_decode($fileContents,true);
        $this->parameters = $json_array[0];
      }else{
        echo "Archivo de Parametros NO EXISTE!";
      }
    /* parametros para la conexion */
    $dsn = 'mysql:host=' . $this->parameters['host'] . ';dbname=' . $this->parameters['dbname'];
    $nombre_usuario = $this->parameters['user'];
    $contraseña = $this->parameters['pass'];

    try {
      /* Conexion */
      $opciones = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
      );
      $this->conn = new PDO($dsn, $nombre_usuario, $contraseña,$opciones);
      //echo "Conexion Exitosa!";

////CREO Tabla TURNOS ///////////////////////////////
      $sql = "CREATE TABLE IF NOT EXISTS turnos(
    id int AUTO_INCREMENT,
	nombre varchar(25) NOT NULL,
    email varchar(25) NOT NULL,
    tel varchar(12) NOT NULL,
    edad int,
    calzado int,
    altura int,
    fechaNac date NOT NULL,
    colorPelo char(15),
    fechaTurno date NOT NULL,
    hturno time,
    diagnostico varchar(25),
    PRIMARY KEY(id)
)
ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
AUTO_INCREMENT = 1";

$this->conn->prepare($sql)->execute();
////////FIN CREATE///////////////////////////////////////
    } catch (\Exception $e) {
      /* Error Conexion */
      echo "ERROR PDO: " . $e;
      die();
    }
  }
/*fin clase */
}
?>
