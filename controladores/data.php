<?php
namespace UNLu\PAW\Controladores;

class data{

private function __construct(){}
private static $initialized = false;

private static function initialize(){
  if(self::$initialized)
    return;
  self::$initialized=true;
  }

public static function verify_username($user){
  $valido = FALSE;
  if((strlen($user)>2)&&(preg_match("/^[a-zA-Z 0-9-_]*$/",$user)===1)){
    $valido = TRUE;
  }
  return $valido;
}

public static function verify_mail($mail){
  $valido = FALSE;
  if((preg_match("/^[a-zA-Z 0-9-_@.]*$/",$mail)===1)){
    $valido = TRUE;
  }
  return $valido;
}

public static function verify_name($name){
  $valido = FALSE;
  if((strlen($name)>2)&&(preg_match("/^[a-zA-Z ]*$/",$name)===1)){
    $valido = TRUE;
  }
  return $valido;
}

public static function verify_age($edad){
  $valido = FALSE;
  if(($edad>9)&&($edad<100)){
    if(preg_match("/^[0-9]*$/",$edad)){
      $valido = TRUE;
    }
  }
  return $valido;
}

public static function verify_phone($tel){
  $valido = FALSE;
  if(empty($tel))
    $valido = TRUE;
  if(strlen($tel)>9){
    if(preg_match("/^[0-9]*$/",$tel)){
      $valido = TRUE;
    }
  }
  return $valido;
}

public static function verify_pass($pass){
  $valido = FALSE;
  if((strlen($pass)>6)&&(preg_match("/^[a-zA-Z0-9]*$/",$pass)===1)){
    $valido = TRUE;
  }
  return $valido;
}

}
