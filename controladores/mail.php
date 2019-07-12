<?php
namespace UNLu\PAW\Controladores;
require_once 'Mail.php';

class mail{

private function __construct(){}
private static $initialized = false;
private static $from = '<hayfulbitoweb@gmail.com>';

private static function initialize(){
  if(self::$initialized)
    return;
  self::$initialized=true;
  }

private static function sendMail($to,$subject,$body){
  $headers = array(
      'From' => self::$from,
      'To' => $to,
      'Subject' => $subject
  );
  $config = self::leerConfig();
  var_dump($config);
  $smtp = \Mail::factory('smtp', $config);
  $mail = $smtp->send($to, $headers, $body);

  if (\PEAR::isError($mail)) {
      echo('<p>' . $mail->getMessage() . '</p>');
  } else {
      echo('<p>Message successfully sent!</p>');
  }
}

public static function leerConfig(){
    $rutaArchivo = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'mailparameters.json';
    $fileContents = file_get_contents($rutaArchivo);
    $json_array = json_decode($fileContents,true);
  return $json_array[0];
}

public static function sendConfirmMail($to){
  $subject = "Mensaje de Confirmacion FulbitoWeb..";
  $message = "Hi, Bienvenido a FulbitoWeb";
  $token = sha1($to);
  $link = "http://localhost/verify/validarmail/" . $token ;
  $message = $message . " token: " . $token . " link: " .$link;
  $to = '<' . $to . '>';
  self::sendMail($to,$subject,$message);
}

}
