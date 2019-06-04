 <?php
require_once 'libs/autoloader.php';

use UNLu\PAW\Libs\Configuracion;
use UNLu\PAW\Libs\Despachador;
use UNLu\PAW\Libs\Router;

$router = new Router();

$solicitud = substr($_SERVER['REQUEST_URI'],1);//Obtengo la url q ing el user
$solicitud = strstr($solicitud,'/');
$solicitud = strtolower($solicitud);

$despachador = new Despachador($router);
$configuracion = new Configuracion(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'app.php');
$rutaPorDefecto = $configuracion->getConfiguracion('Router.accionPorDefecto');

/*
if(!(is_null($rutaPorDefecto)) && (strlen($solicitud))<=1){
    $router->setRutaPorDefecto($rutaPorDefecto);
}else{
  $rutaPorDefecto = $router->route($solicitud);
  $router->setRutaPorDefecto($rutaPorDefecto);
}
*/

if(strlen($solicitud)>1){
  $rutaPorDefecto = $router->route($solicitud);
  //var_dump($rutaPorDefecto);
}

if(!is_null($rutaPorDefecto)){
  $router->setRutaPorDefecto($rutaPorDefecto);
}

$despachador->desapchar($_SERVER);
