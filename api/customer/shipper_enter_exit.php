<?php

    include('../../dbcon.php');

    header('Content-Type: application/json');
    
    if(isset($_POST['cu_phone_code']) && isset($_POST['cu_phone']) && isset($_POST['cu_token']))
    {
        $phone_code = $_POST['cu_phone_code'];
        $phone = $_POST['cu_phone'];
        $token = $_POST['cu_token'];
        
        $re_c = "select * from customers where cu_phone = '$phone'";
        $re_r = mysqli_query($link, $re_c);
        $row_c = mysqli_fetch_array($re_r, MYSQLI_ASSOC);
        $counte = mysqli_num_rows($re_r);
        if($counte >= 1)
        {
            $rancu_no = rand(100000, 999999);

            $mobile_sql = "update customers set cu_otp = '$rancu_no', cu_token = '$token' where cu_phone = '$phone' and cu_id = '".$row_c['cu_id']."'";

            $mobile_insert = mysqli_query($link, $mobile_sql);
            
            if($mobile_insert)
            {
                $api = '314319Asz8t1bwU0qU5e27d970P1';
                $msg = "Your OTP is $rancu_no. Do not share OTP. This OTP will expire in 20 minutes.";
                
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
        else
        {
            $rancu_no = rand(100000, 999999);

            date_default_timezone_set("Asia/Kolkata");
            $date = date('Y-m-d H:i:s');

            $mobile_sql = "insert into customers (cu_phone_code, cu_phone, cu_otp, cu_registered, cu_token) values ('$phone_code', '$phone', '$rancu_no', '$date', '$token')";

            $mobile_insert = mysqli_query($link, $mobile_sql);

            if($mobile_insert)
            {
                for($i = 1; $i <= 6; $i++)
                {
                    $re_c1 = "select * from customer_docs where doc_owner_phone = '$phone' and doc_sr_num = '$i'";
                    $re_r1 = mysqli_query($link, $re_c1);
                    $counte1 = mysqli_num_rows($re_r1);
                    if($counte1 == 0)
                    {
                        mysqli_query($link, "insert into customer_docs (doc_owner_phone, doc_sr_num) values ('$phone', '$i')");
                    }
                }

                $api = '321698AFaajkjGny5f65b2b7P1';
                $template = '5f9a630095ce6a72a977b143';
                
                $curl = curl_init();

                curl_setopt_array($curl, array(
                  CURLOPT_URL => "https://api.msg91.com/api/v5/otp?authkey=$api&template_id=$template&mobile=91$phone&invisible=1&otp=$rancu_no",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 10,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "GET",
                  CURLOPT_SSL_VERIFYHOST => 0,
                  CURLOPT_SSL_VERIFYPEER => 0,
                  CURLOPT_HTTPHEADER => array(
                    "content-type: application/json"
                  ),
                ));
                
                $response = curl_exec($curl);
                $err = curl_error($curl);
                
                curl_close($curl);

                $no_title = "New Shipper Registered";
                $no_message = "New shipper registered with phone number ".$_POST['cu_phone'];
                $no_for_id = $_POST['cu_phone'];
                $no_date = date('Y-m-d H:i:s');
mysqli_query($link, "insert into notifications (no_date_time, no_title, no_message, id) values('$no_date', '$no_title', '$no_message', '$no_for_id')");
                
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
    elseif(isset($_POST['logout_number']))
    {
        $inactive = "update customers set cu_active = 0 where cu_phone = '".$_POST['logout_number']."'";
        $set = mysqli_query($link, $inactive);

        $responseData = ['success' => '1', 'message' => 'User logged out'];
        echo json_encode($responseData, JSON_PRETTY_PRINT);

        http_response_code(200);
    }
    else
    {
        $responseData = ['success' => '0', 'message' => 'Something went wrong'];
        echo json_encode($responseData, JSON_PRETTY_PRINT);

        http_response_code(400);
    }

?>