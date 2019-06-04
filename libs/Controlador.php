<?php
namespace UNLu\PAW\Libs;

/**
 * Clase base para controladores
 *
 * @author Santiago Ricci <sricci.soft at gmail.com>
 */
abstract class Controlador {
    /**
     * Nombre de la clase a emplear como vista
     * @var string
     */
    private $viewClass;

    /**
     * Variables para el renderizado de la vista
     * @var array
     */
    private $variablesVista;

    public function __construct() {
        $this->variablesVista = [];
        $this->viewClass = 'UNLu\\PAW\\Libs\\VistaHTMLSimple';
    }

    public function redireccionar($localizacion){
        header("Location: $localizacion");
        exit();
    }

    public function ejecutarAccion($nombre, $parametros = []){
        //call_user_method_array($nombre, $this, $parametros);
        call_user_func_array([$this,$nombre], $parametros);
        $claseVista = $this->getViewClass();
        /* @var $vista Vista */
        $vista = new $claseVista();
        foreach ($this->variablesVista as $nombreVar => $valor){
            $vista->setVariable($nombreVar, $valor);
        }
        $fqcn = explode('\\', get_class($this));
        $controllerName = array_pop($fqcn);
        $vista->dibujar($controllerName. DIRECTORY_SEPARATOR . $nombre);
    }

    public function pasarVariableAVista($nombre,$valor){
        $this->variablesVista[$nombre] = $valor;
    }


    public function setViewClass($viewClass) {
        $this->viewClass = $viewClass;
    }

    public function getViewClass() {
        return $this->viewClass;
    }

}
