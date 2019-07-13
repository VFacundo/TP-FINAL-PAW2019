<?php
namespace UNLu\PAW\Controladores;
require_once 'Mail.php';
require_once 'Mail/mime.php';

class mail{

private function __construct(){}
private static $initialized = false;
private static $from = '<hayfulbitoweb@gmail.com>';

private static function initialize(){
  if(self::$initialized)
    return;
  self::$initialized=true;
}

private static function sendMail($to,$subject,$html){
  $headers = array(
      'From' => self::$from,
      'To' => $to,
      'Subject' => $subject,
  );
  $config = self::leerConfig();
  $image = "img/logoBlack.png";
  $crlf = "\n";
  $mime = new \Mail_mime(array('eol' => $crlf));
  $mime->setHTMLBody($html);
  $mime->addHTMLImage(file_get_contents($image),mime_content_type($image),basename($image),false);
  $body = $mime->get();
  $headers = $mime->headers($headers);

  $smtp = \Mail::factory('smtp', $config);
  $mail = $smtp->send($to, $headers, $body);

}

public static function leerConfig(){
    $rutaArchivo = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'mailparameters.json';
    $fileContents = file_get_contents($rutaArchivo);
    $json_array = json_decode($fileContents,true);
  return $json_array[0];
}

	public static function sendConfirmMail($to){
	  $subject = "Confirmacion de email. Hay Fulbito!";
	  $token = sha1($to);
	  $link = "http://localhost/verify/validarmail/" . $token ;
	  $message = ' <!DOCTYPE html>
					<html lang="es" dir="ltr">
					  <head>
						<meta charset="utf-8">
					  </head>
					  <style>
							@import url("https://fonts.googleapis.com/css?family=Indie+Flower&display=swap");
							@import url("https://fonts.googleapis.com/css?family=Raleway&display=swap");
							
							figure{
								display: flex;
								justify-content: center;
								align-items: center;
								max-height: 200px;
								max-width: 200px;
							}
							figure img{
								height: auto;
								width: auto;
								max-width: 200px;
								max-height: 200px;
							}
							h1{font-size: 5vh;color: black;line-height: 7vh;text-shadow: 0 2px 2px rgba(0, 0, 0, 0.32);font-family: "Indie Flower", cursive;}
					  </style>
					  <body>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td style="text-align: center;">
									<h1>Hay Fulbito!</h1>
									<figure>
										img src="logoBlack.png" width="200" height="200">
									</figure>
									<h3>Verificar el email</h3>
									<h4>Gracias por registrarte en Hay Fulbito!</h4>
									<h4>Para completar el registro haz <a href="'. $link .'">click aqui</a></h4>
									<h4>Esperamos verte pronto por nuestras canchas!</h4>
								</td>
							</tr>
						</table>
					  </body>

					</html>';
	  $to = '<' . $to . '>';
	  self::sendMail($to,$subject,$message);
	}


	public static function sendConfirmDesafio($to,$fecha,$cancha,$equipo){
	  $subject = "Confirmacion de desafio. Hay Fulbito!";
	  $message = ' <!DOCTYPE html>
					<html lang="es" dir="ltr">
					  <head>
						<meta charset="utf-8">
					  </head>
					  <style>
							@import url("https://fonts.googleapis.com/css?family=Indie+Flower&display=swap");
							@import url("https://fonts.googleapis.com/css?family=Raleway&display=swap");
							
							figure{
								display: flex;
								justify-content: center;
								align-items: center;
								max-height: 200px;
								max-width: 200px;
							}
							figure img{
								height: auto;
								width: auto;
								max-width: 200px;
								max-height: 200px;
							}
							h1{font-size: 5vh;color: black;line-height: 7vh;text-shadow: 0 2px 2px rgba(0, 0, 0, 0.32);font-family: "Indie Flower", cursive;}
					  </style>
					  <body>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td style="text-align: center;">
									<h1>Hay Fulbito!</h1>
									<figure>
										<img src="logoBlack.png" width="200" height="200">
									</figure>
									<h3>Confirmacion de desafio</h3>
									<span>Felicidades! El equipo <strong>'.$equipo.'</strong> te ha aceptado el desafio.<br>
									<strong>Datos turno:</strong><br>
									<strong>Cancha y Direccion:</strong> '. $cancha .'<br>
									<strong>Dia y hora:</strong> '. $fecha .'<br></span><br>
									<span>No pierdas tiempo, ¡Avísale a tus compañeros de equipo!</span><br>
									<span>Te esperamos!</span>
								</td>
							</tr>
						</table>
						  
					  </body>

					</html>';
	  $to = '<' . $to . '>';
	  self::sendMail($to,$subject,$message);
	}
	
