<?php
namespace UNLu\PAW\Controladores;
use UNLu\PAW\Libs\VIstaHTML;

class DefaultPage extends \UNLu\PAW\Libs\Controlador{
    public function Default($mensaje=null){
      //carga la vista default
      $this->pasarVariableAVista('action','Login/loginUser');
      $this->pasarVariableAVista('mensaje',$mensaje);
    }

}
