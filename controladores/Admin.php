<?php
namespace UNLu\PAW\Controladores;
use UNLu\PAW\Libs\VIstaHTML;
use UNLu\PAW\Modelos\admindb;
use UNLu\PAW\Modelos\users;

class Admin extends \UNLu\PAW\Libs\Controlador{

	private static $initialized = false;
	private static $db;
	private static $dbUsers;

	private static function initialize(){
		if(self::$initialized)
			return true;
		self::$db = new admindb();
		self::$dbUsers = new users();
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

	public function borrarCancha(){
		self::initialize();
		sesion::startSession();
			if(sesion::is_login() && sesion::isAdmin()){
				$id_cancha = $_POST['id_cancha'];
					if(self::$db->borrarCancha($id_cancha)){
						echo 200;
					}else {
						echo 400;
					}
			}
			exit();
	}

	public function nueva_cancha(){
		self::initialize();
		sesion::startSession();
		if(sesion::is_login() && sesion::isAdmin()){
		$array_cancha = [
			'nombre_cancha' => $_POST['nombre_cancha'],
			'direccion_cancha' => $_POST['direccion_cancha'],
			'telefono_cancha' => $_POST['telefono_cancha'],
			'horario_apertura' => $_POST['horario_apertura'],
			'horario_cierre' => $_POST['horario_cierre'],
			'duracion_turno' => $_POST['duracion_turno'],
		];
			self::$db->addCancha($array_cancha);
			echo 200;
		}
		exit();
	}

	public function borrarTurno(){
		self::initialize();
		sesion::startSession();
		if(sesion::is_login() && sesion::isAdmin()){
			$id_turno = $_POST['id_turno'];
				if(self::$db->borrarTurno($id_turno)){
					echo 200;
				}else {
					echo 400;
				}
		}else {
			echo 400;
		}
		exit();
	}

	public function logout(){
		sesion::log_out();
	}
}
?>