	public static function sendRejectDesafio($to,$fecha,$cancha,$equipo){
		$subject = "Rechazo de desafio. Hay Fulbito!";
		$message = ' <!DOCTYPE html>
					<html lang="es" dir="ltr">
					  <head>
						<meta charset="utf-8">
					  </head>
					  <style>
							@import url("https://fonts.googleapis.com/css?family=Indie+Flower&display=swap");
							@import url("https://fonts.googleapis.com/css?family=Raleway&display=swap");
							
							figure{
								display: flex;
								justify-content: center;
								align-items: center;
								max-height: 200px;
								max-width: 200px;
							}
							figure img{
								height: auto;
								width: auto;
								max-width: 200px;
								max-height: 200px;
							}
							h1{font-size: 5vh;color: black;line-height: 7vh;text-shadow: 0 2px 2px rgba(0, 0, 0, 0.32);font-family: "Indie Flower", cursive;}
					  </style>
					  <body>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td style="text-align: center;">
								  <h1>Hay Fulbito!</h1>
								  <figure>
									<img src="logoBlack.png" width="200" height="200">
								  </figure>
								  <h3>Rechazo de desafio</h3>
								   <span>El equipo <strong>'.$equipo.'</strong> te ha rechazado el desafio.<br>
									<strong>Datos turno:</strong><br>
									<strong>Cancha y Direccion:</strong> '. $cancha .'<br>
									<strong>Dia y hora:</strong> '. $fecha .'<br></span>
								  <span>No te desanimes... Sigue retando equipos!</span><br>
								  <span>Te esperamos pronto</span>
								</td>
							</tr>
						</table>
					  </body>

					</html>';
		$to = '<' . $to . '>';
		self::sendMail($to,$subject,$message);
	}
	
	public static function sendRequestDesafio($to,$fecha,$cancha, $equipo){
		$subject = "Te han desafiado. Hay Fulbito!";
		$message = ' <!DOCTYPE html>
					<html lang="es" dir="ltr">
					  <head>
						<meta charset="utf-8">
					  </head>
					  <style>
							@import url("https://fonts.googleapis.com/css?family=Indie+Flower&display=swap");
							@import url("https://fonts.googleapis.com/css?family=Raleway&display=swap");
							
							section{
								width: 100%;
								display: flex;
								flex-direction: column;
								align-items: center;
								font-family: "Raleway", sans-serif;
							}

							figure{
								display: flex;
								justify-content: center;
								align-items: center;
								max-height: 200px;
								max-width: 200px;
							}
							figure img{
								height: auto;
								width: auto;
								max-width: 200px;
								max-height: 200px;
							}
							h1{font-size: 5vh;color: black;line-height: 7vh;text-shadow: 0 2px 2px rgba(0, 0, 0, 0.32);font-family: "Indie Flower", cursive;}
					  </style>
					  <body>
						<section >
						  <h1>Hay Fulbito!</h1>
						  <figure>
							<img src="logoBlack.png" width="200" height="200">
						  </figure>
						  <h3>Solicitud de desafio</h3>
						  <span>El equipo <strong>'.$equipo.'</strong> te ha desafiado.<br>
							<strong>Datos turno:</strong><br>
							<strong>Cancha y Direccion:</strong> '. $cancha .'<br>
							<strong>Dia y hora:</strong> '. $fecha .'<br></span>
						  <span><a href="localhost/login"> Ingresa al sistema</a> y responde la solicitud<br></span>
						  <span>Te esperamos!</span>
						</section>
					  </body>

					</html>';
		$to = '<' . $to . '>';
		self::sendMail($to,$subject,$message);
	}
}