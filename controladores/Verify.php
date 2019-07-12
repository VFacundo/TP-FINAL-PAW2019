<?php
namespace UNLu\PAW\Controladores;
use UNLu\PAW\Libs\VIstaHTML;
use UNLu\PAW\Modelos\users;

class Verify extends \UNLu\PAW\Libs\Controlador{

private static $initialized = false;
private static $dbUsers;

private static function initialize(){
  if(self::$initialized)
    return true;
    self::$dbUsers = new users();
  self::$initialized=true;
}

  public function verifymail($mail){
    //self::initialize();
    $this->pasarVariableAVista("mail",$mail);
  }

  public function validarmail($token){
    self::initialize();
    $mail_pendiente = self::$dbUsers->getMailPendiente();
      if($mail_pendiente){
        foreach($mail_pendiente as $value){
          if(sha1($value['mail']) === $token){
            self::$dbUsers->setMailVerificado($value['mail']);
            sesion::startSession();
            sesion::inicializarSesion($value['id']);
            $this->redireccionarA($_SERVER['REQUEST_URI'],'/perfil');
            break;
          }
        }
      }
      exit();
  }
}
