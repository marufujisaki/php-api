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
  $lic_id = $data->lic_key;
  $products = $data->products;
  if(!empty($lic_id) && !empty($products)){
    $sqlId = mysqli_query($conn,"SELECT id FROM licitaciones WHERE lic_key = {$lic_id}");
    if(mysqli_num_rows($sqlId)>0){
      $row = mysqli_fetch_assoc($sqlId);
      $lic = $row["id"];

      foreach ($products as $value) {
        $subcategoria = $value->subcategoria;
        $detalles = $value->details;
        $sku = rand(time(), 100000000);
    
        $insertProd = mysqli_query($conn,"INSERT INTO productos_servicios (sku,detalles,lic_id,subcat_id)
          VALUES ({$sku},'{$detalles}',{$lic}, {$subcategoria})");
      }
      if($insertProd){
        $selectSql = mysqli_query($conn, "SELECT * FROM productos_servicios WHERE lic_id = {$lic}");
        if(mysqli_num_rows($selectSql)>0){
        $response = array("success"=>1,"message"=>"Everythings fine");
        }
      }
    }else{
      $response = array("success"=>0,"message"=>"No existe esta licitacion");
    }
  }else{
    $response = array("success"=>0,"message"=>"Todos los campos son requeridos");
  }
  
  
  // Encode json and goodbye api
  echo json_encode($response);
  exit();
}


// Consulta todos los registros de la tabla empleados
$sqlProducts = mysqli_query($conn,"SELECT * FROM productos_servicios");
if(mysqli_num_rows($sqlProducts) > 0){
    $products = mysqli_fetch_all($sqlProducts,MYSQLI_ASSOC);
    echo json_encode($products);
}
else{ echo json_encode([["success"=>0]]); }



?>