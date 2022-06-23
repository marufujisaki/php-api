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

// bethac9_appuser - appuserpassword
// $conn = mysqli_connect("bethacorp07.com","bethac9_appuser","appuserpassword","bethac9_app");
//   if(!$conn){
//     echo("database connected") . mysqli_connect_error();
//   }


// Consulta todos los registros de la tabla empleados
$sqlEstados = mysqli_query($conn,"SELECT * FROM estados ");
if(mysqli_num_rows($sqlEstados) > 0){
    $estados = mysqli_fetch_all($sqlEstados,MYSQLI_ASSOC);
    echo json_encode($estados);
}
else{ echo json_encode([["success"=>0]]); }



?>