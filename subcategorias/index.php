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
$sqlCategories = mysqli_query($conn,"SELECT * FROM subcategorias");
if(mysqli_num_rows($sqlCategories) > 0){
    $categories = mysqli_fetch_all($sqlCategories,MYSQLI_ASSOC);
    echo json_encode($categories);
}
else{ echo json_encode([["success"=>0]]); }



?>