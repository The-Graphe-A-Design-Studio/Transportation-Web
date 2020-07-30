<?php

    include('../../dbcon.php');

    header('Content-Type: application/json');

    if(isset($_POST['truck_owner_id']))
    {
        $sql = "select * from trucks where trk_owner = '".$_POST['truck_owner_id']."'";
        $result = mysqli_query($link, $sql) or die("Error in Selecting " . mysqli_error($link));

        //create an array
        $emparray = array();
        while($row = mysqli_fetch_assoc($result))
        {
            $emparray[] = $row;
        }
        
        echo json_encode($emparray, JSON_PRETTY_PRINT);

        http_response_code(200);
        
        mysqli_close($link);
    }
    // elseif(isset($_POST['cu_id']) && isset($_POST['order_id']))
    // {
    //     $d_sql = "update orders set order_status = '2' where order_id = '".$_POST['order_id']."'";
    //     $d_run = mysqli_query($link, $d_sql);
    //     if($d_run)
    //     {
    //         $responseData = ['success' => '1', 'message' => 'Order cancelled'];
    //         echo json_encode($responseData, JSON_PRETTY_PRINT);
    //     }
    //     else
    //     {
    //         $responseData = ['success' => '0', 'message' => 'Order cancel failed'];
    //         echo json_encode($responseData, JSON_PRETTY_PRINT);
    //     }
    // }
    elseif(isset($_POST['del_truck_id']))
    {
        $sql = "select * from trucks where trk_id = '".$_POST['del_truck_id']."'";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        $dirname = "../../assets/documents/truck_owners/truck_owners_id_".$row['trk_owner']."/".$row['trk_num'];

        array_map('unlink', glob("$dirname/*.*"));
        rmdir("$dirname");

        $d_sql = "delete from trucks where trk_id = '".$_POST['del_truck_id']."'";
        $d_run = mysqli_query($link, $d_sql);
        if($d_run)
        {
            $responseData = ['success' => '1', 'message' => 'Truck removed'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);

            http_response_code(200);
        }
        else
        {
            $responseData = ['success' => '0', 'message' => 'Failed to remove'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);

            http_response_code(400);
        }
    }
    elseif(isset($_POST['trk_owner']) && isset($_POST['trk_cat']) && isset($_POST['trk_num']) && isset($_POST['trk_load']) && isset($_POST['trk_dr_name']) && 
        isset($_POST['trk_dr_phone_code']) && isset($_POST['trk_dr_phone']))
    {
        $truck_owner = $_POST['trk_owner'];
        $truck_cat = $_POST['trk_cat'];
        $truck_num = $_POST['trk_num'];
        $truck_load = $_POST['trk_load'];
        $truck_driver_name = $_POST['trk_dr_name'];
        $truck_driver_phone_code = $_POST['trk_dr_phone_code'];
        $truck_driver_phone = $_POST['trk_dr_phone'];

        $sqle = "SELECT * FROM trucks where trk_num = '$truck_num'";
        $checke = mysqli_query($link, $sqle);
        $rowe = mysqli_fetch_array($checke, MYSQLI_ASSOC);
        $counte = mysqli_num_rows($checke);
        if($counte >= 1)
        {
            $responseData = ['success' => '0', 'message' => 'This Truck Number is already registered'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);

            http_response_code(400);
        }
        else
        {
            $trk_folder = "../../assets/documents/truck_owners/truck_owners_id_".$truck_owner;

            if(!is_dir($trk_folder))
            {
                mkdir("../../assets/documents/truck_owners/truck_owners_id_".$truck_owner);
            }
            
            $trk_num_folder = "../../assets/documents/truck_owners/truck_owners_id_".$truck_owner."/".$truck_num;

            if(!is_dir($trk_num_folder))
            {
                mkdir("../../assets/documents/truck_owners/truck_owners_id_".$truck_owner."/".$truck_num);
            }

            // Validate RC
            if(!empty($_FILES['trk_rc']))
            {
                $file_name = $_FILES['trk_rc']['name'];
                $file_size =$_FILES['trk_rc']['size'];
                $file_tmp =$_FILES['trk_rc']['tmp_name'];
                $file_type=$_FILES['trk_rc']['type'];
                
                if($file_size >= 200000)
                {
                    $responseData = ['success' => '0', 'message' => 'RC file size must be less than 200 kb'];
                    echo json_encode($responseData, JSON_PRETTY_PRINT);

                    http_response_code(400);
                }
                else
                {
                    $file_name = $_FILES["trk_rc"]["name"];
                    $rn = explode(".", $file_name);
                    $extension = end($rn);
                    $new_trk_rc = $truck_num."_rc.".$extension;
                    
                    move_uploaded_file($file_tmp,"../../assets/documents/truck_owners/truck_owners_id_".$truck_owner."/".$truck_num."/".$new_trk_rc);

                    // Validate License
                    if(!empty($_FILES['trk_dr_license']))
                    {
                        $file_name1 = $_FILES['trk_dr_license']['name'];
                        $file_size1 = $_FILES['trk_dr_license']['size'];
                        $file_tmp1 = $_FILES['trk_dr_license']['tmp_name'];
                        $file_type1 = $_FILES['trk_dr_license']['type'];
                        
                        
                        if($file_size1 >= 200000)
                        {
                            $responseData = ['success' => '0', 'message' => 'License file size must be less than 200 kb'];
                            echo json_encode($responseData, JSON_PRETTY_PRINT);

                            http_response_code(400);
                        }
                        else
                        {
                            $file_name1 = $_FILES["trk_dr_license"]["name"];
                            $rn1 = explode(".", $file_name1);
                            $extension1 = end($rn1);
                            $new_trk_dr_license = $truck_num."_license.".$extension1;

                            move_uploaded_file($file_tmp1,"../../assets/documents/truck_owners/truck_owners_id_".$truck_owner."/".$truck_num."/".$new_trk_dr_license);

                            // Validate Insurance
                            if(!empty($_FILES['trk_insurance']))
                            {
                                $file_name2 = $_FILES['trk_insurance']['name'];
                                $file_size2 = $_FILES['trk_insurance']['size'];
                                $file_tmp2 = $_FILES['trk_insurance']['tmp_name'];
                                $file_type2 = $_FILES['trk_insurance']['type'];
                                
                                
                                if($file_size2 >= 200000)
                                {
                                    $responseData = ['success' => '0', 'message' => 'Insurance file size must be less than 150 kb'];
                                    echo json_encode($responseData, JSON_PRETTY_PRINT);

                                    http_response_code(400);
                                }
                                else
                                {
                                    $file_name2 = $_FILES["trk_insurance"]["name"];
                                    $rn2 = explode(".", $file_name2);
                                    $extension2 = end($rn2);
                                    $new_trk_insurance = $truck_num."_insurance.".$extension2;

                                    move_uploaded_file($file_tmp2,"../../assets/documents/truck_owners/truck_owners_id_".$truck_owner."/".$truck_num."/".$new_trk_insurance);

                                    // Validate Road Tax
                                    if(!empty($_FILES['trk_road_tax']))
                                    {
                                        $file_namefp = $_FILES['trk_road_tax']['name'];
                                        $file_sizefp = $_FILES['trk_road_tax']['size'];
                                        $file_tmpfp = $_FILES['trk_road_tax']['tmp_name'];
                                        $file_typefp = $_FILES['trk_road_tax']['type'];
                                        
                                        
                                        if($file_sizefp >= 200000)
                                        {
                                            $responseData = ['success' => '0', 'message' => 'Road tax file size must be less than 200 kb'];
                                            echo json_encode($responseData, JSON_PRETTY_PRINT);

                                            http_response_code(400);
                                        }
                                        else
                                        {
                                            $file_namefp = $_FILES["trk_road_tax"]["name"];
                                            $rnfp = explode(".", $file_namefp);
                                            $extensionfp = end($rnfp);
                                            $new_trk_road_tax = $truck_num."_road_tax.".$extensionfp;

                                            move_uploaded_file($file_tmpfp,"../../assets/documents/truck_owners/truck_owners_id_".$truck_owner."/".$truck_num."/".$new_trk_road_tax);

                                            // Validate RTO
                                            if(!empty($_FILES['trk_rto']))
                                            {
                                                $file_namefp = $_FILES['trk_rto']['name'];
                                                $file_sizefp = $_FILES['trk_rto']['size'];
                                                $file_tmpfp = $_FILES['trk_rto']['tmp_name'];
                                                $file_typefp = $_FILES['trk_rto']['type'];
                                                
                                                
                                                if($file_sizefp >= 200000)
                                                {
                                                    $responseData = ['success' => '0', 'message' => 'RTO file size must be less than 200 kb'];
                                                    echo json_encode($responseData, JSON_PRETTY_PRINT);

                                                    http_response_code(400);
                                                }
                                                else
                                                {
                                                    $file_namefp = $_FILES["trk_rto"]["name"];
                                                    $rnfp = explode(".", $file_namefp);
                                                    $extensionfp = end($rnfp);
                                                    $new_trk_rto = $truck_num."_rto.".$extensionfp;

                                                    move_uploaded_file($file_tmpfp,"../../assets/documents/truck_owners/truck_owners_id_".$truck_owner."/".$truck_num."/".$new_trk_rto);

                                                    $trk_folder_loc = "assets/documents/truck_owners/truck_owners_id_".$truck_owner."/".$truck_num."/";

                                                    $rc = $trk_folder_loc.$new_trk_rc;
                                                    $license = $trk_folder_loc.$new_trk_dr_license;
                                                    $insurance = $trk_folder_loc.$new_trk_insurance;
                                                    $road_tax = $trk_folder_loc.$new_trk_road_tax;
                                                    $rto = $trk_folder_loc.$new_trk_rto;

                                                    $mobile_sql = "insert into trucks (trk_owner, trk_cat, trk_num, trk_load, trk_dr_name, trk_dr_phone_code, 
                                                                    trk_dr_phone, trk_dr_license, trk_rc, trk_insurance, trk_road_tax, trk_rto) values 
                                                                    ('$truck_owner', '$truck_cat', '$truck_num', '$truck_load', '$truck_driver_name', 
                                                                    '$truck_driver_phone_code', '$truck_driver_phone', '$rc', '$license', '$insurance', 
                                                                    '$road_tax', '$rto')";

                                                    $mobile_insert = mysqli_query($link, $mobile_sql);
                                                    
                                                    if($mobile_insert)
                                                    {
                                                        $responseData = ['success' => '1', 'message' => 'New Truck added'];
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
                                            else
                                            {
                                                $responseData = ['success' => '0', 'message' => 'RTO file is missing'];
                                                echo json_encode($responseData, JSON_PRETTY_PRINT);

                                                http_response_code(206);
                                            }
                                        }
                                    }
                                    else
                                    {
                                        $responseData = ['success' => '0', 'message' => 'Road Tax file is missing'];
                                        echo json_encode($responseData, JSON_PRETTY_PRINT);

                                        http_response_code(206);
                                    }
                                }
                            }
                            else
                            {
                                $responseData = ['success' => '0', 'message' => 'Insurance is missing'];
                                echo json_encode($responseData, JSON_PRETTY_PRINT);

                                http_response_code(206);
                            }
                        }
                    }
                    else
                    {
                        $responseData = ['success' => '0', 'message' => 'License is missing'];
                        echo json_encode($responseData, JSON_PRETTY_PRINT);

                        http_response_code(206);
                    }
                }
            }
            else
            {
                $responseData = ['success' => '0', 'message' => 'RC is missing'];
                echo json_encode($responseData, JSON_PRETTY_PRINT);

                http_response_code(206);
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