<?php
namespace UNLu\PAW\Controladores;
use UNLu\PAW\Libs\VIstaHTML;
use UNLu\PAW\Modelos\users;

class Perfil extends \UNLu\PAW\Libs\Controlador{

public function perfil(){
  session_start();
  $mensaje = $_SESSION['mensaje'];
  $this->pasarVariableAVista('mensaje',$mensaje);
}

}
?>
