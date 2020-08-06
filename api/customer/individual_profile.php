<?php

    include('../../dbcon.php');

    header('Content-Type: application/json');
    
    if(isset($_POST['cu_id']) && isset($_POST['cu_name']) && isset($_POST['cu_email']) && isset($_POST['cu_phone_code']) && isset($_POST['cu_phone']) && 
        isset($_POST['cu_city']) && isset($_POST['cu_address']) && isset($_POST['cu_pin']) && isset($_POST['cu_pan']))
    {
        $id = $_POST['cu_id'];
        $name = $_POST['cu_name'];
        $email = $_POST['cu_email'];
        $phone_code = $_POST['cu_phone_code'];
        $phone = $_POST['cu_phone'];
        $phone_w_code = $phone_code.$phone;
        $city = $_POST['cu_city'];
        $address = $_POST['cu_address'];
        $address = mysqli_real_escape_string($link, $address);
        $pin = $_POST['cu_pin'];
        $pan = $_POST['cu_pan'];

        $re_c = "select * from customers where cu_id = '".$_POST['cu_id']."'";
        $re_r = mysqli_query($link, $re_c);
        $row_c = mysqli_fetch_array($re_r, MYSQLI_ASSOC);
        
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $responseData = ['success' => '0', 'message' => 'Invalid email format'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
            http_response_code(400);
        }
        else
        {
            $sqle = "SELECT * FROM customers where cu_email = '$email' and cu_id <> '$id'";
            $checke = mysqli_query($link, $sqle);
            $rowe = mysqli_fetch_array($checke, MYSQLI_ASSOC);
            $counte = mysqli_num_rows($checke);
            if($counte >= 1)
            {
                $responseData = ['success' => '0', 'message' => 'This email id is already registered'];
                echo json_encode($responseData, JSON_PRETTY_PRINT);
                http_response_code(400);
            }
            else
            {
        
                $sqlr = "SELECT * FROM customers where cu_pan_num = '$pan' and cu_id <> '$id'";
                $checkr = mysqli_query($link, $sqlr);
                $rowr = mysqli_fetch_array($checkr, MYSQLI_ASSOC);
                $count = mysqli_num_rows($checkr);
                if($count >= 1)
                {
                    $responseData = ['success' => '0', 'message' => 'This PAN number is already registered'];
                    echo json_encode($responseData, JSON_PRETTY_PRINT);
                    http_response_code(400);
                }
                else
                {

                    if($_POST['cu_phone_code'] == $row_c['cu_phone_code'] && $_POST['cu_phone'] == $row_c['cu_phone'])
                    {
                        $up_c = "update customers set cu_name = '".$_POST['cu_name']."', cu_email = '".$_POST['cu_email']."', 
                                cu_phone_code = '".$_POST['cu_phone_code']."', cu_phone = '".$_POST['cu_phone']."', cu_city = '".$_POST['cu_city']."', 
                                cu_address = '".$_POST['cu_address']."', cu_pin_code = '".$_POST['cu_pin']."', cu_pan_num = '".$_POST['cu_pan']."' 
                                where cu_id = '".$_POST['cu_id']."'";

                        $run_c = mysqli_query($link, $up_c);

                        if($run_c)
                        {
                            $responseData = ['success' => '1', 'message' => 'Profile info updated'];
                            echo json_encode($responseData, JSON_PRETTY_PRINT);

                            http_response_code(202);
                        }
                        else
                        {
                            $responseData = ['success' => '0', 'message' => 'Profile info update failed'];
                            echo json_encode($responseData, JSON_PRETTY_PRINT);

                            http_response_code(400);
                        }
                    }
                    else
                    {
                        $rancu_no = rand(100000, 999999);

                        $mobile_sql = "insert into temp_register1 (cu_name, cu_email, cu_phone_code, cu_phone, cu_city, cu_address, 
                                        cu_pin_code, cu_pan_num, t_otp, t_reg_user) values ('$name', '$email', '$phone_code', '$phone', '$city', 
                                        '$address', '$pin', '$pan', '$rancu_no', '1')";

                        $mobile_insert = mysqli_query($link, $mobile_sql);
                        
                        if($mobile_insert)
                        {
                            $api = '314319Asz8t1bwU0qU5e27d970P1';
                            $msg = "Your OTP for changing your Phone number is $rancu_no. This OTP will expire in 20 minutes.";
                            
                            $curl = curl_init();

                            curl_setopt_array($curl, array(
                            CURLOPT_URL => "http://control.msg91.com/api/v5/otp?authkey=$api&mobiles=$phone&message=$msg&sender=TRNSPT&country=$phone_code&otp=$rancu_no",
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

                            http_response_code(400);
                        }
                    }
                }
            }
        }
    }
    else
    {
        $responseData = ['success' => '0', 'message' => 'Something went wrong'];
        echo json_encode($responseData, JSON_PRETTY_PRINT);

        http_response_code(400);
    }

?>