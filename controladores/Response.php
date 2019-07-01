<?php
namespace UNLu\PAW\Controladores;
use UNLu\PAW\Libs\VIstaHTML;

class Response extends \UNLu\PAW\Libs\Controlador{

    public function responsePage($mensaje=null){
      echo($mensaje);
    }

}
