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
  var_dump($config);
  $crlf = "\n";
  $mime = new \Mail_mime(array('eol' => $crlf));
  $mime->setHTMLBody($html);

  $body = $mime->get();
  $headers = $mime->headers($headers);

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
	  $subject = "Confirmacion de email. HayFulbito1";
	  $token = sha1($to);
	  $link = "http://localhost/verify/validarmail/" . $token ;
	  $message = 	'<!DOCTYPE html>
					<html lang="es" dir="ltr">
					  <head>
						<meta charset="utf-8">
						<title>Verificacion por mail - Hay Fulbito!</title>
						<meta name="viewport" content="width=device-width, initial-scale=1.0">
					  </head>
					  <body>
						<section class="login verificacion">
						  <h1>Hay Fulbito!</h1>
						  <figure>
							<?xml version="1.0" standalone="no"?>
								<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 20010904//EN"
								 "http://www.w3.org/TR/2001/REC-SVG-20010904/DTD/svg10.dtd">
								<svg version="1.0" xmlns="http://www.w3.org/2000/svg"
								 width="499.000000pt" height="487.000000pt" viewBox="0 0 499.000000 487.000000"
								 preserveAspectRatio="xMidYMid meet">
								<g transform="translate(0.000000,487.000000) scale(0.100000,-0.100000)"
								fill="#000000" stroke="none">
								<path d="M3270 4709 c-162 -42 -615 -201 -633 -222 -16 -19 -18 -32 -12 -94 6
								-64 4 -74 -14 -92 -45 -45 -87 -21 -97 56 -3 32 -11 66 -16 76 -12 23 -59 22
								-92 -4 -24 -19 -26 -26 -26 -103 0 -91 -8 -106 -55 -106 -45 0 -55 16 -55 86
								0 52 -4 65 -21 77 -25 18 -51 12 -77 -16 -16 -17 -21 -40 -24 -100 -4 -84 -17
								-107 -63 -107 -36 0 -45 20 -45 95 0 78 -9 95 -51 95 -50 0 -69 -27 -80 -115
								-11 -88 -27 -115 -69 -115 -45 0 -53 20 -45 109 6 74 5 80 -14 90 -25 14 -64
								5 -85 -18 -8 -9 -22 -50 -31 -91 -9 -41 -23 -82 -32 -92 -21 -23 -55 -23 -77
								1 -16 18 -16 24 0 110 10 50 14 94 10 98 -4 5 -40 8 -79 8 -94 0 -147 -30
								-220 -123 -81 -104 -225 -243 -302 -293 -153 -98 -273 -133 -436 -127 -93 4
								-113 8 -175 37 -87 41 -126 42 -165 5 -43 -41 -99 -227 -99 -331 0 -73 12 -78
								165 -70 331 18 686 114 1335 364 168 64 261 93 403 125 140 31 466 33 595 4
								84 -19 99 -29 60 -42 -13 -4 -36 -17 -53 -29 -16 -13 -93 -57 -169 -99 -140
								-76 -140 -76 -172 -61 -18 8 -70 20 -116 26 -196 25 -442 -22 -928 -180 -432
								-139 -650 -186 -933 -198 -150 -6 -183 -15 -172 -45 13 -32 64 -43 201 -41
								268 3 604 70 1009 200 156 50 276 78 378 88 78 8 297 -3 297 -15 0 -5 -53 -36
								-117 -71 -65 -34 -160 -84 -211 -111 -89 -48 -95 -49 -125 -37 -44 19 -229 16
								-347 -5 -52 -9 -169 -32 -259 -52 -253 -54 -354 -66 -574 -67 -196 -1 -197 -1
								-197 -23 0 -12 26 -57 58 -100 98 -132 270 -280 338 -291 30 -5 35 -9 30 -27
								-7 -26 37 -102 63 -109 11 -2 41 6 68 19 59 29 85 30 108 4 22 -24 43 -25 73
								-4 20 14 23 23 20 62 l-3 46 55 34 c75 45 96 49 124 23 27 -25 44 -26 75 -5
								19 14 22 23 18 55 -4 27 0 42 12 52 22 20 333 200 511 295 36 19 74 41 85 49
								50 34 31 5 -56 -84 -122 -126 -166 -180 -234 -289 -314 -499 -343 -1118 -79
								-1647 166 -330 443 -600 777 -757 394 -185 829 -213 1240 -79 129 43 336 147
								441 223 549 396 805 1040 676 1701 -121 619 -613 1137 -1225 1288 -256 64
								-497 69 -759 16 -54 -11 -101 -17 -104 -15 -10 11 31 45 93 78 l64 33 23 -21
								c36 -33 46 -36 70 -23 34 18 39 30 33 75 -4 31 -2 44 12 56 32 29 367 248 378
								248 7 0 20 -9 30 -20 26 -28 71 -26 91 5 13 20 14 29 3 59 -13 35 -12 37 22
								72 45 47 138 119 166 128 16 5 28 -1 52 -26 26 -28 33 -31 57 -23 41 15 51 38
								35 77 -26 63 -25 86 3 122 26 34 35 85 16 87 -5 1 -16 2 -22 3 -8 1 -12 13
								-10 34 4 39 -32 92 -82 123 -41 25 -136 24 -236 -2z m-790 -1238 c73 -30 260
								-138 260 -150 0 -3 -43 -135 -96 -293 l-95 -286 -57 -23 c-93 -36 -320 -150
								-423 -212 -53 -31 -100 -57 -105 -57 -5 1 -90 49 -189 108 -149 89 -181 112
								-183 132 -3 24 73 222 122 318 23 45 139 167 221 233 58 46 191 132 255 164
								42 22 207 94 216 95 1 0 35 -13 74 -29z m1216 -92 c253 -140 475 -368 623
								-640 49 -91 52 -109 14 -83 -67 44 -184 106 -253 135 -41 17 -86 42 -99 57
								-13 15 -45 50 -70 78 -165 182 -406 458 -405 465 1 5 2 25 3 44 l1 34 48 -20
								c26 -11 88 -43 138 -70z m-94 -246 c100 -113 188 -211 193 -217 65 -70 125
								-146 125 -159 0 -34 -143 -412 -164 -434 -6 -6 -160 -26 -371 -47 l-360 -37
								-40 43 c-52 56 -337 377 -349 393 -6 8 26 117 90 309 54 163 101 302 105 307
								12 19 268 45 466 48 l121 1 184 -207z m-1930 -622 c73 -43 151 -89 173 -101
								74 -43 75 -45 75 -134 0 -110 16 -277 39 -418 l20 -118 -147 -230 c-81 -126
								-152 -230 -158 -230 -15 0 -140 90 -196 142 -38 35 -51 57 -67 108 -48 157
								-71 311 -71 477 0 136 24 310 50 371 26 57 127 212 139 212 6 0 70 -35 143
								-79z m2178 -417 c94 -145 210 -353 210 -377 0 -14 -342 -661 -359 -679 -17
								-17 -316 -54 -503 -61 -80 -4 -100 -1 -111 12 -22 25 -357 608 -357 621 0 7
								33 73 74 148 40 76 103 193 139 262 l66 125 283 29 c156 15 308 32 338 36 30
								4 74 8 96 9 l42 1 82 -126z m646 -231 c-2 -10 -7 -45 -11 -78 -43 -374 -264
								-768 -569 -1013 -70 -56 -85 -58 -86 -7 0 11 -11 66 -25 121 l-24 101 188 352
								188 351 98 51 c55 28 126 70 160 94 65 47 89 55 81 28z m-2301 -237 c92 -21
								310 -62 431 -81 29 -5 43 -25 200 -298 92 -160 172 -299 177 -308 8 -12 -12
								-45 -83 -143 -50 -70 -102 -138 -113 -151 l-22 -25 -378 68 -377 67 -46 58
								c-63 81 -153 218 -203 309 l-42 77 73 113 c41 62 107 166 148 231 68 107 77
								118 100 113 14 -3 75 -16 135 -30z m266 -1041 c259 -47 319 -61 381 -90 40
								-18 85 -36 100 -40 15 -4 33 -14 40 -23 12 -14 0 -15 -118 -9 -160 8 -277 28
								-409 68 -104 32 -315 126 -315 141 0 11 -21 14 321 -47z"/>
								</g>
								</svg>
						  </figure>
						  <h3>Verificar el email</h3>
						  <h4>Gracias por registrarte en Hay Fulbito!</h4>
						  <h4>Para completar el registro haz <a href="'. $link .'">click aqui</a></h4>
						  <h4>¡Esperamos verte pronto por nuestras canchas!</h4>
						</section>
					  </body>
					  <style>
							@import url("https://fonts.googleapis.com/css?family=Indie+Flower&display=swap");
							@import url("https://fonts.googleapis.com/css?family=Raleway&display=swap");
						 .login{
							width: 50vw;
							background: #ffffffa8;
							display: flex;
							flex-direction: column;
							align-items: center;
							margin: 2em 0em 0em 0em;
							font-family: "Raleway", sans-serif;
							height: 90vh;
							}

							.login figure{
								display: flex;
								justify-content: center;
								align-items: center;
								max-height: 15vh;
								max-width: 15vw;
							}
							.login figure img{
								height: auto;
								width: auto;
								max-width: 15vw;
								max-height: 15vh;
							}
							.login h1{font-size: 5vh;color: black;line-height: 7vh;text-shadow: 0 2px 2px rgba(0, 0, 0, 0.32);font-family: "Indie Flower", cursive;}
					  </style>

					</html>';
	  $to = '<' . $to . '>';
	  self::sendMail($to,$subject,$message);
	}

}
