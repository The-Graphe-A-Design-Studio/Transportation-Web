<?php

    include('../../dbcon.php');

    header('Content-Type: application/json');

    if(isset($_POST['phone_number']) && isset($_POST['otp']))
    {
        $otp_sql = "select * from trucks where trk_dr_phone = '".$_POST['phone_number']."' and trk_otp = '".$_POST['otp']."'";
        $otp_run = mysqli_query($link, $otp_sql);
        $otp_row = mysqli_fetch_array($otp_run, MYSQLI_ASSOC);

        $t_id = $otp_row['trk_id'];

        if($otp_row['trk_otp'] === $_POST['otp'])
        {
            $responseData = ['success' => '1', 'message' => 'OTP verified. Logged in', 'id' => $otp_row['trk_id'], 'truck owner id' => $otp_row['trk_owner'],
                            'truck number' => $otp_row['trk_num'], 'driver name' => $otp_row['trk_dr_name'], 'phone country code' => $otp_row['trk_dr_phone_code'], 
                            'driver phone' => $otp_row['trk_dr_phone'], 'driver pic' => $otp_row['trk_dr_pic'], 'driver license' => $otp_row['trk_dr_license'], 
                            'truck rc' => $otp_row['trk_rc'], 'truck insurance' => $otp_row['trk_insurance'], 'truck road tax' => $otp_row['trk_road_tax'],
                            'truck rto passing' => $otp_row['trk_rto'], 'firebase token' => $otp_row['trk_dr_token']];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
            http_response_code(200);
        }
        else
        { 
            $responseData = ['success' => '0', 'message' => 'Wrong OTP'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
            http_response_code(400);
        }
    }
    elseif(isset($_POST['resend_otp_on']))
    {
        $ranto_no = rand(100000, 999999);

        $phone = $_POST['resend_otp_on'];

        $re_sql = "update trucks set trk_otp = '$ranto_no' where trk_dr_phone = '$phone'";
        $re_run = mysqli_query($link, $re_sql);
        
        if($re_run)
        {
            $api = '314319Asz8t1bwU0qU5e27d970P1';
            $msg = "Your new OTP is $ranto_no. Do not Share OTP. This OTP will expire in 20 minutes.";
            
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