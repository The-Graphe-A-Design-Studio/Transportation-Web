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
            if(!empty($_POST['to_name']) && !empty($_POST['to_phone_code']) && !empty($_POST['to_phone']))
            {
                // Prepare a select statement
                $sqlr = "SELECT * FROM customers where to_phone_code = '".$_POST['to_phone_code']."' and to_phone = '".$_POST['to_phone']."'";
                $checkr = mysqli_query($link, $sqlr);
                $rowr = mysqli_fetch_array($checkr, MYSQLI_ASSOC);
                $count = mysqli_num_rows($checkr);
                if($count >= 1)
                {
                    $responseData = ['success' => '0', 'message' => 'This Phone Number is already registered'];
                    echo json_encode($responseData, JSON_PRETTY_PRINT);
                }
                else
                {
                    $name_sql = "update customers set to_name = '".$_POST['to_name']."' where to_id = '".$_POST['to_id']."'";
                    $name_insert = mysqli_query($link, $name_sql);

                    if($name_insert)
                    {
                        // Prepare an insert statement
                        $rand_no = rand(100000, 999999);
                        $name = $_POST['to_name'];
                        $phone_code = $_POST['to_phone_code'];
                        $phone = $_POST['to_phone'];
                        $phone_w_code = $_POST['to_phone_code'].$_POST['to_phone'];

                        $mobile_sql = "INSERT INTO mobile_numbers (mobile_number, verification_code) VALUES ('$phone_w_code', '$rand_no')";
                        $mobile_insert = mysqli_query($link, $mobile_sql);
                        
                        if($mobile_insert)
                        {
                            
                            $curl = curl_init();

                            curl_setopt_array($curl, array(
                            CURLOPT_URL => "https://control.msg91.com/api/sendotp.php?authkey=314319Asz8t1bwU0qU5e27d970P1&mobile=$phone&message=Hello $name, Your verification code for new number is $rand_no.&sender=AATAWALA&country=$phone_code&otp=$rand_no",
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
                            
                            $responseData = ['success' => '1', 'message' => 'OTP sent to your given new number. Please verify your number'];
                            echo json_encode($responseData, JSON_PRETTY_PRINT);
                        } 
                        else
                        {
                            $responseData = ['success' => '0', 'message' => 'Something went wrong. Please try again'];
                            echo json_encode($responseData, JSON_PRETTY_PRINT);
                        }
                    }
                    else
                    {
                        $responseData = ['success' => '0', 'message' => 'Something went wrong. Please try again'];
                        echo json_encode($responseData, JSON_PRETTY_PRINT);
                    }
                }
            }
        }
    }
    elseif(isset($_POST['to_id']) && isset($_POST['phn_code']) && isset($_POST['phn']) && isset($_POST['otp']))
    {
        
        $phone = $_POST['phn_code'].$_POST['phn'];
        
        // Check input errors before inserting in database
        if(!empty($_POST['otp']))
        {
            
            // Prepare an insert statement
            $otp_sql = "select * from mobile_numbers where mobile_number = '$phone' and verification_code = '".$_POST['otp']."'";
            $otp_insert = mysqli_query($link, $otp_sql);
            $row = mysqli_fetch_array($otp_insert, MYSQLI_ASSOC);

            $mobile_id = $row['mobile_id'];
            
            if($row['verification_code'] === $_POST['otp'])
            {
                $verified_sql = "update mobile_numbers set verified = '1' where mobile_id = $mobile_id";
                $verified_insert = mysqli_query($link, $verified_sql);

                if($verified_insert)
                {
                    $up_c = "update customers set  to_phone_code = '".$_POST['phn_code']."', to_phone = '".$_POST['phn']."' where to_id = '".$_POST['to_id']."'";
                    $run_c = mysqli_query($link, $up_c);

                    if($run_c)
                    {
                        $responseData = ['success' => '1', 'message' => 'Number verified & Info updated'];
                        echo json_encode($responseData, JSON_PRETTY_PRINT);
                    }
                    else
                    {
                        $responseData = ['success' => '0', 'message' => 'Something went wrong. Please try again'];
                        echo json_encode($responseData, JSON_PRETTY_PRINT);
                    }
                }
                else
                {
                    $responseData = ['success' => '0', 'message' => 'Something went wrong. Please try again'];
                    echo json_encode($responseData, JSON_PRETTY_PRINT);
                }
            }
            else
            {
                $responseData = ['success' => '0', 'message' => 'Wrong OTP'];
                echo json_encode($responseData, JSON_PRETTY_PRINT);
            }
        }
        else
        {
            $responseData = ['success' => '0', 'message' => 'OTP is missing'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
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
         
            $ck_sql = "SELECT * FROM customers WHERE to_id = '".$_POST['to_id']."'";
            $result = mysqli_query($link, $ck_sql);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

            if($ento_password === $row['to_password'])
            {
                $ento_new_password = md5($new);

                $up_p = "update customers set to_password = '$ento_new_password' where to_id = '".$_POST['to_id']."'";
                $run_p = mysqli_query($link, $up_p);

                if($run_p)
                {
                    $responseData = ['success' => '1', 'message' => 'Password Updated. Login again'];
                    echo json_encode($responseData, JSON_PRETTY_PRINT);
                }
                else
                {
                    $responseData = ['success' => '0', 'message' => 'OTP is missing'];
                    echo json_encode($responseData, JSON_PRETTY_PRINT);
                }
            }
            else
            {
                $responseData = ['success' => '0', 'message' => 'Wrong password'];
                echo json_encode($responseData, JSON_PRETTY_PRINT);
            }
        }
        else
        {
            $responseData = ['success' => '0', 'message' => 'Confirm Password not matched with New Password'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
        }
    }
    else
    {
        $responseData = ['success' => '0', 'message' => 'Something went wrong'];
        echo json_encode($responseData, JSON_PRETTY_PRINT);
    }

?>