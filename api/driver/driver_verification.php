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
            $get_cat = "select * from truck_cat where trk_cat_id = '".$otp_row['trk_cat']."'";
            $run_cat = mysqli_query($link, $get_cat);
            $row_cat = mysqli_fetch_array($run_cat, MYSQLI_ASSOC);

            $get_type = "select * from truck_cat_type where ty_id = '".$otp_row['trk_cat_type']."'";
            $run_type = mysqli_query($link, $get_type);
            $row_type = mysqli_fetch_array($run_type, MYSQLI_ASSOC);

            $responseData = ['success' => '1', 'message' => 'OTP verified. Logged in', 'id' => $otp_row['trk_id'], 'driver name' => $otp_row['trk_dr_name'], 
                            'phone country code' => $otp_row['trk_dr_phone_code'], 'driver phone' => $otp_row['trk_dr_phone'], 'driver pic' => $otp_row['trk_dr_pic'], 
                            'driver license' => $otp_row['trk_dr_license'], 'truck owner id' => $otp_row['trk_owner'], 'truck number' => $otp_row['trk_num'], 
                            'truck category' => $row_cat['trk_cat_name'], 'truck type' => $row_type['ty_name'], 'truck rc' => $otp_row['trk_rc'], 'truck insurance' => $otp_row['trk_insurance'], 
                            'truck road tax' => $otp_row['trk_road_tax'], 'truck rto passing' => $otp_row['trk_rto'], 'firebase token' => $otp_row['trk_dr_token']];
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
            $api = '321698AFaajkjGny5f65b2b7P1';
            $template = '5f995d9b6ddb9b27a7274a72';
            
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.msg91.com/api/v5/otp?authkey=$api&template_id=$template&mobile=91$phone&invisible=1&otp=$ranto_no",
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