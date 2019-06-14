<?php
namespace UNLu\PAW\Controladores;
use UNLu\PAW\Libs\VIstaHTML;

class Formulario extends \UNLu\PAW\Libs\Controlador{
    public function cargar($id){
      //carga la vista default
      $this->pasarVariableAVista('carga',$id);
    }
}
