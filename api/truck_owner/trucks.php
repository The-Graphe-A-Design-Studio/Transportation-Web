<?php

    include('../../dbcon.php');
    include('../../FCM/notification.php');

    header('Content-Type: application/json');

    //view all trucks
    if(isset($_POST['truck_owner_id']))
    {
        $sql = "select * from trucks where trk_owner = '".$_POST['truck_owner_id']."'";
        $result = mysqli_query($link, $sql) or die("Error in Selecting " . mysqli_error($link));

        //create an array
        $emparray = array();
        while($row = mysqli_fetch_assoc($result))
        {
            $owner = "select * from truck_owners where to_id = '".$row['trk_owner']."'";
            $g_owner = mysqli_query($link, $owner);
            $row_owner = mysqli_fetch_array($g_owner, MYSQLI_ASSOC);

            $cat = "select * from truck_cat where trk_cat_id = '".$row['trk_cat']."'";
            $g_cat = mysqli_query($link, $cat);
            $row_cat = mysqli_fetch_array($g_cat, MYSQLI_ASSOC);

            $type = "select * from truck_cat_type where ty_id = '".$row['trk_cat_type']."'";
            $g_type = mysqli_query($link, $type);
            $row_type = mysqli_fetch_array($g_type, MYSQLI_ASSOC);

            $responseData[] = ["trk_id" => $row['trk_id'],
                            "trk_owner" => $row['trk_owner'],
                            "trk_cat" => $row_cat['trk_cat_name'],
                            "trk_cat_type" => $row_type['ty_name'],
                            "trk_num" => $row['trk_num'],
                            "trk_dr_name" => $row['trk_dr_name'],
                            "trk_dr_phone_code" => $row['trk_dr_phone_code'],
                            "trk_dr_phone" => $row['trk_dr_phone'],
                            "trk_otp" => $row['trk_otp'],
                            "trk_verified" =>$row['trk_verified'],
                            "trk_lat" => $row['trk_lat'],
                            "trk_lng" => $row['trk_lng'],
                            "trk_active" => $row['trk_active'],
                            "trk_on_trip" => $row['trk_on_trip'],
                            "trk_dr_token" => $row['trk_dr_token'],
                            "trk_on" => $row['trk_on']
                            ];
        }
        
        echo json_encode($responseData, JSON_PRETTY_PRINT);

        http_response_code(200);
        
        mysqli_close($link);
    }

    //add truck
    elseif(isset($_POST['trk_owner']) && isset($_POST['trk_cat']) && isset($_POST['trk_cat_type']) && isset($_POST['trk_num']) && isset($_POST['trk_dr_name']) && 
            isset($_POST['trk_dr_phone_code']) && isset($_POST['trk_dr_phone']))
    {
        $truck_owner = $_POST['trk_owner'];
        $truck_cat = $_POST['trk_cat'];
        $truck_type = $_POST['trk_cat_type'];
        $truck_num = $_POST['trk_num'];
        $truck_driver_name = $_POST['trk_dr_name'];
        $truck_driver_phone_code = $_POST['trk_dr_phone_code'];
        $truck_driver_phone = $_POST['trk_dr_phone'];

        $owner = "select * from truck_owners where to_id = '".$_POST['trk_owner']."'";
        $run_owner = mysqli_query($link, $owner);
        $row_owner = mysqli_fetch_array($run_owner, MYSQLI_ASSOC);

        $truck_counting = "select * from trucks where trk_owner = '".$row_owner['to_id']."'";
        $run_truck_counting = mysqli_query($link, $truck_counting);
        $count_truck_counting = mysqli_num_rows($run_truck_counting);

        if($count_truck_counting >= $row_owner['to_truck_limit'])
        {
            $responseData = ['success' => '0', 'message' => 'No space to add more truck. View add on truck plans'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);

            http_response_code(400);
        }
        else
        {
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

                $sqle = "SELECT * FROM trucks where trk_dr_phone = '$truck_driver_phone'";
                $checke = mysqli_query($link, $sqle);
                $rowe = mysqli_fetch_array($checke, MYSQLI_ASSOC);
                $counte = mysqli_num_rows($checke);
                if($counte >= 1)
                {
                    $responseData = ['success' => '0', 'message' => 'This Truck Driver Mobile Number is already registered'];
                    echo json_encode($responseData, JSON_PRETTY_PRINT);

                    http_response_code(400);
                }
                else
                {
                    $trk_folder = "../../assets/documents/truck_owners/truck_owner_id_".$truck_owner;

                    if(!is_dir($trk_folder))
                    {
                        mkdir("../../assets/documents/truck_owners/truck_owner_id_".$truck_owner);
                    }
                    
                    $trk_num_folder = "../../assets/documents/truck_owners/truck_owner_id_".$truck_owner."/".$truck_num;

                    if(!is_dir($trk_num_folder))
                    {
                        mkdir("../../assets/documents/truck_owners/truck_owner_id_".$truck_owner."/".$truck_num);
                    }

                    // Validate RC
                    if(!empty($_FILES['trk_rc']))
                    {
                        $file_name = $_FILES['trk_rc']['name'];
                        $file_size =$_FILES['trk_rc']['size'];
                        $file_tmp =$_FILES['trk_rc']['tmp_name'];
                        $file_type=$_FILES['trk_rc']['type'];
                        
                        if($file_size >= 300000)
                        {
                            $responseData = ['success' => '0', 'message' => 'RC file size must be less than 300 kb'];
                            echo json_encode($responseData, JSON_PRETTY_PRINT);

                            http_response_code(400);
                        }
                        else
                        {
                            $file_name = $_FILES["trk_rc"]["name"];
                            $rn = explode(".", $file_name);
                            $extension = end($rn);
                            $new_trk_rc = "rc.".$extension;
                            
                            move_uploaded_file($file_tmp,"../../assets/documents/truck_owners/truck_owner_id_".$truck_owner."/".$truck_num."/".$new_trk_rc);

                            // Validate Insurance
                            if(!empty($_FILES['trk_insurance']))
                            {
                                $file_name2 = $_FILES['trk_insurance']['name'];
                                $file_size2 = $_FILES['trk_insurance']['size'];
                                $file_tmp2 = $_FILES['trk_insurance']['tmp_name'];
                                $file_type2 = $_FILES['trk_insurance']['type'];
                                
                                
                                if($file_size2 >= 300000)
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
                                    $new_trk_insurance = "insurance.".$extension2;

                                    move_uploaded_file($file_tmp2,"../../assets/documents/truck_owners/truck_owner_id_".$truck_owner."/".$truck_num."/".$new_trk_insurance);

                                    // Validate Road Tax
                                    if(!empty($_FILES['trk_road_tax']))
                                    {
                                        $file_namefp = $_FILES['trk_road_tax']['name'];
                                        $file_sizefp = $_FILES['trk_road_tax']['size'];
                                        $file_tmpfp = $_FILES['trk_road_tax']['tmp_name'];
                                        $file_typefp = $_FILES['trk_road_tax']['type'];
                                        
                                        
                                        if($file_sizefp >= 300000)
                                        {
                                            $responseData = ['success' => '0', 'message' => 'Road tax file size must be less than 300 kb'];
                                            echo json_encode($responseData, JSON_PRETTY_PRINT);

                                            http_response_code(400);
                                        }
                                        else
                                        {
                                            $file_namefp = $_FILES["trk_road_tax"]["name"];
                                            $rnfp = explode(".", $file_namefp);
                                            $extensionfp = end($rnfp);
                                            $new_trk_road_tax = "road_tax.".$extensionfp;

                                            move_uploaded_file($file_tmpfp,"../../assets/documents/truck_owners/truck_owner_id_".$truck_owner."/".$truck_num."/".$new_trk_road_tax);

                                            // Validate RTO
                                            if(!empty($_FILES['trk_rto']))
                                            {
                                                $file_namefp = $_FILES['trk_rto']['name'];
                                                $file_sizefp = $_FILES['trk_rto']['size'];
                                                $file_tmpfp = $_FILES['trk_rto']['tmp_name'];
                                                $file_typefp = $_FILES['trk_rto']['type'];
                                                
                                                
                                                if($file_sizefp >= 300000)
                                                {
                                                    $responseData = ['success' => '0', 'message' => 'RTO file size must be less than 300 kb'];
                                                    echo json_encode($responseData, JSON_PRETTY_PRINT);

                                                    http_response_code(400);
                                                }
                                                else
                                                {
                                                    $file_namefp = $_FILES["trk_rto"]["name"];
                                                    $rnfp = explode(".", $file_namefp);
                                                    $extensionfp = end($rnfp);
                                                    $new_trk_rto = "rto.".$extensionfp;

                                                    move_uploaded_file($file_tmpfp,"../../assets/documents/truck_owners/truck_owner_id_".$truck_owner."/".$truck_num."/".$new_trk_rto);

                                                    $trk_folder_loc = "assets/documents/truck_owners/truck_owner_id_".$truck_owner."/".$truck_num."/";

                                                    $rc = $trk_folder_loc.$new_trk_rc;
                                                    $insurance = $trk_folder_loc.$new_trk_insurance;
                                                    $road_tax = $trk_folder_loc.$new_trk_road_tax;
                                                    $rto = $trk_folder_loc.$new_trk_rto;

                                                    // $mobile_sql = "insert into trucks (trk_owner, trk_cat, trk_cat_type, trk_num, trk_dr_name, trk_dr_phone_code, 
                                                    //                 trk_dr_phone, trk_rc, trk_insurance, trk_road_tax, trk_rto) values 
                                                    //                 ('$truck_owner', '$truck_cat', '$truck_type', '$truck_num', '$truck_driver_name', 
                                                    //                 '$truck_driver_phone_code', '$truck_driver_phone', '$rc', '$insurance', 
                                                    //                 '$road_tax', '$rto')";

                                                    $mobile_sql = "insert into trucks (trk_owner, trk_cat, trk_cat_type, trk_num, trk_dr_name, trk_dr_phone_code, 
                                                                    trk_dr_phone) values ('$truck_owner', '$truck_cat', '$truck_type', '$truck_num', '$truck_driver_name', 
                                                                    '$truck_driver_phone_code', '$truck_driver_phone')";

                                                    $mobile_insert = mysqli_query($link, $mobile_sql);
                                                    
                                                    if($mobile_insert)
                                                    {
                                                        mysqli_query($link, "insert into truck_docs (trk_doc_truck_num, trk_doc_sr_num) values ('$truck_num', 1)");
                                                        mysqli_query($link, "insert into truck_docs (trk_doc_truck_num, trk_doc_sr_num) values ('$truck_num', 2)");
                                                        mysqli_query($link, "insert into truck_docs (trk_doc_truck_num, trk_doc_sr_num, trk_doc_location) values ('$truck_num', 3, '$rc')");
                                                        mysqli_query($link, "insert into truck_docs (trk_doc_truck_num, trk_doc_sr_num, trk_doc_location) values ('$truck_num', 4, '$insurance')");
                                                        mysqli_query($link, "insert into truck_docs (trk_doc_truck_num, trk_doc_sr_num, trk_doc_location) values ('$truck_num', 5, '$road_tax')");
                                                        mysqli_query($link, "insert into truck_docs (trk_doc_truck_num, trk_doc_sr_num, trk_doc_location) values ('$truck_num', 6, '$rto')");

                                                        $no_title = "New Truck Registered";
                                                        $no_message = "New truck registered by truck owner ID ".$truck_owner." with driver's phone number ".$truck_driver_phone;
                                                        $no_for_id = $truck_driver_phone;
                                                        $no_date = date('Y-m-d H:i:s');
                                                        mysqli_query($link, "insert into notifications (no_date_time, no_title, no_message, id) values('$no_date', '$no_title', '$no_message', '$no_for_id')");

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
                        $responseData = ['success' => '0', 'message' => 'RC is missing'];
                        echo json_encode($responseData, JSON_PRETTY_PRINT);

                        http_response_code(206);
                    }
                }
            }
        }
    }

    // 3 - RC; 4 - Insurance; 5 - Road Tax; 6 - RTO Passing

    //edit truck and driver details
    elseif(isset($_POST['trk_id']) && isset($_POST['trk_cat_edit']) && isset($_POST['trk_cat_type_edit']) && isset($_POST['trk_num_edit']) && isset($_POST['trk_dr_name_edit']) && 
        isset($_POST['trk_dr_phone_code_edit']) && isset($_POST['trk_dr_phone_edit']))
    {
        $truck_id = $_POST['trk_id'];
        $truck_cat_edit = $_POST['trk_cat_edit'];
        $truck_cat_type_edit = $_POST['trk_cat_type_edit'];
        $truck_num_edit = $_POST['trk_num_edit'];
        $truck_driver_name_edit = $_POST['trk_dr_name_edit'];
        $truck_driver_phone_code_edit = $_POST['trk_dr_phone_code_edit'];
        $truck_driver_phone_edit = $_POST['trk_dr_phone_edit'];

        $sqle = "SELECT * FROM trucks where trk_id = '$truck_id'";
        $checke = mysqli_query($link, $sqle);
        $rowe = mysqli_fetch_array($checke, MYSQLI_ASSOC);

        if($truck_num_edit == $rowe['trk_num'])
        {
            $d_sql = "update trucks set trk_cat = '$truck_cat_edit', trk_cat_type = '$truck_cat_type_edit', trk_dr_name = '$truck_driver_name_edit',
                        trk_dr_phone_code = '$truck_driver_phone_code_edit', trk_dr_phone = '$truck_driver_phone_edit' where trk_id = '$truck_id'";

            $d_run = mysqli_query($link, $d_sql);

            if($d_run)
            {
                $no_title = "Truck docs";
                $no_message = "Truck number changed from '".$rowe['trk_num']."' to '".$truck_num_edit."'";
                $no_for_id = $rowe['trk_id'];
                $no_date = date('Y-m-d H:i:s');
                mysqli_query($link, "insert into notifications (no_date_time, no_title, no_message, id) values('$no_date', '$no_title', '$no_message', '$no_for_id')");
                
                $responseData = ['success' => '1', 'message' => 'Truck Details Updated'];
                echo json_encode($responseData, JSON_PRETTY_PRINT);
                http_response_code(200);
            }
            else
            {
                $responseData = ['success' => '0', 'message' => 'Something went wrong'];
                echo json_encode($responseData, JSON_PRETTY_PRINT);
            }
        }
        else
        {
            $sqlee = "SELECT * FROM trucks where trk_num = '$truck_num_edit'";
            $checkee = mysqli_query($link, $sqlee);
            $rowee = mysqli_fetch_array($checkee, MYSQLI_ASSOC);
            $countee = mysqli_num_rows($checkee);
            if($countee >= 1)
            {
                $responseData = ['success' => '0', 'message' => 'This Truck Number is already registered'];
                echo json_encode($responseData, JSON_PRETTY_PRINT);

                http_response_code(400);
            }
            else
            {
                //selfie
                $doc = "select * from truck_docs where trk_doc_truck_num = '".$rowe['trk_num']."' and trk_doc_sr_num = 1";
                $r_doc = mysqli_query($link, $doc);
                $selfie = mysqli_fetch_array($r_doc, MYSQLI_ASSOC);

                $selfie_update = $selfie['trk_doc_location'];
                $selfie_update = str_replace($rowe['trk_num'], $truck_num_edit, $selfie_update);
                mysqli_query($link, "update truck_docs set trk_doc_location = '$selfie_update' where trk_doc_id = '".$selfie['trk_doc_id']."'");


                //dl
                $doc1 = "select * from truck_docs where trk_doc_truck_num = '".$rowe['trk_num']."' and trk_doc_sr_num = 2";
                $r_doc1 = mysqli_query($link, $doc1);
                $dl = mysqli_fetch_array($r_doc1, MYSQLI_ASSOC);

                $dl_update = $dl['trk_doc_location'];
                $dl_update = str_replace($rowe['trk_num'], $truck_num_edit, $dl_update);
                mysqli_query($link, "update truck_docs set trk_doc_location = '$dl_update' where trk_doc_id = '".$dl['trk_doc_id']."'");


                //rc
                $doc2 = "select * from truck_docs where trk_doc_truck_num = '".$rowe['trk_num']."' and trk_doc_sr_num = 3";
                $r_doc2 = mysqli_query($link, $doc2);
                $rc = mysqli_fetch_array($r_doc2, MYSQLI_ASSOC);

                $rc_update = $rc['trk_doc_location'];
                $rc_update = str_replace($rowe['trk_num'], $truck_num_edit, $rc_update);
                mysqli_query($link, "update truck_docs set trk_doc_location = '$rc_update', trk_doc_verified = 0 where trk_doc_id = '".$rc['trk_doc_id']."'");


                //insurance
                $doc3 = "select * from truck_docs where trk_doc_truck_num = '".$rowe['trk_num']."' and trk_doc_sr_num = 4";
                $r_doc3 = mysqli_query($link, $doc3);
                $insurance = mysqli_fetch_array($r_doc3, MYSQLI_ASSOC);

                $insurance_update = $insurance['trk_doc_location'];
                $insurance_update = str_replace($rowe['trk_num'], $truck_num_edit, $insurance_update);
                mysqli_query($link, "update truck_docs set trk_doc_location = '$insurance_update', trk_doc_verified = 0 where trk_doc_id = '".$insurance['trk_doc_id']."'");


                //road tax
                $doc4 = "select * from truck_docs where trk_doc_truck_num = '".$rowe['trk_num']."' and trk_doc_sr_num = 5";
                $r_doc4 = mysqli_query($link, $doc4);
                $road_t = mysqli_fetch_array($r_doc4, MYSQLI_ASSOC);

                $road_t_update = $road_t['trk_doc_location'];
                $road_t_update = str_replace($rowe['trk_num'], $truck_num_edit, $road_t_update);
                mysqli_query($link, "update truck_docs set trk_doc_location = '$road_t_update', trk_doc_verified = 0 where trk_doc_id = '".$road_t['trk_doc_id']."'");


                //rto pass
                $doc5 = "select * from truck_docs where trk_doc_truck_num = '".$rowe['trk_num']."' and trk_doc_sr_num = 6";
                $r_doc5 = mysqli_query($link, $doc5);
                $rto_p = mysqli_fetch_array($r_doc5, MYSQLI_ASSOC);

                $rto_p_update = $rto_p['trk_doc_location'];
                $rto_p_update = str_replace($rowe['trk_num'], $truck_num_edit, $rto_p_update);
                mysqli_query($link, "update truck_docs set trk_doc_location = '$rto_p_update', trk_doc_verified = 0 where trk_doc_id = '".$rto_p['trk_doc_id']."'");

                $docs_table = "update truck_docs set trk_doc_truck_num = '$truck_num_edit' where trk_doc_truck_num = '".$rowe['trk_num']."'";
                $run_docs_table = mysqli_query($link, $docs_table);

                if($run_docs_table)
                {
                    $d_sql = "update trucks set trk_cat = '$truck_cat_edit', trk_cat_type = '$truck_cat_type_edit', trk_num = '$truck_num_edit', 
                            trk_dr_name = '$truck_driver_name_edit', trk_dr_phone_code = '$truck_driver_phone_code_edit', 
                            trk_dr_phone = '$truck_driver_phone_edit', trk_verified = 0 where trk_id = '$truck_id'";
                    $d_run = mysqli_query($link, $d_sql);

                    if($d_run)
                    {
                        $old_dirname1 = "../../assets/documents/truck_owners/truck_owner_id_".$rowe['trk_owner']."/".$rowe['trk_num'];
                        $new_dirname = "../../assets/documents/truck_owners/truck_owner_id_".$rowe['trk_owner']."/".$truck_num_edit;

                        if(rename("$old_dirname1", "$new_dirname"))
                        {
                            $no_title = "Truck docs";
                            $no_message = "Truck number changed from '".$rowe['trk_num']."' to '".$truck_num_edit."'";
                            $no_for_id = $rowe['trk_id'];
                            $no_date = date('Y-m-d H:i:s');
                            mysqli_query($link, "insert into notifications (no_date_time, no_title, no_message, id) values('$no_date', '$no_title', '$no_message', '$no_for_id')");

                            $owner = "select * from truck_owners where to_id = '".$rowe['trk_owner']."'";
                            $g_owner = mysqli_query($link, $owner);
                            $row_owner = mysqli_fetch_array($g_owner, MYSQLI_ASSOC);
                            
                            $device_id = $row_owner['to_token'];
                            $title = "Document Verification";
                            $message = "Your truck number changed from '".$rowe['trk_num']."' to '".$truck_num_edit."'. Update documents for new truck number";
                            $sent = push_notification_android($device_id, $title, $message);
                            
                            $responseData = ['success' => '1', 'message' => 'Truck Details Updated'];
                            echo json_encode($responseData, JSON_PRETTY_PRINT);
                            http_response_code(200);
                        }
                        else
                        {
                            $responseData = ['success' => '0', 'message' => 'Partial update. Rename truck'];
                            echo json_encode($responseData, JSON_PRETTY_PRINT);

                            http_response_code(400);
                        }
                    }
                }
                else
                {
                    $responseData = ['success' => '0', 'message' => 'Something went wrong'];
                    echo json_encode($responseData, JSON_PRETTY_PRINT);
                }
            }
        }
    }

    //edit truck RC file - 3
    elseif(isset($_POST['trk_id']) && !empty($_FILES['trk_rc_edit']))
    {
        $sqle = "SELECT * FROM trucks where trk_id = '".$_POST['trk_id']."'";
        $checke = mysqli_query($link, $sqle);
        $rowe = mysqli_fetch_array($checke, MYSQLI_ASSOC);

        $file_name1 = $_FILES['trk_rc_edit']['name'];
        $file_size1 = $_FILES['trk_rc_edit']['size'];
        $file_tmp1 = $_FILES['trk_rc_edit']['tmp_name'];
        $file_type1 = $_FILES['trk_rc_edit']['type'];
        
        if($file_size1 >= 300000)
        {
            $responseData = ['success' => '0', 'message' => 'RC file size must be less than 300 kb'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);

            http_response_code(400);
        }
        else
        {
            $file_name1 = $_FILES["trk_rc_edit"]["name"];
            $rn1 = explode(".", $file_name1);
            $extension1 = end($rn1);
            $new_trk_dr_license = "rc.".$extension1;

            $des = "../../assets/documents/truck_owners/truck_owner_id_".$rowe['trk_owner']."/".$rowe['trk_num']."/".$new_trk_dr_license;

            $dir = "../../assets/documents/truck_owners/truck_owner_id_".$rowe['trk_owner']."/".$rowe['trk_num'];    
            array_map('unlink', glob("$dir/rc.*"));

            move_uploaded_file($file_tmp1, $des);

            $des = "assets/documents/truck_owners/truck_owner_id_".$rowe['trk_owner']."/".$rowe['trk_num']."/".$new_trk_dr_license;

            mysqli_query($link, "update trucks set trk_verified = 0 where trk_id = '".$rowe['trk_id']."'");

            $d_sql = "update truck_docs set trk_doc_location = '$des', trk_doc_verified = 0 where trk_doc_truck_num = '".$rowe['trk_num']."' and trk_doc_sr_num = 3";
            $d_run = mysqli_query($link, $d_sql);

            if($d_run)
            {
                $no_title = "Truck docs";
                $no_message = "Truck ID ".$_POST['trk_id']." updated RC details";
                $no_for_id = $_POST['trk_id'];
                $no_date = date('Y-m-d H:i:s');
                mysqli_query($link, "insert into notifications (no_date_time, no_title, no_message, id) values('$no_date', '$no_title', '$no_message', '$no_for_id')");

                $responseData = ['success' => '1', 'message' => 'Truck RC Updated'];
                echo json_encode($responseData, JSON_PRETTY_PRINT);
                http_response_code(200);
            }
            else
            {
                $responseData = ['success' => '0', 'message' => 'Update failed'];
                echo json_encode($responseData, JSON_PRETTY_PRINT);

                http_response_code(400);
            }
        }
    }

    //edit truck insurnace file - 4
    elseif(isset($_POST['trk_id']) && !empty($_FILES['trk_insurance_edit']))
    {
        $sqle = "SELECT * FROM trucks where trk_id = '".$_POST['trk_id']."'";
        $checke = mysqli_query($link, $sqle);
        $rowe = mysqli_fetch_array($checke, MYSQLI_ASSOC);

        $file_name1 = $_FILES['trk_insurance_edit']['name'];
        $file_size1 = $_FILES['trk_insurance_edit']['size'];
        $file_tmp1 = $_FILES['trk_insurance_edit']['tmp_name'];
        $file_type1 = $_FILES['trk_insurance_edit']['type'];
        
        if($file_size1 >= 300000)
        {
            $responseData = ['success' => '0', 'message' => 'Insurance file size must be less than 300 kb'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);

            http_response_code(400);
        }
        else
        {
            $file_name1 = $_FILES["trk_insurance_edit"]["name"];
            $rn1 = explode(".", $file_name1);
            $extension1 = end($rn1);
            $new_trk_dr_license = "insurance.".$extension1;

            $des = "../../assets/documents/truck_owners/truck_owner_id_".$rowe['trk_owner']."/".$rowe['trk_num']."/".$new_trk_dr_license;

            $dir = "../../assets/documents/truck_owners/truck_owner_id_".$rowe['trk_owner']."/".$rowe['trk_num'];    
            array_map('unlink', glob("$dir/insurance.*"));

            move_uploaded_file($file_tmp1, $des);

            $des = "assets/documents/truck_owners/truck_owner_id_".$rowe['trk_owner']."/".$rowe['trk_num']."/".$new_trk_dr_license;

            mysqli_query($link, "update trucks set trk_verified = 0 where trk_id = '".$rowe['trk_id']."'");

            $d_sql = "update truck_docs set trk_doc_location = '$des', trk_doc_verified = 0 where trk_doc_truck_num = '".$rowe['trk_num']."' and trk_doc_sr_num = 4";
            $d_run = mysqli_query($link, $d_sql);

            if($d_run)
            {
                $no_title = "Truck docs";
                $no_message = "Truck ID ".$_POST['trk_id']." updated Insurance details";
                $no_for_id = $_POST['trk_id'];
                $no_date = date('Y-m-d H:i:s');
                mysqli_query($link, "insert into notifications (no_date_time, no_title, no_message, id) values('$no_date', '$no_title', '$no_message', '$no_for_id')");

                $responseData = ['success' => '1', 'message' => 'Truck Insurance Updated'];
                echo json_encode($responseData, JSON_PRETTY_PRINT);
                http_response_code(200);
            }
            else
            {
                $responseData = ['success' => '0', 'message' => 'Update failed'];
                echo json_encode($responseData, JSON_PRETTY_PRINT);

                http_response_code(400);
            }
        }
    }

    //edit truck road tax file - 5
    elseif(isset($_POST['trk_id']) && !empty($_FILES['trk_road_tax_edit']))
    {
        $sqle = "SELECT * FROM trucks where trk_id = '".$_POST['trk_id']."'";
        $checke = mysqli_query($link, $sqle);
        $rowe = mysqli_fetch_array($checke, MYSQLI_ASSOC);

        $file_name1 = $_FILES['trk_road_tax_edit']['name'];
        $file_size1 = $_FILES['trk_road_tax_edit']['size'];
        $file_tmp1 = $_FILES['trk_road_tax_edit']['tmp_name'];
        $file_type1 = $_FILES['trk_road_tax_edit']['type'];
        
        if($file_size1 >= 300000)
        {
            $responseData = ['success' => '0', 'message' => 'Road Tax file size must be less than 300 kb'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);

            http_response_code(400);
        }
        else
        {
            $file_name1 = $_FILES["trk_road_tax_edit"]["name"];
            $rn1 = explode(".", $file_name1);
            $extension1 = end($rn1);
            $new_trk_dr_license = "road_tax.".$extension1;

            $des = "../../assets/documents/truck_owners/truck_owner_id_".$rowe['trk_owner']."/".$rowe['trk_num']."/".$new_trk_dr_license;

            $dir = "../../assets/documents/truck_owners/truck_owner_id_".$rowe['trk_owner']."/".$rowe['trk_num'];    
            array_map('unlink', glob("$dir/road_tax.*"));

            move_uploaded_file($file_tmp1, $des);

            $des = "assets/documents/truck_owners/truck_owner_id_".$rowe['trk_owner']."/".$rowe['trk_num']."/".$new_trk_dr_license;

            mysqli_query($link, "update trucks set trk_verified = 0 where trk_id = '".$rowe['trk_id']."'");

            $d_sql = "update truck_docs set trk_doc_location = '$des', trk_doc_verified = 0 where trk_doc_truck_num = '".$rowe['trk_num']."' and trk_doc_sr_num = 5";
            $d_run = mysqli_query($link, $d_sql);

            if($d_run)
            {
                $no_title = "Truck docs";
                $no_message = "Truck ID ".$_POST['trk_id']." updated Road tax details";
                $no_for_id = $_POST['trk_id'];
                $no_date = date('Y-m-d H:i:s');
                mysqli_query($link, "insert into notifications (no_date_time, no_title, no_message, id) values('$no_date', '$no_title', '$no_message', '$no_for_id')");

                $responseData = ['success' => '1', 'message' => 'Truck Road Tax Updated'];
                echo json_encode($responseData, JSON_PRETTY_PRINT);
                http_response_code(200);
            }
            else
            {
                $responseData = ['success' => '0', 'message' => 'Update failed'];
                echo json_encode($responseData, JSON_PRETTY_PRINT);

                http_response_code(400);
            }
        }
    }

    //edit truck rto file - 6
    elseif(isset($_POST['trk_id']) && !empty($_FILES['trk_rto_edit']))
    {
        $sqle = "SELECT * FROM trucks where trk_id = '".$_POST['trk_id']."'";
        $checke = mysqli_query($link, $sqle);
        $rowe = mysqli_fetch_array($checke, MYSQLI_ASSOC);

        $file_name1 = $_FILES['trk_rto_edit']['name'];
        $file_size1 = $_FILES['trk_rto_edit']['size'];
        $file_tmp1 = $_FILES['trk_rto_edit']['tmp_name'];
        $file_type1 = $_FILES['trk_rto_edit']['type'];
        
        if($file_size1 >= 300000)
        {
            $responseData = ['success' => '0', 'message' => 'RTO file size must be less than 300 kb'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);

            http_response_code(400);
        }
        else
        {
            $file_name1 = $_FILES["trk_rto_edit"]["name"];
            $rn1 = explode(".", $file_name1);
            $extension1 = end($rn1);
            $new_trk_dr_license = "rto.".$extension1;

            $des = "../../assets/documents/truck_owners/truck_owner_id_".$rowe['trk_owner']."/".$rowe['trk_num']."/".$new_trk_dr_license;

            $dir = "../../assets/documents/truck_owners/truck_owner_id_".$rowe['trk_owner']."/".$rowe['trk_num'];    
            array_map('unlink', glob("$dir/rto.*"));

            move_uploaded_file($file_tmp1, $des);

            $des = "assets/documents/truck_owners/truck_owner_id_".$rowe['trk_owner']."/".$rowe['trk_num']."/".$new_trk_dr_license;

            mysqli_query($link, "update trucks set trk_verified = 0 where trk_id = '".$rowe['trk_id']."'");

            $d_sql = "update truck_docs set trk_doc_location = '$des', trk_doc_verified = 0 where trk_doc_truck_num = '".$rowe['trk_num']."' and trk_doc_sr_num = 6";
            $d_run = mysqli_query($link, $d_sql);

            if($d_run)
            {
                $no_title = "Truck docs";
                $no_message = "Truck ID ".$_POST['trk_id']." updated RTO passing details";
                $no_for_id = $_POST['trk_id'];
                $no_date = date('Y-m-d H:i:s');
                mysqli_query($link, "insert into notifications (no_date_time, no_title, no_message, id) values('$no_date', '$no_title', '$no_message', '$no_for_id')");

                $responseData = ['success' => '1', 'message' => 'Truck RTO Updated'];
                echo json_encode($responseData, JSON_PRETTY_PRINT);
                http_response_code(200);
            }
            else
            {
                $responseData = ['success' => '0', 'message' => 'Update failed'];
                echo json_encode($responseData, JSON_PRETTY_PRINT);

                http_response_code(400);
            }
        }
    }

    //set truck status
    elseif(isset($_POST['trk_id']) && isset($_POST['trk_status']))
    {
        $d_sql = "update trucks set trk_active = '".$_POST['trk_status']."' where trk_id = '".$_POST['trk_id']."'";
        $d_run = mysqli_query($link, $d_sql);
        if($d_run)
        {
            $no_title = "Truck status";
            $no_message = "Truck ID ".$_POST['trk_id']." status updated";
            $no_for_id = $_POST['trk_id'];
            $no_date = date('Y-m-d H:i:s');
            mysqli_query($link, "insert into notifications (no_date_time, no_title, no_message, id) values('$no_date', '$no_title', '$no_message', '$no_for_id')");

            $responseData = ['success' => '1', 'message' => 'Truck status updated'];
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

    //delete truck
    elseif(isset($_POST['del_truck_id']))
    {
        $sql = "select * from trucks where trk_id = '".$_POST['del_truck_id']."'";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        $owner = $row['trk_owner'];

        $dirname = "../../assets/documents/truck_owners/truck_owner_id_".$row['trk_owner']."/".$row['trk_num'];

        array_map('unlink', glob("$dirname/*.*"));
        rmdir("$dirname");

        $d_sql = "delete from trucks where trk_id = '".$_POST['del_truck_id']."'";
        $d_run = mysqli_query($link, $d_sql);
        if($d_run)
        {
            $no_title = "Truck removed";
            $no_message = "Truck removed by Truck Onwer ID ".$owner;
            $no_for_id = $owner;
            $no_date = date('Y-m-d H:i:s');
            mysqli_query($link, "insert into notifications (no_date_time, no_title, no_message, id) values('$no_date', '$no_title', '$no_message', '$no_for_id')");

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

    else
    {
        $responseData = ['success' => '0', 'message' => 'Something went wrong'];
        echo json_encode($responseData, JSON_PRETTY_PRINT);

        http_response_code(400);
    }
    
?>