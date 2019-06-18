<?php
namespace UNLu\PAW\Modelos;
use UNLu\PAW\Modelos\dbconnect;
use PDO;
require_once 'vendor/autoload.php';

class users{
  private $db;
    public function __construct(){
      $this->db = new dbconnect();
    }

    public function datosUserJug($id){
      $sql = "SELECT nombre,edad,user,mail,tel FROM usuario u INNER JOIN jugador j on (u.id = j.id_usuario) WHERE u.id = '$id'";
      $result = $this->db->conn->query($sql);
      if(!$result===FALSE){
        $result = $result->fetch();
      }
      return $result;
    }

    public function newGoogleUser($token,$mail){
      $sql = "INSERT INTO usuario(id,mail,pass,tipo,token_id) VALUES (?,?,?,?,?)";
      $resultado = $this->db->conn->prepare($sql)->execute([NULL,$mail,$token,0,$token]);
    }

    public function newUser($pass, $mail,$nombre,$username,$edad,$tel){
      if(empty($this->buscarUser($mail))){//0 usuario comun, 1 admin
        if(empty($username)){
          $sql = "SELECT count(id) from usuario";
            $result = $this->db->conn->query($sql);
            $result = $result->fetch();
            $username = 'Guest' . $result[0];
        }
        $pass = password_hash($pass, PASSWORD_DEFAULT); // -> https://www.php.net/manual/es/function.password-hash.php
        $sql = "INSERT INTO usuario(id,mail,user,pass,tipo) VALUES (?,?,?,?,?)";
        $resultado = $this->db->conn->prepare($sql)->execute([NULL,$mail,$username,$pass,0]);
        if(!$resultado===FALSE){
          $sql = "SELECT id from usuario WHERE mail='$mail'";
          $result = $this->db->conn->query($sql);
          $result = $result->fetch();
          $id_usuario = $result[0];

          $sql = "INSERT INTO jugador(id,nombre,img_src,edad,tel,id_usuario) VALUES (?,?,?,?,?,?)";
          $resultado = $this->db->conn->prepare($sql)->execute([NULL,$nombre,'default',$edad,$tel,$id_usuario]);
        }

      }
    }

    public function loginUser($ident,$pass){
      $sql= "SELECT pass FROM usuario WHERE mail='$ident'";
        if(strpos($ident,'@')===FALSE){//si tiene @ es un mail
            $sql= "SELECT pass FROM usuario WHERE user='$ident'";
        }
      $result = $this->db->conn->query($sql);
      $login = FALSE;
      if(!$result===FALSE){
        $result = $result->fetch();
          if(!$result===FALSE){
              $hash = $result[0];
              if(password_verify($pass,$hash)){
                  $login = TRUE;
              }
          }
      }
      return $login;
  }

  public function buscarUser($ident){//Busco el mail en la bd
    $sql= "SELECT id,mail FROM usuario WHERE mail='$ident'";
      if(strpos($ident,'@')===FALSE){//si tiene @ es un mail
          $sql= "SELECT id,user FROM usuario WHERE user='$ident'";
      }
    $result = $this->db->conn->query($sql);
    $existe = NULL;
    if(!$result===FALSE){
      $result = $result->fetch();
        if(!$result===FALSE){
            $existe = $result['id'];
        }
    }
    return $existe;
  }
}
?>
