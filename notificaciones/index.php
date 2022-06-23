<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET,POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$conn = mysqli_connect("localhost","root","","app");
  if(!$conn){
    echo("database connected") . mysqli_connect_error();
  }

  if(isset($_GET["insertar"])){
    $data = json_decode(file_get_contents("php://input"));

    $id = $data->user;
    $message = $data->message;

    $sqlInsert = mysqli_query($conn,"INSERT INTO notificaciones (user_id,text) VALUES({$id},'{$message}')");
    if($sqlInsert){
      $response = array("success"=>1);
    }else{
      $response = array("success"=>0,"message"=>"No se pudo");
    }
    echo json_encode($response);
    exit();
  }

  // Consulta todos los registros de la tabla empleados
$sql = mysqli_query($conn,"SELECT * FROM notificaciones");
if(mysqli_num_rows($sql) > 0){
    $results = mysqli_fetch_all($sql,MYSQLI_ASSOC);
    echo json_encode($results);
}
else{ echo json_encode([["success"=>0]]); }


?>