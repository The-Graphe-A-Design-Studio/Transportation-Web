<?php

    include('../../dbcon.php');

    header('Content-Type: application/json');
    
    if(isset($_POST['to_id']) && isset($_POST['to_name']) && isset($_POST['to_email']) && isset($_POST['to_phone_code']) && isset($_POST['to_phone']) && 
        isset($_POST['to_city']) && isset($_POST['to_address']) && isset($_POST['to_operating_routes']) && isset($_POST['to_state_permits']) && 
        isset($_POST['to_pan']) && isset($_POST['to_bank']) && isset($_POST['to_ifsc']))
    {
        $id = $_POST['to_id'];
        $name = $_POST['to_name'];
        $email = $_POST['to_email'];
        $phone_code = $_POST['to_phone_code'];
        $phone = $_POST['to_phone'];
        $phone_w_code = $phone_code.$phone;
        $city = $_POST['to_city'];
        $address = $_POST['to_address'];
        $address = mysqli_real_escape_string($link, $address);
        $routes = $_POST['to_operating_routes'];
        $permits = $_POST['to_state_permits'];
        $pan = $_POST['to_pan'];
        $bank = $_POST['to_bank'];
        $ifsc = $_POST['to_ifsc'];
        
        $re_c = "select * from truck_owners where to_id = '".$_POST['to_id']."'";
        $re_r = mysqli_query($link, $re_c);
        $row_c = mysqli_fetch_array($re_r, MYSQLI_ASSOC);

        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $responseData = ['success' => '0', 'message' => 'Invalid email format'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);

            http_response_code(400);
        }
        else
        {
            if($_POST['to_phone_code'] == $row_c['to_phone_code'] && $_POST['to_phone'] == $row_c['to_phone'])
            {
                $up_c = "update truck_owners set to_name = '".$_POST['to_name']."', to_email = '".$_POST['to_email']."', to_phone_code = '".$_POST['to_phone_code']."', 
                        to_phone = '".$_POST['to_phone']."', to_city = '".$_POST['to_city']."', to_address = '".$_POST['to_address']."', 
                        to_routes = '".$_POST['to_operating_routes']."', to_permits = '".$_POST['to_state_permits']."', to_pan = '".$_POST['to_pan']."', 
                        to_bank = '".$_POST['to_bank']."', to_ifsc = '".$_POST['to_ifsc']."' where to_id = '".$_POST['to_id']."'";

                $run_c = mysqli_query($link, $up_c);

                if($run_c)
                {
                    $responseData = ['success' => '1', 'message' => 'Profile info updated'];
                    echo json_encode($responseData, JSON_PRETTY_PRINT);

                    http_response_code(202);
                }
                else
                {
                    $responseData = ['success' => '0', 'message' => 'Profile info update failed'];
                    echo json_encode($responseData, JSON_PRETTY_PRINT);

                    http_response_code(400);
                }
            }
            else
            {
                $ranto_no = rand(100000, 999999);

                $mobile_sql = "insert into temp_register (t_to_name, t_to_email, t_to_phone_code, t_to_phone, t_to_city, t_to_address, t_to_routes, t_to_permits,
                                t_to_pan, t_to_bank, t_to_ifsc, t_otp, t_reg_user) values ('$name', '$email', '$phone_code', '$phone', '$city', '$address', '$routes',
                                '$permits', '$pan', '$bank', '$ifsc', '$ranto_no', '1')";

                $mobile_insert = mysqli_query($link, $mobile_sql);
                
                if($mobile_insert)
                {
                    $api = '314319Asz8t1bwU0qU5e27d970P1';
                    $msg = "Your OTP for changing your Phone number is $ranto_no. This OTP will expire in 20 minutes.";
                    
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
        }
    }
    elseif(isset($_POST['to_id']) && isset($_POST['phone_number']) && isset($_POST['otp']))
    {
        $otp_sql = "select * from temp_register where t_to_phone = '".$_POST['phone_number']."' and t_otp = '".$_POST['otp']."' and t_reg_user = '1' and t_verified = '0'";
        $otp_run = mysqli_query($link, $otp_sql);
        $otp_row = mysqli_fetch_array($otp_run, MYSQLI_ASSOC);

        $t_id = $otp_row['t_id'];

        if($otp_row['t_otp'] === $_POST['otp'])
        {
            $verifieto_sql = "update temp_register set t_verified = '1' where t_id = '$t_id'";
            $verifieto_insert = mysqli_query($link, $verifieto_sql);

            if($verifieto_insert)
            {
                $copy_sql = "select * from temp_register where t_id = '$t_id' and t_to_phone = '".$_POST['phone_number']."' and t_otp = '".$_POST['otp']."' and t_reg_user = '1' and t_verified = '1'";
                $copy_run = mysqli_query($link, $copy_sql);
                $copy_row = mysqli_fetch_array($copy_run, MYSQLI_ASSOC);

                $address = mysqli_real_escape_string($link, $copy_row['t_to_address']);

                $cust_sql = "update truck_owners set to_name = '".$copy_row['t_to_name']."', to_email = '".$copy_row['t_to_email']."', to_phone_code = '".$copy_row['t_to_phone_code']."', 
                            to_phone = '".$copy_row['t_to_phone']."', to_city = '".$copy_row['t_to_city']."', to_address = '".$copy_row['t_to_address']."', 
                            to_routes = '".$copy_row['t_to_routes']."', to_permits = '".$copy_row['t_to_permits']."', to_pan = '".$copy_row['t_to_pan']."', 
                            to_bank = '".$copy_row['t_to_bank']."', to_ifsc = '".$copy_row['t_to_ifsc']."' where to_id = '".$_POST['to_id']."'";

                $cust_insert = mysqli_query($link, $cust_sql);

                if($cust_insert)
                {
                    $del_sql = "delete from temp_register where t_id = '".$copy_row['t_id']."'";
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
    elseif(isset($_POST['resend_otp']))
    {
        $ranto_no = rand(100000, 999999);

        $phone = $_POST['resend_otp'];

        $re_sql = "update temp_register set t_otp = '$ranto_no' where t_to_phone = '$phone' and t_reg_user = '1' and t_verified = '0'";
        $re_run = mysqli_query($link, $re_sql);
        
        if($re_run)
        {
            $api = '314319Asz8t1bwU0qU5e27d970P1';
            $msg = "Your new OTP for changing your Phone number is $ranto_no. This OTP will expire in 20 minutes.";
            
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

            http_response_code(200);
        } 
        else
        {
            $responseData = ['success' => '0', 'message' => 'Something went wrong'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);

            http_response_code(200);
        }
    }
    elseif(isset($_POST['to_id']) && isset($_POST['curr_password']) && isset($_POST['new_password']) && isset($_POST['cnf_new_password']))
    {
        $curr = $_POST['curr_password'];
        $new = $_POST['new_password'];
        $cnf_new = $_POST['cnf_new_password'];

        if($cnf_new === $new)
        {
            $ento_password = md5($curr);
         
            $ck_sql = "SELECT * FROM truck_owners WHERE to_id = '".$_POST['to_id']."'";
            $result = mysqli_query($link, $ck_sql);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

            if($ento_password === $row['to_password'])
            {
                $ento_new_password = md5($new);

                $up_p = "update truck_owners set to_password = '$ento_new_password' where to_id = '".$_POST['to_id']."'";
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
        $responseData = ['success' => '0', 'message' => 'Something went wrong'];
        echo json_encode($responseData, JSON_PRETTY_PRINT);

        http_response_code(400);
    }

?>