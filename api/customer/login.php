<?php

    include('../../dbcon.php');

    header('Content-Type: application/json');

    if(isset($_POST['cu_type']) && isset($_POST['phone_code']) && isset($_POST['phone']) && isset($_POST['password']))
    {
        $enc_password = md5($_POST['password']);
         
        $login_sql = "SELECT * FROM customers WHERE cu_phone_code = '".$_POST['phone_code']."' and cu_phone = '".$_POST['phone']."' and cu_password = '$enc_password' and 
                        cu_type = '".$_POST['cu_type']."' and cu_verified = '1'";
        $result = mysqli_query($link, $login_sql);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $count = mysqli_num_rows($result);
        
        if($count == 1 && $row['cu_type'] == 1)
        {
            $responseData = ['success' => '1', 'id' => $row['cu_id'], 'name' => $row['cu_name'], 'email' => $row['cu_email'], 'phone_con_code' => $row['cu_phone_code'],
            'phone' => $row['cu_phone'], 'city' => $row['cu_city'], 'address' => $row['cu_address'], 'pin code' => $row['cu_pin_code'], 'pan number' => $row['cu_pan_num'],
            'registered on' => $row['cu_registered']];
            echo json_encode($responseData, JSON_PRETTY_PRINT);

            http_response_code(200);
            
            mysqli_close($link);
        }
        elseif($count == 1 && $row['cu_type'] == 2)
        {
            $responseData = ['success' => '1', 'id' => $row['cu_id'], 'name' => $row['cu_name'], 'email' => $row['cu_email'], 'phone_con_code' => $row['cu_phone_code'],
            'phone' => $row['cu_phone'], 'city' => $row['cu_city'], 'address' => $row['cu_address'], 'pin code' => $row['cu_pin_code'], 'pan number' => $row['cu_pan_num'],
            'company name' => $row['cu_co_name'], 'company type' => $row['cu_co_type'], 'company service tax' => $row['cu_co_service_tax'], 'compnay pan number' => $row['cu_co_pan_num'], 
            'company website' => $row['cu_co_website'], 'registered on' => $row['cu_registered']];
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