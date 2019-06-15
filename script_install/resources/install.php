<?php
namespace Install;
require "dbConnection.php";

if($_SERVER['REQUEST_METHOD']=== 'POST'){
  $metodo= INPUT_POST;
}elseif ($_SERVER['REQUEST_METHOD']==='GET') {
  $metodo = INPUT_GET;
}else{
  http_response_code(400);
  echo "Peticion Malformada";
  exit;
}

$dbname = filter_input($metodo,'dbname',FILTER_SANITIZE_STRING);
$dbhost = filter_input($metodo,'dbhost',FILTER_SANITIZE_STRING);
$dbport = filter_input($metodo,'dbport',FILTER_SANITIZE_STRING);
$user = filter_input($metodo,'user',FILTER_SANITIZE_STRING);
$pass = filter_input($metodo,'pass',FILTER_SANITIZE_STRING);

$json_array = [
        'dbname' => $dbname,
        'dbhost' => $dbhost,
        'dbport' => $dbport,
        'username' => $user,
        'pass' => $pass,
];
  $rutaArchivoConfig = dirname(dirname(__DIR__)) .'/config/dbparameters.json';
  $save_json_array[] = $json_array;
  file_put_contents($rutaArchivoConfig, json_encode($save_json_array,JSON_PRETTY_PRINT));

$db = new dbConnection($json_array);
include "success.html";
?>
