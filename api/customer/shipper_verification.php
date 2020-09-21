<?php

    include('../../dbcon.php');

    header('Content-Type: application/json');

    if(isset($_POST['phone_number']) && isset($_POST['otp']))
    {
        $otp_sql = "select * from customers where cu_phone = '".$_POST['phone_number']."' and cu_otp = '".$_POST['otp']."'";
        $otp_run = mysqli_query($link, $otp_sql);
        $otp_row = mysqli_fetch_array($otp_run, MYSQLI_ASSOC);

        $t_id = $otp_row['cu_id'];

        if($otp_row['cu_otp'] === $_POST['otp'])
        {
            $active = "update customers set cu_active = 1 where cu_phone = '".$_POST['phone_number']."' and cu_otp = '".$_POST['otp']."'";
            $set = mysqli_query($link, $active);

            if($otp_row['cu_account_on'] == 1)
            {
                $date_now = new DateTime(date('Y-m-d H:i:s'));
                $date2    = new DateTime(date_format(date_create($otp_row['cu_trial_expire_date']), 'Y-m-d H:i:s'));
                
                if($date_now > $date2)
                {
                    $trial = 'Trial period expired';
                }
                else
                {
                    $trial = 'In trial period';
                }

                $comp = "select * from customer_docs where doc_owner_phone = '".$_POST['phone_number']."' and doc_sr_num = 5";
                $comp_run = mysqli_query($link, $comp);
                $comp_row = mysqli_fetch_array($comp_run, MYSQLI_ASSOC);

                $responseData = ['success' => '1', 'message' => 'OTP verified. Logged in', 'shipper id' => $otp_row['cu_id'], 'shipper phone country code' => $otp_row['cu_phone_code'], 
                                'shipper phone' => $otp_row['cu_phone'], 'shipper company name' => $comp_row['doc_location'], 'verified' => $otp_row['cu_verified'], 
                                'registered on' => $otp_row['cu_registered'], 'trial period upto' => $otp_row['cu_trial_expire_date'], 'trial period status' => $trial, 'firebase token' => $otp_row['cu_token']];
                echo json_encode($responseData, JSON_PRETTY_PRINT);
                http_response_code(200);
            }
            elseif($otp_row['cu_account_on'] == 2)
            {
                $date_now = new DateTime(date('Y-m-d H:i:s'));
                $date2    = new DateTime(date_format(date_create($otp_row['cu_subscription_expire_date']), 'Y-m-d H:i:s'));
                
                if($date_now > $date2)
                {
                    $trial = 'Subscription period expired';
                }
                else
                {
                    $trial = 'In subscription period';
                }

                $comp = "select * from customer_docs where doc_owner_phone = '".$_POST['phone_number']."' and doc_sr_num = 5";
                $comp_run = mysqli_query($link, $comp);
                $comp_row = mysqli_fetch_array($comp_run, MYSQLI_ASSOC);

                $responseData = ['success' => '1', 'message' => 'OTP verified. Logged in', 'shipper id' => $otp_row['cu_id'], 'shipper phone country code' => $otp_row['cu_phone_code'], 
                                'shipper phone' => $otp_row['cu_phone'], 'shipper company name' => $comp_row['doc_location'], 'verified' => $otp_row['cu_verified'], 
                                'registered on' => $otp_row['cu_registered'], 'subscription period upto' => $otp_row['cu_subscription_expire_date'], 'subscription period status' => $trial, 'firebase token' => $otp_row['cu_token']];
                echo json_encode($responseData, JSON_PRETTY_PRINT);
                http_response_code(200);
            }
            else
            {
                $trial = 'Not on subcsription';
                
                $comp = "select * from customer_docs where doc_owner_phone = '".$_POST['phone_number']."' and doc_sr_num = 5";
                $comp_run = mysqli_query($link, $comp);
                $comp_row = mysqli_fetch_array($comp_run, MYSQLI_ASSOC);

                $responseData = ['success' => '1', 'message' => 'OTP verified. Logged in', 'shipper id' => $otp_row['cu_id'], 'shipper phone country code' => $otp_row['cu_phone_code'], 
                                'shipper phone' => $otp_row['cu_phone'], 'shipper company name' => $comp_row['doc_location'], 'verified' => $otp_row['cu_verified'], 
                                'registered on' => $otp_row['cu_registered'], 'subscription period upto' => $trial, 'subscription period status' => $trial, 'firebase token' => $otp_row['cu_token']];
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

        $re_sql = "update customers set cu_otp = '$ranto_no' where cu_phone = '$phone'";
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