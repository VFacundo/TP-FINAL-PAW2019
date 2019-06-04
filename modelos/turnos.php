<?php
namespace UNLu\PAW\Modelos;
use UNLu\PAW\Modelos\dbconnect;
use PDO;

class turnos{
  private $db;
  private $directorioImagenes;
    public function __construct(){
      $this->db = new dbconnect();
      $this->directorioImagenes = "img/";
      //$res = $this->db->conn->query("DESCRIBE turnos");
      //var_dump($res->fetch());
    }

    public function updateTurno($arrayTurno){
      $id = $arrayTurno['id'];
      $arrayTurno = $this->verificar($arrayTurno);
      $erroresFormulario = $arrayTurno['erroresFormulario'];
      $imgTurno = $arrayTurno['diagnostico'];
      unset($arrayTurno['erroresFormulario']);
      if(empty($erroresFormulario)){
        $arrayTurno['diagnostico'] = $this->cargarImagen($imgTurno);
        //var_dump($arrayTurno);
        $consulta = "UPDATE turnos SET nombre=:firstname,email=:email,tel=:tel,edad=:edad,
        calzado=:calzado,altura=:altura,fechaNac=:fechaNac,colorPelo=:colorPelo,fechaTurno=:fechaTurno,
        hTurno=:hturno,diagnostico=:diagnostico WHERE id='$id'";
        $resultado = $this->db->conn->prepare($consulta)->execute($arrayTurno);
      }
      return $arrayTurno;
    }

    public function selectAdmin(){
      $sql = "SELECT id,fechaTurno,hturno,nombre,tel,email FROM turnos";
      $result = $this->db->conn->query($sql);
      $arrayResultado = NULL;
        if(!$result===FALSE){
          for ($i=0; $registro = $result->fetch() ; $i++) {
              $arrayResultado[$i] = $registro;
          }
        }
      return $arrayResultado;
    }

    public function eliminarTurno($id){
      $arrayResultado = $this->selectTurno($id);
      $sql = "DELETE FROM turnos WHERE id='$id'";
      $result = $this->db->conn->exec($sql);
      if(!empty($result)){
              if(!empty($arrayResultado['diagnostico'])){
                  $archivoImagen = $arrayResultado['diagnostico'];
                  unlink($archivoImagen);
              }
      }
      return $arrayResultado;
    }

    public function selectTurno($id){
      $sql = "SELECT * FROM turnos WHERE id='$id'";
      $result = $this->db->conn->query($sql);
      $arrayResultado = NULL;
        if(!$result===FALSE){
              $arrayResultado = $result->fetch();
                if(!empty($arrayResultado['diagnostico'])){
                    $archivoImagen = $arrayResultado['diagnostico'];
                    $arrayResultado['diagnostico'] = $this->directorioImagenes . $archivoImagen;
                }
        }
      return $arrayResultado;
    }

    public function cargarImagen($datosImg){//Recibe nombre img y exten.
      $archivoImagen = "";
      if(is_uploaded_file($datosImg['imgTurno']['tmp_name'])){
        $tipoImagen = $datosImg['imgTurno']['type'];
            if(($tipoImagen == "image/jpeg") || ($tipoImagen == "image/png")){
              $infoImg = getimagesize($datosImg['imgTurno']['tmp_name']);
                if(($datosImg['imgTurno']['size'])<10000000){
                    $sql = "SELECT MAX(id) AS id FROM turnos";
                    $result = $this->db->conn->query($sql);
                    $result = $result->fetch(PDO::FETCH_ASSOC);
                    $id = $result['id'];
                    $id++;
                    $archivoImagen = $id . basename($_FILES["imgTurno"]["name"]);
                    move_uploaded_file($datosImg["imgTurno"]["tmp_name"],$this->directorioImagenes . $archivoImagen);
                }
            }
        }
        return $archivoImagen;
      }/*Fin Cargar Imagen */

