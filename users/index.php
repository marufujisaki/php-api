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
  $user_name = $data->user_name;
  $email = $data->email;
  $password = $data->password;
  $password_confirm = $data->password_confirm;
  $industry = $data->industria;
  $contact_name = $data->contact_name;
  $rif = $data->rif;
  $state = $data->estado;
  $address = $data->address;
  $phone = $data->phone;

  // Additional info
  $ran_id = rand(time(), 100000000);
  $user_type = 1; // Si es cliente o distribuidor ---- 0 Dist 1 Client
  $auth = 0; // Usuario verificado o no ---- 0 No 1 Si

  // Check if all the fields were filled
  if(!empty($user_name) && !empty($rif) && !empty($address) && !empty($phone) && !empty($industry) && !empty($state) && !empty($contact_name) && !empty($email) && !empty($password) && !empty($password_confirm)) {
    // check if the user email is valid
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
      // check if the email already exists in the db
      $sql = mysqli_query($conn,"SELECT email FROM users WHERE email = '{$email}'");
      if(mysqli_num_rows($sql) > 0){
        $response = array("success"=>0,"message"=>"Ya existe una cuenta con este correo electrónico");
      }else{
        // check if the password and confirm password field are correct
        if($password == $password_confirm){
          $insertUser = mysqli_query($conn,"INSERT INTO users (unique_id, email, password, user_type, user_name, ind_id, contact_name, rif, state_id, address, phone, auth)
          VALUES ({$ran_id}, '{$email}','{$password}', {$user_type}, '{$user_name}', {$industry}, '{$contact_name}', '{$rif}', {$state}, '{$address}', '{$phone}', {$auth})");
          if($insertUser){
            $select_sql2 = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
            if(mysqli_num_rows($select_sql2) > 0){
              $row = mysqli_fetch_assoc($select_sql2);
              $response = array("success"=>1, "message"=>"Everything's fine", "unique_id"=>$row['unique_id'],"user_type"=>$row['user_type'],"user_name"=>$row['user_name'],"contact_name"=>$row['contact_name'],"auth"=>$row['auth']);
            }
          }
        }else{
          $response = array("success"=>0,"message"=>"Las contraseñas suministradas no coinciden");
        }
      }
    }else{
      $response = array("success"=>0,"message"=>"Ingrese un correo válido");
    }
  }else{
    $response = array("success"=>0,"message"=>"Todos los datos son requeridos");
  }

  // Encode json and goodbye api
  echo json_encode($response);
  exit();
}

if(isset($_GET["login"])){
  $data = json_decode(file_get_contents("php://input"));
  $email = $data->email;
  $password = $data->password;
  $sql = mysqli_query($conn,"SELECT * FROM users WHERE email = '{$email}'");
    if(mysqli_num_rows($sql) > 0){
      // aqui agarra esa sola fila de la tabla
      $row = mysqli_fetch_assoc($sql);
      $db_password = $row['password'];
      if($db_password == $password) {
        $response = array("success"=>1, "message"=>"Everything's fine", "unique_id"=>$row['unique_id'],"user_type"=>$row['user_type'],"user_name"=>$row['user_name'],"contact_name"=>$row['contact_name'],"auth"=>$row['auth']);
        echo json_encode($response);
      }else{
        $response = array("success"=>0, "message"=>"La contraseña ingresada es incorrecta");
        echo json_encode($response);
      }
    }else{
      $response = array("success"=>0, "message"=>"No existe ninguna cuenta con este correo electrónico");
      echo json_encode($response);
    }
    exit();  
}

if(isset($_GET["aprobar"])){

  $data = json_decode(file_get_contents("php://input"));

  $id = $data->id;
  $auth = 1;

  $sqlUsers = mysqli_query($conn,"UPDATE users SET auth=".$auth." WHERE unique_id=".$id);
  if($sqlUsers){
    $response = array("success"=>1);
  }else{
    $response = array("success"=>0);
  }
  echo json_encode($response);
  exit();
}

// Consulta todos los registros de la tabla empleados
$sqlUsers = mysqli_query($conn,"SELECT * FROM users ");
if(mysqli_num_rows($sqlUsers) > 0){
    $users = mysqli_fetch_all($sqlUsers,MYSQLI_ASSOC);
    echo json_encode($users);
}
else{ echo json_encode([["success"=>0]]); }



?>