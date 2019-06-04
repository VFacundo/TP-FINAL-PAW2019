<?php

namespace UNLu\PAW\Libs;

/**
 * Vista HTML simple (no utiliza motor de templates)
 *
 * @author Santiago Ricci <sricci.soft at gmail.com>
 */
class VistaHTMLSimple extends Vista{
    /**
     * Directorio base de templates
     * @var string
     */
    private $baseDir;

    /**
     * Variables de la vista
     * @var array
     */
    private $viewVars;

    public function __construct() {
        parent::__construct();
        $this->contentType = "text/html";
        $this->baseDir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vistas' . DIRECTORY_SEPARATOR;
        $this->viewVars = [];
    }


    protected function doRender($accion) {
        extract($this->viewVars);
        ob_start();
        include $this->baseDir . $accion . '.php';
        return ob_get_clean();
    }

    public function setVariable($nombre, $valor) {
        $this->viewVars[$nombre] = $valor;
    }

}
