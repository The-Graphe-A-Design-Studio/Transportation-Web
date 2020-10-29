<?php

    include('../../dbcon.php');

    header('Content-Type: application/json');

    if(isset($_POST['phone_number']) && isset($_POST['otp']))
    {
        $otp_sql = "select * from truck_owners where to_phone = '".$_POST['phone_number']."' and to_otp = '".$_POST['otp']."'";
        $otp_run = mysqli_query($link, $otp_sql);
        $otp_row = mysqli_fetch_array($otp_run, MYSQLI_ASSOC);

        $t_id = $otp_row['to_id'];

        if($otp_row['to_otp'] === $_POST['otp'])
        {
            $active = "update truck_owners set to_active = 1 where to_phone = '".$_POST['phone_number']."' and to_otp = '".$_POST['otp']."'";
            $set = mysqli_query($link, $active);

            $comp = "select * from truck_owner_docs where to_doc_owner_phone = '".$_POST['phone_number']."' and to_doc_sr_num = 1";
            $comp_run = mysqli_query($link, $comp);
            $comp_row = mysqli_fetch_array($comp_run, MYSQLI_ASSOC);

            if($otp_row['to_account_on'] == 2)
            {
                $date_now = new DateTime(date('Y-m-d H:i:s'));
                $date2    = new DateTime(date_format(date_create($otp_row['to_subscription_expire_date']), 'Y-m-d H:i:s'));
                
                if($date_now > $date2)
                {
                    $subs = 'Subscription period expired';
                }
                else
                {
                    $subs = 'In subscription period';
                }

                $responseData = ['success' => '1', 
                                'message' => 'OTP verified. Logged in', 
                                'id' => $otp_row['to_id'], 
                                'phone country code' => $otp_row['to_phone_code'], 
                                'phone' => $otp_row['to_phone'], 
                                'name' => $otp_row['to_name'],
                                'bank account number' => $otp_row['to_bank'], 
                                'ifsc code' => $otp_row['to_ifsc'], 
                                'pan card' => $comp_row['to_doc_location'],
                                'verified' => $otp_row['to_verified'],
                                'registered on' => $otp_row['to_registered'],
                                'plan type' => $otp_row['to_account_on'], 
                                'period upto' => $otp_row['to_subscription_expire_date'], 
                                'period status' => $subs, 
                                'total truck' => $otp_row['to_truck_limit'],
                                'firebase token' => $otp_row['to_token']];
                echo json_encode($responseData, JSON_PRETTY_PRINT);
                http_response_code(200);
            }
            elseif($otp_row['to_account_on'] == 1)
            {
                $date_now = new DateTime(date('Y-m-d H:i:s'));
                $date2    = new DateTime(date_format(date_create($otp_row['to_trial_expire_date']), 'Y-m-d H:i:s'));
                
                if($date_now > $date2)
                {
                    $trial = 'Trial period expired';
                }
                else
                {
                    $trial = 'In trial period';
                }
                
                $responseData = ['success' => '1', 
                                'message' => 'OTP verified. Logged in', 
                                'id' => $otp_row['to_id'], 
                                'phone country code' => $otp_row['to_phone_code'], 
                                'phone' => $otp_row['to_phone'], 
                                'name' => $otp_row['to_name'],
                                'bank account number' => $otp_row['to_bank'], 
                                'ifsc code' => $otp_row['to_ifsc'], 
                                'pan card' => $comp_row['to_doc_location'],
                                'verified' => $otp_row['to_verified'],
                                'registered on' => $otp_row['to_registered'], 
                                'plan type' => $otp_row['to_account_on'],
                                'period upto' => $otp_row['to_trial_expire_date'], 
                                'period status' => $trial, 
                                'total truck' => $otp_row['to_truck_limit'],
                                'firebase token' => $otp_row['to_token']];
                echo json_encode($responseData, JSON_PRETTY_PRINT);
                http_response_code(200);
            }
            else
            {
                $responseData = ['success' => '1', 
                                'message' => 'OTP verified. Logged in', 
                                'id' => $otp_row['to_id'], 
                                'phone country code' => $otp_row['to_phone_code'], 
                                'phone' => $otp_row['to_phone'], 
                                'name' => $otp_row['to_name'],
                                'bank account number' => $otp_row['to_bank'], 
                                'ifsc code' => $otp_row['to_ifsc'], 
                                'pan card' => $comp_row['to_doc_location'],
                                'verified' => $otp_row['to_verified'],
                                'registered on' => $otp_row['to_registered'], 
                                'plan type' => $otp_row['to_account_on'],
                                'period upto' => '', 
                                'period status' => 'No Plan', 
                                'total truck' => $otp_row['to_truck_limit'],
                                'firebase token' => $otp_row['to_token']];
                echo json_encode($responseData, JSON_PRETTY_PRINT);
                http_response_code(200);
            }
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

        $re_sql = "update truck_owners set to_otp = '$ranto_no' where to_phone = '$phone'";
        $re_run = mysqli_query($link, $re_sql);
        
        if($re_run)
        {
            $api = '321698AFaajkjGny5f65b2b7P1';
            $template = '5f9a63b35e24ef5ca12cf8bb';
            
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