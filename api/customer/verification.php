<?php

    include('../../dbcon.php');

    header('Content-Type: application/json');

    if(isset($_POST['phone_number']) && isset($_POST['otp']))
    {
        $otp_sql = "select * from temp_register1 where cu_phone = '".$_POST['phone_number']."' and t_otp = '".$_POST['otp']."' and t_verified = '0'";
        $otp_run = mysqli_query($link, $otp_sql);
        $otp_row = mysqli_fetch_array($otp_run, MYSQLI_ASSOC);

        $t_id = $otp_row['t_id'];

        if($otp_row['t_otp'] === $_POST['otp'])
        {
            $verifieto_sql = "update temp_register1 set t_verified = '1' where t_id = '$t_id'";
            $verifieto_insert = mysqli_query($link, $verifieto_sql);

            if($verifieto_insert)
            {
                $copy_sql = "select * from temp_register1 where t_id = '$t_id' and cu_phone = '".$_POST['phone_number']."' and t_otp = '".$_POST['otp']."' and t_verified = '1'";
                $copy_run = mysqli_query($link, $copy_sql);
                $copy_row = mysqli_fetch_array($copy_run, MYSQLI_ASSOC);

                $address = mysqli_real_escape_string($link, $copy_row['cu_address']);

                date_default_timezone_set("Asia/Kolkata");
                $date = date('Y-m-d');

                $cust_sql = "INSERT INTO customers (cu_name, cu_email, cu_phone_code, cu_phone, cu_password, cu_city, cu_address, cu_pin_code, cu_pan_num, cu_co_name, cu_co_type, 
                            cu_co_service_tax, cu_co_pan_num, cu_co_website, cu_type, cu_registered) VALUES ('".$copy_row['cu_name']."', '".$copy_row['cu_email']."', 
                            '".$copy_row['cu_phone_code']."', '".$copy_row['cu_phone']."', '".$copy_row['cu_password']."', '".$copy_row['cu_city']."', '$address',
                            '".$copy_row['cu_pin_code']."', '".$copy_row['cu_pan_num']."', '".$copy_row['cu_co_name']."', '".$copy_row['cu_co_type']."', '".$copy_row['cu_co_service_tax']."',
                            '".$copy_row['cu_co_pan_num']."', '".$copy_row['cu_co_website']."', '".$copy_row['cu_type']."', '$date')";
                $cust_insert = mysqli_query($link, $cust_sql);

                if($cust_insert)
                {
                    $del_sql = "delete from temp_register1 where t_id = '$t_id'";
                    $del_run = mysqli_query($link, $del_sql);

                    $responseData = ['success' => '1', 'message' => 'OTP verified. Registration successful'];
                    echo json_encode($responseData, JSON_PRETTY_PRINT);
                }
                else
                {
                    $responseData = ['success' => '0', 'message' => 'Server error'];
                    echo json_encode($responseData, JSON_PRETTY_PRINT);
                }
            }
            else
            {
                $responseData = ['success' => '0', 'message' => 'Server error. Try again'];
                echo json_encode($responseData, JSON_PRETTY_PRINT);
            }
        }
        else
        {
            $responseData = ['success' => '0', 'message' => 'Wrong OTP'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
        }
    }
    elseif(isset($_POST['resend_otp']))
    {
        $ranto_no = rand(100000, 999999);

        $phone = $_POST['resend_otp'];

        $re_sql = "update temp_register1 set t_otp = '$ranto_no' where cu_phone = '$phone' and t_verified = '0'";
        $re_run = mysqli_query($link, $re_sql);
        
        if($re_run)
        {
            $api = '314319Asz8t1bwU0qU5e27d970P1';
            $msg = "Your new OTP for Customer registration is $ranto_no. This OTP will expire in 20 minutes.";
            
            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => "http://control.msg91.com/api/v5/otp?authkey=$api&mobiles=$phone&message=$msg&sender=TRNSPT&country=91&otp=$ranto_no",
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
            
            $responseData = ['success' => '1', 'message' => 'New OTP sent to your given number. Please verify your number'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
        } 
        else
        {
            $responseData = ['success' => '0', 'message' => 'Something went wrong'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
        }
    }
    else
    {
        $responseData = ['success' => '0', 'message' => 'Something is missing'];
        echo json_encode($responseData, JSON_PRETTY_PRINT);
    }

?>