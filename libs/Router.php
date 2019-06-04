<?php

namespace UNLu\PAW\Libs;

/**
 * Router básico
 *
 * @author Santiago Ricci <sricci.soft at gmail.com>
 */
class Router {
    // articulos/ver/5
    const FORMATO_URL = '/([a-z,-]+)(?:\/([a-z,-]+)(?:\/(.+\/?)*)?)?/';

    private $rutaPorDefecto;

    public function __construct() {
        $this->rutaPorDefecto = [];
    }


    public function route($url){
        $components = [];
        $map = [
            'controlador',
            'accion',
            'parametros'
        ];
        preg_match(self::FORMATO_URL, $url, $components);
        $result = [];
        foreach ($components as $key => $value) {
            if($key !== 0){
                $result[$map[$key - 1]] = $key === 3 ? explode('/', $value) : $value;
            }
        }
        if($this->hasRutaPorDefecto() && count($result) === 0){
            $result = array_merge($result, $this->getRutaPorDefecto());
        }
        return $result;
    }

    public function setRutaPorDefecto($ruta){
        $this->rutaPorDefecto = $ruta;
    }

    public function getRutaPorDefecto() {
        return $this->rutaPorDefecto;
    }

    public function hasRutaPorDefecto(){
        return is_array($this->rutaPorDefecto) &&
                key_exists('controlador', $this->rutaPorDefecto) &&
                key_exists('accion', $this->rutaPorDefecto);
    }
}
