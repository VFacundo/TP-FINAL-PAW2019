<?php
namespace UNLu\PAW\Modelos;
use UNLu\PAW\Modelos\dbconnect;
use UNLu\PAW\Controladores\sesion;
use PDO;
require_once 'vendor/autoload.php';

class users{
  private $db;
    public function __construct(){
      $this->db = new dbconnect();
    }

    public function setMailVerificado($mail){//Save Mail verif
      $sql = "UPDATE usuario SET tipo='1' WHERE mail='$mail'";
      $resultado = $this->db->conn->prepare($sql)->execute();
    }


    public function getMailPendiente(){
      $sql = "SELECT mail,id FROM usuario WHERE tipo=0";
      $result = $this->db->conn->query($sql);
      if(!$result===FALSE){
        $result = $result->fetchAll();
      }
      return $result;
    }

    public function setImg($id,$img_src){
      $sql = "UPDATE jugador SET img_src='$img_src' WHERE id_usuario='$id'";
      $resultado = $this->db->conn->prepare($sql)->execute();
    }

    public function getImg($id){
      $sql = "SELECT img_src FROM jugador WHERE id_usuario='$id'";
      $result = $this->db->conn->query($sql);
      if(!$result===FALSE){
        $result = $result->fetch();
      }
      return $result;
    }

  public function imgUser($datosImg,$id){
      $img = new imagen();
      $archivoImagen = $img->subirImagen($datosImg,TRUE);
      $this->setImg($id,$archivoImagen);
  }

    public function editUser($nombre,$username=null,$edad,$tel,$id){
      $sql = "UPDATE jugador SET nombre='$nombre',edad='$edad',tel='$tel' WHERE id_usuario='$id'";
      $resultado = $this->db->conn->prepare($sql)->execute();
      if(!empty($username)){
        $sql = "UPDATE usuario SET user='$username' WHERE id='$id'";
        $resultado = $this->db->conn->prepare($sql)->execute();
      }
    }

    public function datosUserJug($id){
      $sql = "SELECT nombre,edad,user,mail,tel,img_src FROM usuario u INNER JOIN jugador j on (u.id = j.id_usuario) WHERE u.id = '$id'";
      $result = $this->db->conn->query($sql);
      if(!$result===FALSE){
        $result = $result->fetch();
      }
      return $result;
    }

    public function googleToken($mail,$token){//Inset token en bd
      $sql = "UPDATE usuario SET token_id='$token' WHERE mail='$mail'";
      $resultado = $this->db->conn->prepare($sql)->execute();
    }

    public function newGoogleUser($token){//Verifico que el token es valido
      $CLIENT_ID = '858752674560-6emgo7emadgcv30k8k9qfa8mrnsotjph.apps.googleusercontent.com';
      $client = new \Google_Client(['client_id' => $CLIENT_ID]);
      $payload = $client->verifyIdToken($token);
      if ($payload) {
        $userid = $payload['sub'];
      } else {
        $userid = FALSE;
      }
      return $userid;
    }

    public function newUser($pass, $mail,$nombre,$username,$edad,$tel){
      if(empty($this->buscarUser($mail))){//0 pendiente, 1 usuario comun, 2 admin
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
          $img = 'avatar' . rand(1,3) . ".svg";
          $resultado = $this->db->conn->prepare($sql)->execute([NULL,$nombre,$img,$edad,$tel,$id_usuario]);
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
    $sql= "SELECT id,mail,tipo FROM usuario WHERE mail='$ident'";
      if(strpos($ident,'@')===FALSE){//si tiene @ es un mail
          $sql= "SELECT id,user,tipo FROM usuario WHERE user='$ident'";
      }
    $result = $this->db->conn->query($sql);
    $existe = NULL;
    if(!$result===FALSE){
      $result = $result->fetch();
        if(!$result===FALSE){
            $existe = $result['id'];
              if($result['tipo']==0){
                return FALSE;
              }
              if($result['tipo']==2){
                sesion::setAdmin();
              }
        }
    }
    return $existe;
  }

}
?>
