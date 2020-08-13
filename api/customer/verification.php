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
    elseif(isset($_POST['cu_id']) && isset($_POST['edit_phone_number']) && isset($_POST['edit_otp']))
    {
        $otp_sql = "select * from temp_register1 where cu_phone = '".$_POST['edit_phone_number']."' and t_otp = '".$_POST['edit_otp']."' and t_reg_user = '1' and t_verified = '0'";
        $otp_run = mysqli_query($link, $otp_sql);
        $otp_row = mysqli_fetch_array($otp_run, MYSQLI_ASSOC);

        $t_id = $otp_row['t_id'];

        if($otp_row['t_otp'] === $_POST['edit_otp'])
        {
            $verifiecu_sql = "update temp_register1 set t_verified = '1' where t_id = '$t_id'";
            $verifiecu_insert = mysqli_query($link, $verifiecu_sql);

            if($verifiecu_insert)
            {
                $copy_sql = "select * from temp_register1 where t_id = '$t_id' and cu_phone = '".$_POST['edit_phone_number']."' and t_otp = '".$_POST['edit_otp']."' and t_reg_user = '1'
                                and t_verified = '1'";
                $copy_run = mysqli_query($link, $copy_sql);
                $copy_row = mysqli_fetch_array($copy_run, MYSQLI_ASSOC);

                $address = mysqli_real_escape_string($link, $copy_row['cu_address']);

                $cust_sql = "update customers set cu_name = '".$copy_row['cu_name']."', cu_email = '".$copy_row['cu_email']."', cu_phone_code = '".$copy_row['cu_phone_code']."', 
                            cu_phone = '".$copy_row['cu_phone']."', cu_city = '".$copy_row['cu_city']."', cu_address = '".$copy_row['cu_address']."', 
                            cu_pin_code = '".$copy_row['cu_pin_code']."', cu_pan_num = '".$copy_row['cu_pan_num']."', cu_pan_num = '".$copy_row['cu_pan_num']."', 
                            cu_co_name = '".$copy_row['cu_co_name']."', cu_co_type = '".$copy_row['cu_co_type']."', cu_co_pan_num = '".$copy_row['cu_co_pan_num']."', 
                            cu_co_website = '".$copy_row['cu_co_website']."' where cu_id = '".$_POST['cu_id']."'";

                $cust_insert = mysqli_query($link, $cust_sql);

                if($cust_insert)
                {
                    $del_sql = "delete from temp_register1 where t_id = '".$copy_row['t_id']."'";
                    $del_run = mysqli_query($link, $del_sql);

                    $responseData = ['success' => '1', 'message' => 'OTP verified. Profile info updated'];
                    echo json_encode($responseData, JSON_PRETTY_PRINT);

                    http_response_code(200);
                }
                else
                {
                    $responseData = ['success' => '0', 'message' => 'Something went wrong'];
                    echo json_encode($responseData, JSON_PRETTY_PRINT);

                    http_response_code(400);
                }
            }
            else
            {
                $responseData = ['success' => '0', 'message' => 'Server error. Try again'];
                echo json_encode($responseData, JSON_PRETTY_PRINT);

                http_response_code(500);
            }
        }
        else
        {
            $responseData = ['success' => '0', 'message' => 'Wrong OTP'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);

            http_response_code(400);
        }
    }
    elseif(isset($_POST['edit_resend_otp']))
    {
        $rancu_no = rand(100000, 999999);

        $phone = $_POST['edit_resend_otp'];

        $re_sql = "update temp_register1 set t_otp = '$rancu_no' where cu_phone = '$phone' and t_reg_user = '1' and t_verified = '0'";
        $re_run = mysqli_query($link, $re_sql);
        
        if($re_run)
        {
            $api = '314319Asz8t1bwU0qU5e27d970P1';
            $msg = "Your new OTP for changing your Phone number is $rancu_no. This OTP will expire in 20 minutes.";
            
            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => "http://control.msg91.com/api/v5/otp?authkey=$api&mobiles=$phone&message=$msg&sender=TRNSPT&country=91&otp=$rancu_no",
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

            http_response_code(200);
        } 
        else
        {
            $responseData = ['success' => '0', 'message' => 'Something went wrong'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);

            http_response_code(200);
        }
    }
    elseif(isset($_POST['cu_id']) && isset($_POST['curr_password']) && isset($_POST['new_password']) && isset($_POST['cnf_new_password']))
    {
        $curr = $_POST['curr_password'];
        $new = $_POST['new_password'];
        $cnf_new = $_POST['cnf_new_password'];

        if($cnf_new === $new)
        {
            $encu_password = md5($curr);
         
            $ck_sql = "SELECT * FROM customers WHERE cu_id = '".$_POST['cu_id']."'";
            $result = mysqli_query($link, $ck_sql);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

            if($encu_password === $row['cu_password'])
            {
                $encu_new_password = md5($new);

                $up_p = "update customers set cu_password = '$encu_new_password' where cu_id = '".$_POST['cu_id']."'";
                $run_p = mysqli_query($link, $up_p);

                if($run_p)
                {
                    $responseData = ['success' => '1', 'message' => 'Password Updated. Login again'];
                    echo json_encode($responseData, JSON_PRETTY_PRINT);

                    http_response_code(200);
                }
                else
                {
                    $responseData = ['success' => '0', 'message' => 'Something went wrong'];
                    echo json_encode($responseData, JSON_PRETTY_PRINT);

                    http_response_code(400);
                }
            }
            else
            {
                $responseData = ['success' => '0', 'message' => 'Wrong password'];
                echo json_encode($responseData, JSON_PRETTY_PRINT);

                http_response_code(400);
            }
        }
        else
        {
            $responseData = ['success' => '0', 'message' => 'Confirm Password does not match New Password'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);

            http_response_code(400);
        }
    }
    else
    {
        $responseData = ['success' => '0', 'message' => 'Something is missing'];
        echo json_encode($responseData, JSON_PRETTY_PRINT);
    }

?>