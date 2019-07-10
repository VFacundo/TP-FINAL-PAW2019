<?php
namespace UNLu\PAW\Controladores;
use UNLu\PAW\Libs\VIstaHTML;
use UNLu\PAW\Modelos\users;

class Admin extends \UNLu\PAW\Libs\Controlador{

	private static $initialized = false;
	private static $db;

	private static function initialize(){
		if(self::$initialized)
			return true;
		self::$db = new users();
		self::$initialized=true;
	}

	public function turnosreservados(){
		self::initialize();
		sesion::startSession();
		if(sesion::is_login()){
			//$this->redireccionarA($_SERVER['REQUEST_URI'],'/turnosadmin');
			sesion::refreshTime();
		}else{
			$this->redireccionarA($_SERVER['REQUEST_URI'],'/login');
		}
	}

	public function canchas(){
		self::initialize();
		sesion::startSession();
		if(sesion::is_login()){
			//$this->redireccionarA($_SERVER['REQUEST_URI'],'/canchas');
			sesion::refreshTime();
		}else{
			$this->redireccionarA($_SERVER['REQUEST_URI'],'/login');
		}
	}

	public function logout(){
		sesion::log_out();
	}
}
?>