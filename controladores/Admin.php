<?php
namespace UNLu\PAW\Controladores;
use UNLu\PAW\Libs\VIstaHTML;
use UNLu\PAW\Modelos\admindb;

class Admin extends \UNLu\PAW\Libs\Controlador{

	private static $initialized = false;
	private static $db;

	private static function initialize(){
		if(self::$initialized)
			return true;
		self::$db = new admindb();
		self::$initialized=true;
	}

	public function turnosreservados(){
		self::initialize();
		sesion::startSession();
		if(sesion::isAdmin()){
			if(sesion::is_login()){
				$this->pasarVariableAVista("turnos_reservados",self::$db->turnos_reservados());
				sesion::refreshTime();
			}else{
				$this->redireccionarA($_SERVER['REQUEST_URI'],'/login');
			}
		}else{
			$this->redireccionarA($_SERVER['REQUEST_URI'],'/login/logout');
		}
	}

	public function canchas(){
		self::initialize();
		sesion::startSession();
		if(sesion::isAdmin()){
			if(sesion::is_login()){
				sesion::refreshTime();
				$this->pasarVariableaVista('canchas',self::$db->buscarCanchas());
			}else{
				$this->redireccionarA($_SERVER['REQUEST_URI'],'/login');
			}
		}else{
			$this->redireccionarA($_SERVER['REQUEST_URI'],'/login/logout');
		}
	}

	public function logout(){
		sesion::log_out();
	}
}
?>
