<?php

    include('../../dbcon.php');

    header('Content-Type: application/json');

    if(isset($_POST['phone_code']) && isset($_POST['phone']) && isset($_POST['password']))
    {
        $enc_password = md5($_POST['password']);
         
        $login_sql = "SELECT * FROM truck_owners WHERE to_phone_code = '".$_POST['phone_code']."' and to_phone = '".$_POST['phone']."' and to_password = '$enc_password' and to_verified = '1'";
        $result = mysqli_query($link, $login_sql);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $count = mysqli_num_rows($result);
        
        if($count == 1)
        {
            $responseData = ['success' => '1', 'id' => $row['to_id'], 'name' => $row['to_name'], 'email' => $row['to_email'], 'phone_con_code' => $row['to_phone_code'],
            'phone' => $row['to_phone'], 'city' => $row['to_city'], 'address' => $row['to_address'], 'operating routes' => $row['to_routes'], 'permit states' => $row['to_permits'],
            'pan' => $row['to_pan'], 'bank' => $row['to_bank'], 'ifsc' => $row['to_ifsc'], 'registered on' => $row['to_registered']];
            echo json_encode($responseData, JSON_PRETTY_PRINT);

            http_response_code(200);
            
            mysqli_close($link);
        }
        else
        {
            $responseData = ['success' => '0', 'message' => 'Invalid Phone or Password or Admin has not accepted your registration yet'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);

            http_response_code(400);
        }
    }
    else
    {
        $responseData = ['success' => '0', 'message' => 'Phone or password is missing'];
        echo json_encode($responseData, JSON_PRETTY_PRINT);

        http_response_code(206);
    }

?>