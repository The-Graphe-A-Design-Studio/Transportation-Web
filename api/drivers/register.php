<?php

    include('../../dbcon.php');

    header('Content-Type: application/json');

    if(isset($_POST['d_name']) && isset($_POST['d_email']) && isset($_POST['d_phone_code']) && isset($_POST['d_phone']) && isset($_POST['d_password']) && isset($_POST['d_cnf_password'])
        && isset($_POST['d_address']) && isset($_POST['d_pan']) && isset($_POST['d_bank']) && isset($_POST['d_ifsc']))
    {
        $name = $_POST['d_name'];
        $email = $_POST['d_email'];
        $phone_code = $_POST['d_phone_code'];
        $phone = $_POST['d_phone'];
        $phone_w_code = $phone_code.$phone;
        $pass = $_POST['d_password'];
        $cnf_pass = $_POST['d_cnf_password'];
        $address = $_POST['d_address'];
        $address = mysqli_real_escape_string($link, $address);
        $pan = $_POST['d_pan'];
        $bank = $_POST['d_bank'];
        $ifsc = $_POST['d_ifsc'];

        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $responseData = ['success' => '0', 'message' => 'Invalid email format'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
        }
        else
        {
            $sqle = "SELECT * FROM drivers where d_email = '$email'";
            $checke = mysqli_query($link, $sqle);
            $rowe = mysqli_fetch_array($checke, MYSQLI_ASSOC);
            $counte = mysqli_num_rows($checke);
            if($counte >= 1)
            {
                $responseData = ['success' => '0', 'message' => 'This email id is already registered'];
                echo json_encode($responseData, JSON_PRETTY_PRINT);
            }
            else
            {
        
                $sqlr = "SELECT * FROM drivers where d_phone_code = '$phone_code' and d_phone = '$phone'";
                $checkr = mysqli_query($link, $sqlr);
                $rowr = mysqli_fetch_array($checkr, MYSQLI_ASSOC);
                $count = mysqli_num_rows($checkr);
                if($count >= 1)
                {
                    $responseData = ['success' => '0', 'message' => 'This Phone number is already registered'];
                    echo json_encode($responseData, JSON_PRETTY_PRINT);
                }
                else
                {
                    if($pass === $cnf_pass)
                    {
                        $d_folder = "../../assets/documents/drivers/".$phone;

                        if(!is_dir($d_folder))
                        {
                            mkdir("../../assets/documents/drivers/".$phone);
                        }

                        // Validate RC
                        if(isset($_FILES['d_rc']))
                        {
                            $file_name = $_FILES['d_rc']['name'];
                            $file_size =$_FILES['d_rc']['size'];
                            $file_tmp =$_FILES['d_rc']['tmp_name'];
                            $file_type=$_FILES['d_rc']['type'];
                            
                            if($file_size >= 200000)
                            {
                                $responseData = ['success' => '0', 'message' => 'RC file size must be less than 200 kb'];
                                echo json_encode($responseData, JSON_PRETTY_PRINT);
                            }
                            else
                            {
                                $file_name = $_FILES["d_rc"]["name"];
                                $rn = explode(".", $file_name);
                                $extension = end($rn);
                                $new_d_rc = $phone."_rc.".$extension;
                                
                                move_uploaded_file($file_tmp,"../../assets/documents/drivers/".$phone."/".$new_d_rc);

                                // Validate License
                                if(isset($_FILES['d_license']))
                                {
                                    $file_name1 = $_FILES['d_license']['name'];
                                    $file_size1 = $_FILES['d_license']['size'];
                                    $file_tmp1 = $_FILES['d_license']['tmp_name'];
                                    $file_type1 = $_FILES['d_license']['type'];
                                    
                                    
                                    if($file_size1 >= 200000)
                                    {
                                        $responseData = ['success' => '0', 'message' => 'License file size must be less than 200 kb'];
                                        echo json_encode($responseData, JSON_PRETTY_PRINT);
                                    }
                                    else
                                    {
                                        $file_name1 = $_FILES["d_license"]["name"];
                                        $rn1 = explode(".", $file_name1);
                                        $extension1 = end($rn1);
                                        $new_d_license = $phone."_license.".$extension1;

                                        move_uploaded_file($file_tmp1,"../../assets/documents/drivers/".$phone."/".$new_d_license);

                                        // Validate Insurance
                                        if(isset($_FILES['d_insurance']))
                                        {
                                            $file_name2 = $_FILES['d_insurance']['name'];
                                            $file_size2 = $_FILES['d_insurance']['size'];
                                            $file_tmp2 = $_FILES['d_insurance']['tmp_name'];
                                            $file_type2 = $_FILES['d_insurance']['type'];
                                            
                                            
                                            if($file_size2 >= 200000)
                                            {
                                                $responseData = ['success' => '0', 'message' => 'Insurance file size must be less than 150 kb'];
                                                echo json_encode($responseData, JSON_PRETTY_PRINT);
                                            }
                                            else
                                            {
                                                $file_name2 = $_FILES["d_insurance"]["name"];
                                                $rn2 = explode(".", $file_name2);
                                                $extension2 = end($rn2);
                                                $new_d_insurance = $phone."_insurance.".$extension2;

                                                move_uploaded_file($file_tmp2,"../../assets/documents/drivers/".$phone."/".$new_d_insurance);

                                                // Validate Road Tax
                                                if(isset($_FILES['d_road_tax']))
                                                {
                                                    $file_namefp = $_FILES['d_road_tax']['name'];
                                                    $file_sizefp = $_FILES['d_road_tax']['size'];
                                                    $file_tmpfp = $_FILES['d_road_tax']['tmp_name'];
                                                    $file_typefp = $_FILES['d_road_tax']['type'];
                                                    
                                                    
                                                    if($file_sizefp >= 200000)
                                                    {
                                                        $responseData = ['success' => '0', 'message' => 'Road tax file size must be less than 200 kb'];
                                                        echo json_encode($responseData, JSON_PRETTY_PRINT);
                                                    }
                                                    else
                                                    {
                                                        $file_namefp = $_FILES["d_road_tax"]["name"];
                                                        $rnfp = explode(".", $file_namefp);
                                                        $extensionfp = end($rnfp);
                                                        $new_d_road_tax = $phone."_road_tax.".$extensionfp;

                                                        move_uploaded_file($file_tmpfp,"../../assets/documents/drivers/".$phone."/".$new_d_road_tax);

                                                        // Validate RTO
                                                        if(isset($_FILES['d_rto']))
                                                        {
                                                            $file_namefp = $_FILES['d_rto']['name'];
                                                            $file_sizefp = $_FILES['d_rto']['size'];
                                                            $file_tmpfp = $_FILES['d_rto']['tmp_name'];
                                                            $file_typefp = $_FILES['d_rto']['type'];
                                                            
                                                            
                                                            if($file_sizefp >= 200000)
                                                            {
                                                                $responseData = ['success' => '0', 'message' => 'RTO file size must be less than 200 kb'];
                                                                echo json_encode($responseData, JSON_PRETTY_PRINT);
                                                            }
                                                            else
                                                            {
                                                                $file_namefp = $_FILES["d_rto"]["name"];
                                                                $rnfp = explode(".", $file_namefp);
                                                                $extensionfp = end($rnfp);
                                                                $new_d_rto = $phone."_rto.".$extensionfp;

                                                                move_uploaded_file($file_tmpfp,"../../assets/documents/drivers/".$phone."/".$new_d_rto);

                                                                $rand_no = rand(100000, 999999);

                                                                $enc_p = md5($pass);

                                                                $d_folder_loc = "assets/documents/drivers/".$phone."/";

                                                                $rc = $d_folder_loc.$new_d_rc;
                                                                $license = $d_folder_loc.$new_d_license;
                                                                $insurance = $d_folder_loc.$new_d_insurance;
                                                                $road_tax = $d_folder_loc.$new_d_road_tax;
                                                                $rto = $d_folder_loc.$new_d_rto;

                                                                $mobile_sql = "insert into temp_register (t_d_name, t_d_email, t_d_phone_code, t_d_phone, t_d_password, t_d_address, t_d_rc, t_d_license, t_d_insurance, 
                                                                                t_d_road_tax, t_d_rto, t_d_pan, t_d_bank, t_d_ifsc, t_otp) values ('$name', '$email', '$phone_code', '$phone', '$enc_p', '$address', '$rc',
                                                                                '$license', '$insurance', '$road_tax', '$rto', '$pan', '$bank', '$ifsc', '$rand_no')";

                                                                $mobile_insert = mysqli_query($link, $mobile_sql);
                                                                
                                                                if($mobile_insert)
                                                                {
                                                                    $api = '314319Asz8t1bwU0qU5e27d970P1';
                                                                    $msg = "Your OTP for Driver registration is $rand_no. This OTP will expire in 20 minutes.";
                                                                    
                                                                    $curl = curl_init();

                                                                    curl_setopt_array($curl, array(
                                                                    CURLOPT_URL => "http://control.msg91.com/api/v5/otp?authkey=$api&mobiles=$phone&message=$msg&sender=TRNSPT&country=$phone_code&otp=$rand_no",
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
                                                                } 
                                                                else
                                                                {
                                                                    $responseData = ['success' => '0', 'message' => 'Something went wrong. Error'];
                                                                    echo json_encode($responseData, JSON_PRETTY_PRINT);
                                                                }
                                                            }
                                                        }
                                                        else
                                                        {
                                                            $responseData = ['success' => '0', 'message' => 'RTO file is missing'];
                                                            echo json_encode($responseData, JSON_PRETTY_PRINT);
                                                        }
                                                    }
                                                }
                                                else
                                                {
                                                    $responseData = ['success' => '0', 'message' => 'Road Tax file is missing'];
                                                    echo json_encode($responseData, JSON_PRETTY_PRINT);
                                                }
                                            }
                                        }
                                        else
                                        {
                                            $responseData = ['success' => '0', 'message' => 'Insurance is missing'];
                                            echo json_encode($responseData, JSON_PRETTY_PRINT);
                                        }
                                    }
                                }
                                else
                                {
                                    $responseData = ['success' => '0', 'message' => 'License is missing'];
                                    echo json_encode($responseData, JSON_PRETTY_PRINT);
                                }
                            }
                        }
                        else
                        {
                            $responseData = ['success' => '0', 'message' => 'RC is missing'];
                            echo json_encode($responseData, JSON_PRETTY_PRINT);
                        }
                    }
                    else
                    {
                        $responseData = ['success' => '0', 'message' => 'Both password must be same'];
                        echo json_encode($responseData, JSON_PRETTY_PRINT);
                    }
                }
            }
        }
    }
    elseif(isset($_POST['phone_number']) && isset($_POST['otp']))
    {
        $otp_sql = "select * from temp_register where t_d_phone = '".$_POST['phone_number']."' and t_otp = '".$_POST['otp']."' and t_verified = '0'";
        $otp_run = mysqli_query($link, $otp_sql);
        $otp_row = mysqli_fetch_array($otp_run, MYSQLI_ASSOC);

        $t_id = $otp_row['t_id'];

        if($otp_row['t_otp'] === $_POST['otp'])
        {
            $verified_sql = "update temp_register set t_verified = '1' where t_id = '$t_id'";
            $verified_insert = mysqli_query($link, $verified_sql);

            if($verified_insert)
            {
                $copy_sql = "select * from temp_register where t_id = '$t_id' and t_d_phone = '".$_POST['phone_number']."' and t_otp = '".$_POST['otp']."' and t_verified = '1'";
                $copy_run = mysqli_query($link, $copy_sql);
                $copy_row = mysqli_fetch_array($copy_run, MYSQLI_ASSOC);

                $address = mysqli_real_escape_string($link, $copy_row['t_d_address']);

                $cust_sql = "INSERT INTO drivers (d_name, d_email, d_phone_code, d_phone, d_password, d_address, d_rc, d_license, d_insurance, 
                            d_road_tax, d_rto, d_pan, d_bank, d_ifsc) VALUES ('".$copy_row['t_d_name']."', '".$copy_row['t_d_email']."', '".$copy_row['t_d_phone_code']."', 
                            '".$copy_row['t_d_phone']."', '".$copy_row['t_d_password']."', '$address',
                            '".$copy_row['t_d_rc']."', '".$copy_row['t_d_license']."', '".$copy_row['t_d_insurance']."', 
                            '".$copy_row['t_d_road_tax']."', '".$copy_row['t_d_rto']."', '".$copy_row['t_d_pan']."', '".$copy_row['t_d_bank']."', 
                            '".$copy_row['t_d_ifsc']."')";
                $cust_insert = mysqli_query($link, $cust_sql);

                if($cust_insert)
                {
                    $del_sql = "delete from temp_register where t_id = '$t_id'";
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
        $rand_no = rand(100000, 999999);

        $phone = $_POST['resend_otp'];

        $re_sql = "update temp_register set t_otp = '$rand_no' where t_d_phone = '$phone' and t_verified = '0'";
        $re_run = mysqli_query($link, $re_sql);
        
        if($re_run)
        {
            $api = '314319Asz8t1bwU0qU5e27d970P1';
            $msg = "Your new OTP for Driver registration is $rand_no. This OTP will expire in 20 minutes.";
            
            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => "http://control.msg91.com/api/v5/otp?authkey=$api&mobiles=$phone&message=$msg&sender=TRNSPT&country=91&otp=$rand_no",
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