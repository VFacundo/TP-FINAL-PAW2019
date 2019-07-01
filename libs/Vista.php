<?php

namespace UNLu\PAW\Libs;

/**
 * Clase base para las vistas
 *
 * @author Santiago Ricci <sricci.soft at gmail.com>
 */
abstract class Vista {
    /**
     * Tipo MIME retornado en el header Content-Type
     * @var string
     */
    protected $contentType;
    
    public function __construct() {
    }

    /**
     * Proporciona una variable a la vista
     * @param string $nombre Nombre de la variable
     * @param mixed $valor Valor de la variable
     */
    public abstract function setVariable($nombre, $valor);
    
    /**
     * Renderiza la vista
     */
    public function dibujar($accion){
        header("Content-Type: {$this->getContentType()}");
        echo $this->doRender($accion);
    }
    
    /**
     * Método a sobrescribir por las clase hijas en el cual se debe implementar
     * la lógica de dibujado de la vista
     * @param string $accion Nombre de la acción a renderizar
     */
    protected abstract function doRender($accion);
    
    /**
     * Recupera el tipo MIME de la vista
     * @return string
     */
    public function getContentType() {
        return $this->contentType;
    }

}
