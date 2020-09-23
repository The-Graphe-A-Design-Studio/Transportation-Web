<?php

    include('../../dbcon.php');

    header('Content-Type: application/json');
    
    if(isset($_POST['trk_phone_code']) && isset($_POST['trk_phone']) && isset($_POST['trk_token']))
    {
        $phone_code = $_POST['trk_phone_code'];
        $phone = $_POST['trk_phone'];
        $token = $_POST['trk_token'];
        
        $re_c = "select * from customers where trk_phone = '$phone'";
        $re_r = mysqli_query($link, $re_c);
        $row_c = mysqli_fetch_array($re_r, MYSQLI_ASSOC);
        $counte = mysqli_num_rows($re_r);
        if($counte >= 1)
        {
            $rantrk_no = rand(100000, 999999);

            $mobile_sql = "update customers set trk_otp = '$rantrk_no', trk_token = '$token' where trk_phone = '$phone' and trk_id = '".$row_c['trk_id']."'";

            $mobile_insert = mysqli_query($link, $mobile_sql);
            
            if($mobile_insert)
            {
                $api = '314319Asz8t1bwU0qU5e27d970P1';
                $msg = "Your OTP is $rantrk_no. Do not share OTP. This OTP will expire in 20 minutes.";
                
                $curl = curl_init();

                curl_setopt_array($curl, array(
                CURLOPT_URL => "http://control.msg91.com/api/v5/otp?authkey=$api&mobiles=$phone&message=$msg&sender=TRNSPT&country=$phone_code&otp=$rantrk_no",
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
            $rantrk_no = rand(100000, 999999);

            date_default_timezone_set("Asia/Kolkata");
            $date = date('Y-m-d H:i:s');

            $mobile_sql = "insert into customers (trk_phone_code, trk_phone, trk_otp, trk_registered, trk_token) values ('$phone_code', '$phone', '$rantrk_no', '$date', '$token')";

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

                $api = '314319Asz8t1bwU0qU5e27d970P1';
                $msg = "Your OTP is $rantrk_no. Do not share OTP. This OTP will expire in 20 minutes.";
                
                $curl = curl_init();

                curl_setopt_array($curl, array(
                CURLOPT_URL => "http://control.msg91.com/api/v5/otp?authkey=$api&mobiles=$phone&message=$msg&sender=TRNSPT&country=$phone_code&otp=$rantrk_no",
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
    elseif(isset($_POST['logout_number']))
    {
        $inactive = "update customers set trk_active = 0 where trk_phone = '".$_POST['logout_number']."'";
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