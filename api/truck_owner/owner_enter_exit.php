<?php

    include('../../dbcon.php');

    header('Content-Type: application/json');
    
    if(isset($_POST['to_phone_code']) && isset($_POST['to_phone']) && isset($_POST['to_token']))
    {
        $phone_code = $_POST['to_phone_code'];
        $phone = $_POST['to_phone'];
        $token = $_POST['to_token'];
        
        $re_c = "select * from truck_owners where to_phone = '$phone'";
        $re_r = mysqli_query($link, $re_c);
        $row_c = mysqli_fetch_array($re_r, MYSQLI_ASSOC);
        $counte = mysqli_num_rows($re_r);
        if($counte >= 1)
        {
            $ranto_no = rand(100000, 999999);

            $mobile_sql = "update truck_owners set to_otp = '$ranto_no', to_token = '$token' where to_phone = '$phone' and to_id = '".$row_c['to_id']."'";

            $mobile_insert = mysqli_query($link, $mobile_sql);
            
            if($mobile_insert)
            {
                $api = '314319Asz8t1bwU0qU5e27d970P1';
                $msg = "Your OTP is $ranto_no. Do not share OTP. This OTP will expire in 20 minutes.";
                
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

                http_response_code(400);
            }
        }
        else
        {
            $ranto_no = rand(100000, 999999);

            date_default_timezone_set("Asia/Kolkata");
            $date = date('Y-m-d H:i:s');

            $mobile_sql = "insert into truck_owners (to_phone_code, to_phone, to_otp, to_registered, to_token) values ('$phone_code', '$phone', '$ranto_no', '$date', '$token')";

            $mobile_insert = mysqli_query($link, $mobile_sql);

            if($mobile_insert)
            {
                for($i = 1; $i <= 1; $i++)
                {
                    $re_c1 = "select * from truck_owner_docs where to_doc_owner_phone = '$phone' and to_doc_sr_num = '$i'";
                    $re_r1 = mysqli_query($link, $re_c1);
                    $counte1 = mysqli_num_rows($re_r1);
                    if($counte1 == 0)
                    {
                        mysqli_query($link, "insert into truck_owner_docs (to_doc_owner_phone, to_doc_sr_num) values ('$phone', '$i')");
                    }
                }

                $api = '314319Asz8t1bwU0qU5e27d970P1';
                $msg = "Your OTP is $ranto_no. Do not share OTP. This OTP will expire in 20 minutes.";
                
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

                $no_title = "New Owner Registered";
                $no_message = "New truck owner registered with phone number ".$_POST['to_phone'];
                $no_for_id = $_POST['to_phone'];
                mysqli_query($link, "insert into notifications (no_title, no_message, id) values('$no_title', '$no_message', '$no_for_id')");
                
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
        $inactive = "update truck_owners set to_active = 0 where to_phone = '".$_POST['logout_number']."'";
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