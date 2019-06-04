<?php
namespace UNLu\PAW\Controladores;
use UNLu\PAW\Libs\VIstaHTML;

class DefaultPage extends \UNLu\PAW\Libs\Controlador{
    public function Default(){
      //carga la vista default
      $carga = $_SERVER['REQUEST_URI'].'Formulario/cargar';
      $this->pasarVariableAVista('carga',$carga);

      $admin = $_SERVER['REQUEST_URI'].'Administracion/listar';
      $this->pasarVariableAVista('admin',$admin);
    }
}
