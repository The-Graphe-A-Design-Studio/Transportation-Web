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
                        mysqli_query($link, "insert into notifications (no_title, no_message, id) values('$no_title', '$no_message', '$no_for_id')");

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
                        mysqli_query($link, "insert into notifications (no_title, no_message, id) values('$no_title', '$no_message', '$no_for_id')");

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
    else
    {
        $responseData = ['success' => '0', 'message' => 'Something is missing'];
        echo json_encode($responseData, JSON_PRETTY_PRINT);
        http_response_code(400);
    }

    mysqli_close($link);

?>