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

  // Consulta datos y recepciona una clave para consultar dichos datos con dicha clave
  if (isset($_GET["consultar"])){
   
    $pendientes = mysqli_query($conn,"SELECT * FROM users WHERE auth=".$auth);
    if(mysqli_num_rows($pendientes) > 0){
        $users = mysqli_fetch_all($pendientes,MYSQLI_ASSOC);
        echo json_encode($users);
        exit();
    }
    else{  
      echo json_encode([]); 
    exit();}
  }

  if(isset($_GET["actualizar"])){
    $data = json_decode(file_get_contents("php://input"));
  
    $id = $data->id;
    $state = $data->state;
  
    $sqlUpdate = mysqli_query($conn,"UPDATE ofertas SET state = {$state} WHERE id={$id}");
    if($sqlUpdate){
      $response = array("success"=>1);
    }else{
      $response = array("success"=>0);
    }
    echo json_encode($response);
    exit();
  }


  if (isset($_GET["pendientes"])){
  $auth = 0;
  $pendientes = mysqli_query($conn,"SELECT * FROM users WHERE auth=".$auth);
  if(mysqli_num_rows($pendientes) > 0){
      $users = mysqli_fetch_all($pendientes,MYSQLI_ASSOC);
      echo json_encode($users);
      exit();
  }
  else{  
    echo json_encode([]); 
  exit();}
  }




// Consulta todos los registros de la tabla empleados
$sqlofertas = mysqli_query($conn,"SELECT * FROM ofertas");
if(mysqli_num_rows($sqlofertas) > 0){
    $ofertas = mysqli_fetch_all($sqlofertas,MYSQLI_ASSOC);
    echo json_encode($ofertas);
}
else{ echo json_encode([["success"=>0]]); }



?>