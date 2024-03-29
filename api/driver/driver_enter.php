<?php

    include('../../dbcon.php');

    header('Content-Type: application/json');
    
    if(isset($_POST['trk_dr_phone_code']) && isset($_POST['trk_dr_phone']) && isset($_POST['trk_dr_token']))
    {
        $phone_code = $_POST['trk_dr_phone_code'];
        $phone = $_POST['trk_dr_phone'];
        $token = $_POST['trk_dr_token'];
        
        $re_c = "select * from trucks where trk_dr_phone = '$phone'";
        $re_r = mysqli_query($link, $re_c);
        $row_c = mysqli_fetch_array($re_r, MYSQLI_ASSOC);
        $counte = mysqli_num_rows($re_r);

        if($phone == 9999988888)
        {
            $responseData = ['success' => '1', 'message' => 'Your OTP is 123456.'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
            http_response_code(200);
        }
        else
        {
            if($counte == 1)
            {
                $rantrk_no = rand(100000, 999999);

                $mobile_sql = "update trucks set trk_otp = '$rantrk_no', trk_dr_token = '$token' where trk_dr_phone = '$phone'";

                $mobile_insert = mysqli_query($link, $mobile_sql);
                
                if($mobile_insert)
                {
                    $api = '321698AFaajkjGny5f65b2b7P1';
                    $template = '5f995d9b6ddb9b27a7274a72';
                    
                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.msg91.com/api/v5/otp?authkey=$api&template_id=$template&mobile=91$phone&invisible=1&otp=$rantrk_no",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
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
                $responseData = ['success' => '0', 'message' => 'This number is not registered. Contact your truck owner.'];
                echo json_encode($responseData, JSON_PRETTY_PRINT);

                http_response_code(400);
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