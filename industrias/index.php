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

// Consulta todos los registros de la tabla empleados
$sqlIndustry = mysqli_query($conn,"SELECT * FROM industrias");
if(mysqli_num_rows($sqlIndustry) > 0){
    $industries = mysqli_fetch_all($sqlIndustry,MYSQLI_ASSOC);
    echo json_encode($industries);
}
else{ echo json_encode([["success"=>0]]); }



?>