<?php
namespace UNLu\PAW\Libs;
use UNLu\PAW\Libs\Router;
/**
 * Atiende una petición
 *
 * @author Santiago Ricci <sricci.soft at gmail.com>
 */
class Despachador {

    private $router;

    public function __construct(Router $router) {
        $this->router = $router;
    }

    public function desapchar($serverArr){
        $ruta = isset($serverArr['PATH_INFO']) ? $serverArr['PATH_INFO'] : '';
        $peticion = $this->router->route($ruta);
        /* @var $controlador Controlador */
        if(empty($peticion)){
            die('Error 404');
        }
        $controlador = $this->getInstanciaControlador($peticion['controlador']);
        if(key_exists('accion',$peticion) && method_exists($controlador, $peticion['accion'])){
            if(!key_exists('parametros', $peticion)){
                $peticion['parametros'] = [];
            }
            //call_user_method_array($peticion['accion'], $controlador, $peticion['parametros']);
            $controlador->ejecutarAccion($peticion['accion'], $peticion['parametros']);
        }else{
            die('Error 404');
        }
    }

    private function getInstanciaControlador($controlador){
        $className = "UNLu\\PAW\\Controladores\\" . ucfirst($controlador);
        if(class_exists($className)){
            return new $className();
        }else{
            //die("Controlador no existente: $className");
            die('Error 404');
        }
    }
}
