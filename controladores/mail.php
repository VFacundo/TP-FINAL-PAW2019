<?php
namespace UNLu\PAW\Controladores;

class mail{

private function __construct(){}
private static $initialized = false;
private static $from = "FulbitoWeb@gmail.com";

private static function initialize(){
  if(self::$initialized)
    return;
  self::$initialized=true;
  }

private static function sendMail($to,$subject,$message){
  ini_set( 'display_errors', 1 );
  error_reporting( E_ALL );
  $headers = "From:" . self::$from;
  mail($to,$subject,$message,$headers);
  echo "Mail Enviado!";
}

public static function sendConfirmMail($to){
  $subject = "Mensaje de Confirmacion FulbitoWeb..";
  $message = "Hi, Bienvenido a FulbitoWeb";
  self::sendMail($to,$subject,$message);
}

}
