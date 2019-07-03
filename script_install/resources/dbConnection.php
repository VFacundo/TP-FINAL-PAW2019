<?php
	namespace Install;
	use PDO;

class dbConnection{
	public $db;
	public function __construct ($resp){
		$dbname = $resp['dbname'];
		$dbhost = $resp['dbhost'];
		$dbport = $resp['dbport'];
		$username = $resp['username'];
		$password = $resp['pass'];

		try{
			$mysql = new PDO("mysql:host=$dbhost;port=$dbport", $username, $password);
			$stmt = $mysql->prepare("CREATE DATABASE IF NOT EXISTS $dbname");
			$stmt->execute();
			$this->db = new PDO("mysql:dbname=$dbname;host=$dbhost;port=$dbport", $username, $password);

			if(isset($mysql)){
				$sql = "CREATE TABLE usuario (
									id INT(11) UNSIGNED AUTO_INCREMENT,
									mail VARCHAR(50) NOT NULL,
									user VARCHAR(30) NOT NULL,
									pass VARCHAR(255) NOT NULL,
									tipo TINYINT NOT NULL,
									token_id VARCHAR(255) DEFAULT NULL,
				    				PRIMARY KEY(id,mail)
									);
				CREATE TABLE jugador (
									id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
									nombre VARCHAR(50) NOT NULL,
									img_src VARCHAR(150),
									edad INT NOT NULL,
									tel varchar(25),
									id_usuario INT(11) UNSIGNED,
									FOREIGN KEY(id_usuario) REFERENCES usuario(id)
									);
				CREATE TABLE equipo (
									id INT(11) UNSIGNED AUTO_INCREMENT,
									nombre VARCHAR(50) NOT NULL,
									logo VARCHAR(150) NOT NULL,
									id_capitan INT(11) UNSIGNED,
									FOREIGN KEY(id_capitan) REFERENCES jugador(id),
									PRIMARY KEY(id,nombre)
									);
				CREATE TABLE jugador_equipo (
									id_jugador INT(11) UNSIGNED,
									id_equipo INT(11) UNSIGNED,
									FOREIGN KEY (id_jugador) REFERENCES jugador(id),
									FOREIGN KEY(id_equipo) REFERENCES equipo(id),
									PRIMARY KEY(id_jugador,id_equipo)
									);
				CREATE TABLE cancha (
									id INT(11) UNSIGNED AUTO_INCREMENT,
									nombre VARCHAR(30) NOT NULL,
									direccion VARCHAR(40) NOT NULL,
									telefono VARCHAR(20),
									horario_cierre TIME NOT NULL,
									horario_apertura TIME NOT NULL,
									duracion_turno INT(10) UNSIGNED NOT NULL,
									PRIMARY KEY(id,nombre)
									);
				CREATE TABLE turno (
									id INT(11) UNSIGNED AUTO_INCREMENT,
									fecha DATE NOT NULL,
									horario_turno TIME NOT NULL,
									id_solicitante INT UNSIGNED,
									id_equipo_rival INT UNSIGNED,
									id_cancha INT(11) UNSIGNED,
									tipo_turno INT(11) UNSIGNED,
									origen_turno VARCHAR(50),
									FOREIGN KEY(id_solicitante) REFERENCES usuario(id),
									FOREIGN KEY(id_equipo_rival) REFERENCES equipo(id),
									FOREIGN KEY(id_cancha) REFERENCES cancha(id),
									PRIMARY KEY(id,fecha,horario_turno)
									);
				CREATE TABLE desafio (
									id INT(11) UNSIGNED AUTO_INCREMENT,
									id_equipo INT(11) UNSIGNED,
									id_turno INT(11) UNSIGNED,
									fecha_registro DATE NOT NULL,
									horario_registro TIME NOT NULL,
									estado VARCHAR(20) NOT NULL,
									FOREIGN KEY(id_equipo) REFERENCES equipo(id),
									FOREIGN KEY(id_turno) REFERENCES turno(id),
									PRIMARY KEY(id,id_equipo,id_turno)
								);";
					$this->db->prepare($sql)->execute();
		}
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}catch (\Exception $e) {
			/* Error Conexion */
			echo "ERROR PDO: " . $e;
			die();
		}
	}
}
?>
