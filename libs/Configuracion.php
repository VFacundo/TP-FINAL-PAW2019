<?php
namespace UNLu\PAW\Libs;

/**
 * Administrador de configuración
 *
 * @author Santiago Ricci <sricci.soft at gmail.com>
 */
class Configuracion {
    private $configuracion;
    
    public function __construct($archivo) {
        $this->configuracion = include $archivo;
    }
    
    public function getConfiguracion($clave){
        $tokens = explode('.', $clave);
        $datos = &$this->configuracion;
        foreach ($tokens as $token){
            if(key_exists($token, $datos)){
                $datos = &$datos[$token];
            }else{
                return null;
            }
        }
        return $datos;
    }        
}
