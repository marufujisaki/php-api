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
    // Decode json
    $data = json_decode(file_get_contents("php://input"));

    $user_id = $data->user_id;
    $sqlId = mysqli_query($conn,"SELECT id FROM users WHERE unique_id = {$user_id}");
    if(mysqli_num_rows($sqlId) > 0){
      $row = mysqli_fetch_assoc($sqlId);
      $user = $row["id"];
      $sqlLicitaciones =mysqli_query($conn,"SELECT * FROM licitaciones WHERE user_id={$user}");
        $licitaciones = mysqli_fetch_all($sqlLicitaciones,MYSQLI_ASSOC);
        echo json_encode($licitaciones);
        exit();
    }else{  
      echo json_encode([]); 
      exit();
    }
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


if(isset($_GET["insertar"])){
  // Decode json
  $data = json_decode(file_get_contents("php://input"));

  // Extracting data
  $user_id = $data->user_id;
  $release_date = $data->release_date;
  $final_date = $data->final_date;
  $comments = $data->comments;

  // Additional info
  $user = 0;
  $lic_id = rand(time(), 100000000);
  $state = 0; // Licitaciones respondida o no ---- 0 No 1 Si

  // Check if all the fields were filled
  if(!empty($user_id) &&!empty($final_date) && !empty($comments)) {
    // check if the user email is valid
      $sqlId = mysqli_query($conn,"SELECT id FROM users WHERE unique_id = {$user_id}");
      if(mysqli_num_rows($sqlId) > 0){
        $row = mysqli_fetch_assoc($sqlId);
        $user = $row["id"];

        $insertLic = mysqli_query($conn,"INSERT INTO licitaciones (lic_key,user_id,release_date,final_date,state,comments)
        VALUES ({$lic_id},{$user},'{$release_date}', '{$final_date}',{$state},'{$comments}')");

        if($insertLic){
          $selectSql = mysqli_query($conn, "SELECT * FROM licitaciones WHERE lic_key = '{$lic_id}'");
          if(mysqli_num_rows($selectSql)>0){
            $response = array("success"=>1,"lic_key"=>$lic_id);
          }
        }

      }else{
        $response = array("success"=>0,"message"=>"Error en el usuario");
      }
    
  }else{
    $response = array("success"=>0,"message"=>"Todos los datos son requeridos");
  }

  // Encode json and goodbye api
  echo json_encode($response);
  exit();
}

if(isset($_GET["actualizar"])){
  $data = json_decode(file_get_contents("php://input"));

  $id = $data->id;
  $state = $data->state;

  $sqlLic = mysqli_query($conn,"UPDATE licitaciones SET state = {$state} WHERE id={$id}");
  if($sqlLic){
    $response = array("success"=>1);
  }else{
    $response = array("success"=>0);
  }
  echo json_encode($response);
  exit();
}

if(isset($_GET["cierre"])){
  $data = json_decode(file_get_contents("php://input"));

  $date = $data->fecha;

  $sqlLic = mysqli_query($conn,"UPDATE licitaciones SET state = 2 WHERE state = 0 AND final_date < '{$date}'");
  if($sqlLic){
    $response = array("success"=>1);
  }else{
    $response = array("success"=>0);
  }
  echo json_encode($response);
  exit();
}
// Consulta todos los registros de la tabla empleados
$sql = mysqli_query($conn,"SELECT * FROM licitaciones");
if(mysqli_num_rows($sql) > 0){
    $results = mysqli_fetch_all($sql,MYSQLI_ASSOC);
    echo json_encode($results);
}
else{ echo json_encode([["success"=>0]]); }



?>