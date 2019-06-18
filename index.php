 <?php
require_once 'libs/autoloader.php';

use UNLu\PAW\Libs\Configuracion;
use UNLu\PAW\Libs\Despachador;
use UNLu\PAW\Libs\Router;

$router = new Router();
$solicitud = substr($_SERVER['REQUEST_URI'],1);//Obtengo la url q ing el user
/////////////////////////////////
$dir = __DIR__;
$dir = explode("/",$dir);
  if(strpos($solicitud,$dir[sizeof($dir)-1])!==FALSE){
    $solicitud = strstr($solicitud,'/');
  }
////////////////////////////////
$solicitud = strtolower($solicitud);

$despachador = new Despachador($router);
$configuracion = new Configuracion(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'app.php');
$rutaPorDefecto = $configuracion->getConfiguracion('Router.accionPorDefecto');

if(strlen($solicitud)>1){
  $rutaPorDefecto = $router->route($solicitud);
  //$rutaPorDefecto = $configuracion->getConfiguracion('');
    if(sizeof($rutaPorDefecto)==1){//Si la ruta x default == 1 significa que solo ing el controlador, return el default del controlador
      //Los default d los controladores deben registrar en app.php
      $solicitud = str_replace("/","",$solicitud);
      $rutaPorDefecto = $configuracion->getConfiguracion($solicitud .= '.accionPorDefecto');
  }
}

if(!is_null($rutaPorDefecto)){
  $router->setRutaPorDefecto($rutaPorDefecto);
}

$despachador->desapchar($_SERVER);
