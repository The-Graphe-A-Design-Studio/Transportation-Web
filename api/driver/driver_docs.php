<?php

    include('../../dbcon.php');

    header('Content-Type: application/json');

    // Validate License - 2
    if(isset($_POST['trk_id']) && !empty($_FILES['trk_dr_license']))
    {
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
                $new_trk_dr_license = "license.".$extension1;

                $sql = "select trucks.*, truck_owners.* from trucks, truck_owners where trucks.trk_id = '".$_POST['trk_id']."' and trucks.trk_owner = truck_owners.to_id";
                $run = mysqli_query($link, $sql);
                $row = mysqli_fetch_array($run, MYSQLI_ASSOC);

                $dir = "../../assets/documents/truck_owners/truck_owner_id_".$row['to_id']."/".$row['trk_num'];    
                array_map('unlink', glob("$dir/license.*"));
                
                $done = move_uploaded_file($file_tmp1,"../../assets/documents/truck_owners/truck_owner_id_".$row['to_id']."/".$row['trk_num']."/".$new_trk_dr_license);

                $dir = "assets/documents/truck_owners/truck_owner_id_".$row['to_id']."/".$row['trk_num']."/".$new_trk_dr_license;

                if($done)
                {
                    $update = "update truck_docs set trk_doc_location = '$dir' where trk_doc_truck_num = '".$row['trk_num']."' and trk_doc_sr_num = 2";
                    $run_update = mysqli_query($link, $update);

                    if($run_update)
                    {
                        $no_title = "Driver Docs";
                        $no_message = "Truck Driver ID ".$row['trk_id']." uploaded Driving license";
                        $no_for_id = $row['trk_id'];
                        $no_date = date('Y-m-d H:i:s');
mysqli_query($link, "insert into notifications (no_date_time, no_title, no_message, id) values('$no_date', '$no_title', '$no_message', '$no_for_id')");

                        $responseData = ['success' => '1', 'message' => 'License uploaded'];
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
                    $responseData = ['success' => '0', 'message' => 'License upload failed'];
                    echo json_encode($responseData, JSON_PRETTY_PRINT);
                    http_response_code(400);
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
    // Validate selfie - 1
    elseif(isset($_POST['trk_id']) && !empty($_FILES['trk_dr_selfie']))
    {
        if(!empty($_FILES['trk_dr_selfie']))
        {
            $file_name1 = $_FILES['trk_dr_selfie']['name'];
            $file_size1 = $_FILES['trk_dr_selfie']['size'];
            $file_tmp1 = $_FILES['trk_dr_selfie']['tmp_name'];
            $file_type1 = $_FILES['trk_dr_selfie']['type'];
            
            
            if($file_size1 >= 200000)
            {
                $responseData = ['success' => '0', 'message' => 'Selfie file size must be less than 200 kb'];
                echo json_encode($responseData, JSON_PRETTY_PRINT);

                http_response_code(400);
            }
            else
            {
                $file_name1 = $_FILES["trk_dr_selfie"]["name"];
                $rn1 = explode(".", $file_name1);
                $extension1 = end($rn1);
                $new_trk_dr_selfie = "driver_selfie.".$extension1;

                $sql = "select trucks.*, truck_owners.* from trucks, truck_owners where trucks.trk_id = '".$_POST['trk_id']."' and trucks.trk_owner = truck_owners.to_id";
                $run = mysqli_query($link, $sql);
                $row = mysqli_fetch_array($run, MYSQLI_ASSOC);

                $dir = "../../assets/documents/truck_owners/truck_owner_id_".$row['to_id']."/".$row['trk_num'];    
                array_map('unlink', glob("$dir/driver_selfie.*"));
                
                $done = move_uploaded_file($file_tmp1,"../../assets/documents/truck_owners/truck_owner_id_".$row['to_id']."/".$row['trk_num']."/".$new_trk_dr_selfie);

                $dir = "assets/documents/truck_owners/truck_owner_id_".$row['to_id']."/".$row['trk_num']."/".$new_trk_dr_selfie;

                if($done)
                {
                    $update = "update truck_docs set trk_doc_location = '$dir' where trk_doc_truck_num = '".$row['trk_num']."' and trk_doc_sr_num = 1";
                    $run_update = mysqli_query($link, $update);

                    if($run_update)
                    {
                        $no_title = "Driver Docs";
                        $no_message = "Truck Driver ID ".$row['trk_id']." uploaded Selfie";
                        $no_for_id = $row['trk_id'];
                        $no_date = date('Y-m-d H:i:s');
mysqli_query($link, "insert into notifications (no_date_time, no_title, no_message, id) values('$no_date', '$no_title', '$no_message', '$no_for_id')");

                        $responseData = ['success' => '1', 'message' => 'Selfie uploaded'];
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
                    $responseData = ['success' => '0', 'message' => 'Selfie upload failed'];
                    echo json_encode($responseData, JSON_PRETTY_PRINT);
                    http_response_code(400);
                }
            }
        }
        else
        {
            $responseData = ['success' => '0', 'message' => 'Selfie is missing'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);

            http_response_code(206);
        }
    }
    elseif(isset($_POST['truck_id']))
    {
        $sql = "select * from trucks where cu_id = '".$_POST['truck_id']."'";
        $g_sql = mysqli_query($link, $sql);
        $row = mysqli_fetch_array($g_sql, MYSQLI_ASSOC);

        $doc = "select * from truck_docs where trk_doc_truck_num = '".$row['trk_num']."' and trk_doc_sr_num = 1";
        $r_doc = mysqli_query($link, $doc);
        $selfie = mysqli_fetch_array($r_doc, MYSQLI_ASSOC);

        $doc1 = "select * from truck_docs where trk_doc_truck_num = '".$row['trk_num']."' and trk_doc_sr_num = 2";
        $r_doc1 = mysqli_query($link, $doc1);
        $dl = mysqli_fetch_array($r_doc1, MYSQLI_ASSOC);

        $doc2 = "select * from truck_docs where trk_doc_truck_num = '".$row['trk_num']."' and trk_doc_sr_num = 3";
        $r_doc2 = mysqli_query($link, $doc2);
        $rc = mysqli_fetch_array($r_doc2, MYSQLI_ASSOC);

        $doc3 = "select * from truck_docs where trk_doc_truck_num = '".$row['trk_num']."' and trk_doc_sr_num = 4";
        $r_doc3 = mysqli_query($link, $doc3);
        $insurance = mysqli_fetch_array($r_doc3, MYSQLI_ASSOC);

        $doc4 = "select * from truck_docs where trk_doc_truck_num = '".$row['trk_num']."' and trk_doc_sr_num = 5";
        $r_doc4 = mysqli_query($link, $doc4);
        $road_t = mysqli_fetch_array($r_doc4, MYSQLI_ASSOC);

        $doc5 = "select * from truck_docs where trk_doc_truck_num = '".$row['trk_num']."' and trk_doc_sr_num = 6";
        $r_doc5 = mysqli_query($link, $doc5);
        $rto_p = mysqli_fetch_array($r_doc5, MYSQLI_ASSOC);    

        $owner = "select * from truck_owners where to_id = '".$row['trk_owner']."'";
        $g_owner = mysqli_query($link, $owner);
        $row_owner = mysqli_fetch_array($g_owner, MYSQLI_ASSOC);

        $cat = "select * from truck_cat where trk_cat_id = '".$row['trk_cat']."'";
        $g_cat = mysqli_query($link, $cat);
        $row_cat = mysqli_fetch_array($g_cat, MYSQLI_ASSOC);

        $type = "select * from truck_cat_type where ty_id = '".$row['trk_cat_type']."'";
        $g_type = mysqli_query($link, $type);
        $row_type = mysqli_fetch_array($g_type, MYSQLI_ASSOC);

        $responseData = ['truck id' => $row['trk_id'], 
                        'owner name' => $row_owner['to_name'],
                        'owner phone' => $row_owner['to_phone'],
                        'truck number' => $row['trk_num'],
                        'truck category' => $row_cat['trk_cat_name'],
                        'truck type' => $row_type['ty_name'],
                        'truck verified' => $row['trk_verified'],
                        'selfie' => $selfie['trk_doc_location'],
                        'selfie verified' => $selfie['trk_doc_verified'],
                        'license' => $dl['trk_doc_location'],
                        'license verified' => $dl['trk_doc_verified'],
                        'rc' => $rc['trk_doc_location'],
                        'rc verified' => $rc['trk_doc_verified'],
                        'insurance' => $insurance['trk_doc_location'],
                        'insurance verified' => $insurance['trk_doc_verified'],
                        'rto pass' => $rto_p['trk_doc_location'],
                        'rto pass verified' => $rto_p['trk_doc_verified'],
                        'road tax' => $road_t['trk_doc_location'],
                        'road tax verified' => $road_t['trk_doc_verified']
                        ];
        echo json_encode($responseData, JSON_PRETTY_PRINT);
        http_response_code(200);
    }
    else
    {
        $responseData = ['success' => '0', 'message' => 'Something is missing'];
        echo json_encode($responseData, JSON_PRETTY_PRINT);
        http_response_code(400);
    }

    mysqli_close($link);

?>