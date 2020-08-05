<?php

    include('../../dbcon.php');

    header('Content-Type: application/json');

    if(isset($_POST['co_cu_name']) && isset($_POST['co_cu_email']) && isset($_POST['co_cu_phone_code']) && isset($_POST['co_cu_phone']) && isset($_POST['co_cu_password']) && 
        isset($_POST['co_cu_cnf_password']) && isset($_POST['co_cu_city']) && isset($_POST['co_cu_address']) && isset($_POST['co_cu_pin']) && isset($_POST['co_cu_pan']) &&
        isset($_POST['co_name']) && isset($_POST['co_type']) && isset($_POST['co_service_tax']) && isset($_POST['co_pan_num']) && isset($_POST['co_website']))
    {
        $name = $_POST['co_cu_name'];
        $email = $_POST['co_cu_email'];
        $phone_code = $_POST['co_cu_phone_code'];
        $phone = $_POST['co_cu_phone'];
        $phone_w_code = $phone_code.$phone;
        $pass = $_POST['co_cu_password'];
        $cnf_pass = $_POST['co_cu_cnf_password'];
        $city = $_POST['co_cu_city'];
        $address = $_POST['co_cu_address'];
        $address = mysqli_real_escape_string($link, $address);
        $pin = $_POST['co_cu_pin'];
        $pan = $_POST['co_cu_pan'];

        $co_name = $_POST['co_name'];
        $co_type = $_POST['co_type'];
        $co_service_tax = $_POST['co_service_tax'];
        $co_pan_num = $_POST['co_pan_num'];
        $co_website = $_POST['co_website'];
        
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $responseData = ['success' => '0', 'message' => 'Invalid email format'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
        }
        else
        {
            $sqle = "SELECT * FROM customers where cu_email = '$email'";
            $checke = mysqli_query($link, $sqle);
            $rowe = mysqli_fetch_array($checke, MYSQLI_ASSOC);
            $counte = mysqli_num_rows($checke);
            if($counte >= 1)
            {
                $responseData = ['success' => '0', 'message' => 'This email id is already registered'];
                echo json_encode($responseData, JSON_PRETTY_PRINT);
            }
            else
            {
        
                $sqlr = "SELECT * FROM customers where cu_phone_code = '$phone_code' and cu_phone = '$phone'";
                $checkr = mysqli_query($link, $sqlr);
                $rowr = mysqli_fetch_array($checkr, MYSQLI_ASSOC);
                $count = mysqli_num_rows($checkr);
                if($count >= 1)
                {
                    $responseData = ['success' => '0', 'message' => 'This Phone number is already registered'];
                    echo json_encode($responseData, JSON_PRETTY_PRINT);
                }
                else
                {

                    $sqlr = "SELECT * FROM customers where cu_pan_num = '$pan'";
                    $checkr = mysqli_query($link, $sqlr);
                    $rowr = mysqli_fetch_array($checkr, MYSQLI_ASSOC);
                    $count = mysqli_num_rows($checkr);
                    if($count >= 1)
                    {
                        $responseData = ['success' => '0', 'message' => 'This PAN number is already registered'];
                        echo json_encode($responseData, JSON_PRETTY_PRINT);
                    }
                    else
                    {

                        $sqlr = "SELECT * FROM customers where cu_co_service_tax = '$co_service_tax'";
                        $checkr = mysqli_query($link, $sqlr);
                        $rowr = mysqli_fetch_array($checkr, MYSQLI_ASSOC);
                        $count = mysqli_num_rows($checkr);
                        if($count >= 1)
                        {
                            $responseData = ['success' => '0', 'message' => 'This Service Tax number is already registered'];
                            echo json_encode($responseData, JSON_PRETTY_PRINT);
                        }
                        else
                        {
                            $sqlr = "SELECT * FROM customers where cu_co_pan_num = '$co_pan_num'";
                            $checkr = mysqli_query($link, $sqlr);
                            $rowr = mysqli_fetch_array($checkr, MYSQLI_ASSOC);
                            $count = mysqli_num_rows($checkr);
                            if($count >= 1)
                            {
                                $responseData = ['success' => '0', 'message' => 'This Company PAN number is already registered'];
                                echo json_encode($responseData, JSON_PRETTY_PRINT);
                            }
                            else
                            {

                                if($pass === $cnf_pass)
                                {
                                    $ranto_no = rand(100000, 999999);

                                    $enc_p = md5($pass);

                                    $mobile_sql = "insert into temp_register1 (cu_name, cu_email, cu_phone_code, cu_phone, cu_password, cu_city, cu_address, cu_pin_code, 
                                                    cu_pan_num, cu_co_name, cu_co_type, cu_co_service_tax, cu_co_pan_num, cu_co_website, cu_type, t_otp) 
                                                    values ('$name', '$email', '$phone_code', '$phone', '$enc_p', '$city', '$address', '$pin', '$pan', '$co_name', '$co_type', 
                                                    '$co_service_tax', '$co_pan_num', '$co_website', '2', '$ranto_no')";

                                    $mobile_insert = mysqli_query($link, $mobile_sql);
                                    
                                    if($mobile_insert)
                                    {
                                        $api = '314319Asz8t1bwU0qU5e27d970P1';
                                        $msg = "Your OTP for Customer registration is $ranto_no. This OTP will expire in 20 minutes.";
                                        
                                        $curl = curl_init();

                                        curl_setopt_array($curl, array(
                                        CURLOPT_URL => "http://control.msg91.com/api/v5/otp?authkey=$api&mobiles=$phone&message=$msg&sender=TRNSPT&country=$phone_code&otp=$ranto_no",
                                        CURLOPT_RETURNTRANSFER => true,
                                        CURLOPT_ENCODING => "",
                                        CURLOPT_MAXREDIRS => 10,
                                        CURLOPT_TIMEOUT => 20,
                                        CURLOPT_FOLLOWLOCATION => true,
                                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                        CURLOPT_CUSTOMREQUEST => "POST",
                                        CURLOPT_HTTPHEADER => array(
                                            "Cookie: PHPSESSID=odd5sg5bulmmhc82a8v81ckbe1"
                                        ),
                                        ));

                                        $response = curl_exec($curl);

                                        curl_close($curl);
                                        
                                        $responseData = ['success' => '1', 'message' => 'OTP sent to your given number. Please verify your number'];
                                        echo json_encode($responseData, JSON_PRETTY_PRINT);

                                        http_response_code(200);
                                    } 
                                    else
                                    {
                                        $responseData = ['success' => '0', 'message' => 'Something went wrong. Error'];
                                        echo json_encode($responseData, JSON_PRETTY_PRINT);
                                    }
                                }
                                else
                                {
                                    $responseData = ['success' => '0', 'message' => 'Both password must be same'];
                                    echo json_encode($responseData, JSON_PRETTY_PRINT);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    else
    {
        $responseData = ['success' => '0', 'message' => 'Something is missing'];
        echo json_encode($responseData, JSON_PRETTY_PRINT);
    }

?>