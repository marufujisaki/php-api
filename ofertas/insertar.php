<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET,POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


$conn = mysqli_connect("localhost","root","","app");
  if(!$conn){
    echo("database connected") . mysqli_connect_error();
  }
  $lic = mysqli_real_escape_string($conn, $_POST["id"]);
  if(!empty($lic)){
      if(isset($_FILES["file"])){
        $img_name = $_FILES['file']['name'];
        $img_type = $_FILES['file']['type'];
        $tmp_name = $_FILES['file']['tmp_name'];
        $time = time();
        $new_file_name = $time.$img_name;
        if(move_uploaded_file($tmp_name,"files/".$new_file_name)){
          $state = 0;
          $insert_query = mysqli_query($conn, "INSERT INTO ofertas (lic_id, route, state)
            VALUES ({$lic},'{$new_file_name}', {$state})");
          if($insert_query){
            $select_sql2 = mysqli_query($conn, "SELECT * FROM ofertas WHERE lic_id = '{$lic}'");
            if(mysqli_num_rows($select_sql2) > 0){
              $result = mysqli_fetch_assoc($select_sql2);
              echo "success";
            }else{
              echo "No se inserto";
            }
        }else{
          echo "Something went wrong. Please try again!";
        }
      }else{
        echo "No se movio";
      }
  }else{
    echo "No hay archivos, intente denuevo";
  }

  }else{
    echo "No hay ID de la Licitacion, intente denuevo";
  }

?>