    public function verificar($arrayTurno){
      global $firstname,$email,$tel,$edad,$calzado,$altura,$fechaNac,$colorPelo,$fechaTurno,$hturno,$erroresFormulario;
      $firstname = $arrayTurno['firstname'];
      $email = $arrayTurno['email'];
      $tel = $arrayTurno['tel'];
      $edad = $arrayTurno['edad'];
      $calzado = $arrayTurno['calzado'];
      $altura = $arrayTurno['altura'];
      $fechaNac = $arrayTurno['fechaNac'];
      $colorPelo = $arrayTurno['colorPelo'];
      $fechaTurno = $arrayTurno['fechaTurno'];
      $hturno = $arrayTurno['hturno'];
      $imgTurno = $arrayTurno['imgTurno'];

      $firstname = filter_var($firstname,FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
      if((strlen($firstname)<2) || (!preg_match("/^[a-zA-Z ]*$/",$firstname))){
        $erroresFormulario.="Error Nombre, ";
      }

      if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $erroresFormulario.="Error Mail, ";
      }

      if(strlen($tel)<12 || (!preg_match("/^[0-9]*$/",$tel))){
        $erroresFormulario.="Error Telefono, ";
      }

      $edad = filter_var($edad, FILTER_SANITIZE_NUMBER_INT);
      if((($edad<1) || ($edad>100)) || (!preg_match("/^[0-9]*$/",$edad))){
        $erroresFormulario.="Error Edad, ";
      }

      $calzado = filter_var($calzado, FILTER_SANITIZE_NUMBER_INT);
      if((($calzado<20) || ($calzado>45)) || (!preg_match("/^[0-9]*$/",$calzado))){
        $erroresFormulario.="Error Calzado, ";
      }

      $altura = filter_var($altura, FILTER_SANITIZE_NUMBER_INT);
      if((($altura<100) || ($altura>230)) || (!preg_match("/^[0-9]*$/",$altura))){
        $erroresFormulario.="Error Altura, ";
      }
      // yy-mm-dd
      //date(j) dia del mes sin cero
      //date(n) mes sin cero
      //date(Y) anio
      $fechaNac = explode('-',$fechaNac);
      if((checkdate($fechaNac[1],$fechaNac[2],$fechaNac[0]))){
        $fechaNac = implode('-',$fechaNac);
        if(date("Y-m-d")<$fechaNac){
          $erroresFormulario.="Error Fecha Nacimiento, ";
        }
      }

      $arrayPelo = ['negro','castanio','rubio','pelirrojo'];
      if((!in_array($colorPelo,$arrayPelo)) || (strlen($colorPelo)==0)){
        $erroresFormulario.="Error Color Pelo, ";
      }

      $fechaTurno = explode('-',$fechaTurno);
      if((checkdate($fechaTurno[1],$fechaTurno[2],$fechaTurno[0]))){
        $fechaTurno = implode('-',$fechaTurno);
        if(date("Y-m-d")>$fechaTurno){
          $erroresFormulario.="Error Fecha Turno, ";
        }
      }

      $hturno = explode(':',$hturno);
      if((($hturno[0]<8) || ($hturno[0]>17)) || (($hturno[1]<0) || ($hturno[1]>60))){
        if($hturno[1] % 15 != 0){
          $erroresFormulario.="Error Hora Turno, ";
        }
      }
      $hturno = implode(':',$hturno);

      $resumen[0] = $erroresFormulario;
      $resumen[1] = "Sin Imagen";

      $arrayTurno = [
            'firstname' => $firstname,
            'email' => $email,
            'tel' => $tel,
            'edad' => $edad,
            'calzado' => $calzado,
            'altura' => $altura,
            'fechaNac' => $fechaNac,
            'colorPelo' => $colorPelo,
            'fechaTurno' => $fechaTurno,
            'hturno' => $hturno,
            'diagnostico' => $imgTurno,
            'erroresFormulario' => $erroresFormulario
        ];
      return $arrayTurno;
    }

    public function insertTurnos($arrayTurno){
        $arrayTurno = $this->verificar($arrayTurno);
        $erroresFormulario = $arrayTurno['erroresFormulario'];
        $imgTurno = $arrayTurno['diagnostico'];
        //$arrayTurno = implode(',',$arrayTurno);
        //var_dump($arrayTurno);
        $resumen[0] = $erroresFormulario;
        $resumen[1] = "Sin imagen";
        unset($arrayTurno['erroresFormulario']);
        if(empty($erroresFormulario)){
          $arrayTurno['diagnostico'] = $this->cargarImagen($imgTurno);
          $consulta = "INSERT INTO turnos(id,nombre,email,tel,edad,calzado,altura,fechaNac,colorPelo,fechaTurno,hturno,diagnostico)
          VALUES (NULL,:firstname,:email,:tel,:edad,:calzado,:altura,:fechaNac,:colorPelo,:fechaTurno,:hturno,:diagnostico)";
          $resultado = $this->db->conn->prepare($consulta)->execute($arrayTurno);
          if(!empty($arrayTurno['diagnostico']))
            $resumen[1] = '<img src="'. '../'. $this->directorioImagenes . $arrayTurno['diagnostico'] . '" alt="Img Diagnostico" width="100px">';
        }
        return $resumen;
    }/*Fin INSERT*/

}
