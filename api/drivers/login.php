<?php

    include('../../dbcon.php');

    header('Content-Type: application/json');

    if(isset($_POST['phone_code']) && isset($_POST['phone']) && isset($_POST['password']))
    {
        $enc_password = md5($_POST['password']);
         
        $login_sql = "SELECT * FROM drivers WHERE d_phone_code = '".$_POST['phone_code']."' and d_phone = '".$_POST['phone']."' and d_password = '$enc_password'";
        $result = mysqli_query($link, $login_sql);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $count = mysqli_num_rows($result);
        
        if($count == 1)
        {
            $responseData = ['success' => '1', 'd_id' => $row['d_id'], 'd_name' => $row['d_name'], 'd_email' => $row['d_email'], 'd_phone_code' => $row['d_phone_code'],
            'd_phone' => $row['d_phone'], 'd_password' => $row['d_password'], 'd_address' => $row['d_address'], 'd_rc' => $row['d_rc'], 'd_license' => $row['d_license'],
            'd_insurance' => $row['d_insurance'], 'd_road_tax' => $row['d_road_tax'], 'd_rto' => $row['d_rto'], 'd_pan' => $row['d_pan'], 'd_bank' => $row['d_bank'],
            'd_ifsc' => $row['d_ifsc'], 'd_verified' => $row['d_verified'], 'd_registered' => $row['d_registered']];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
            
            mysqli_close($link);
        }
        else
        {
            $responseData = ['success' => '0', 'message' => 'Invalid Phone or Password'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
        }
    }
    else
    {
        $responseData = ['success' => '0', 'message' => 'Phone or password is missing'];
        echo json_encode($responseData, JSON_PRETTY_PRINT);
    }

?>