<?php

    include('../../dbcon.php');

    header('Content-Type: application/json');
    
    if(isset($_POST['to_phone']) && !empty($_FILES['to_pan_card']))
    {
        $sqle = "SELECT truck_owners.*, truck_owner_docs.* FROM truck_owners, truck_owner_docs WHERE truck_owners.to_phone = '".$_POST['to_phone']."' AND 
                truck_owners.to_phone = truck_owner_docs.to_doc_owner_phone";
        $checke = mysqli_query($link, $sqle);
        $rowe = mysqli_fetch_array($checke, MYSQLI_ASSOC);
        $counte = mysqli_num_rows($checke);
        if($counte == 0)
        {
            $responseData = ['success' => '0', 'message' => 'This number is not registered'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
        }
        else
        {
            $file_name1 = $_FILES['to_pan_card']['name'];
            $file_size1 = $_FILES['to_pan_card']['size'];
            $file_tmp1 = $_FILES['to_pan_card']['tmp_name'];
            $file_type1 = $_FILES['to_pan_card']['type'];
            
            if($file_size1 >= 200000)
            {
                $responseData = ['success' => '0', 'message' => 'PAN card file size must be less than 200 kb'];
                echo json_encode($responseData, JSON_PRETTY_PRINT);

                http_response_code(400);
            }
            else
            {
                $file_name1 = $_FILES["to_pan_card"]["name"];
                $rn1 = explode(".", $file_name1);
                $extension1 = end($rn1);
                $pan_card = "pan_card.".$extension1;

                $des = "../../assets/documents/truck_owners/truck_owner_id_".$rowe['to_id']."/".$pan_card;
                $dir = "../../assets/documents/truck_owners/truck_owner_id_".$rowe['to_id']."/";

                if(!is_dir($dir))
                {
                    mkdir("../../assets/documents/truck_owners/truck_owner_id_".$rowe['to_id']);
                }

                array_map('unlink', glob("$dir/pan_card.*"));

                move_uploaded_file($file_tmp1, $des);

                $des1 = "assets/documents/truck_owners/truck_owner_id_".$rowe['to_id']."/".$pan_card;

                $d_sql = "update truck_owner_docs set to_doc_location = '$des1' where to_doc_owner_phone = '".$_POST['to_phone']."' and to_doc_sr_num = 1";
                $d_run = mysqli_query($link, $d_sql);

                if($d_run)
                {
                    $no_title = "Owner docs";
                    $no_message = "Truck owner ID ".$rowe['to_id']." uploaded PAN card";
                    $no_for_id = $rowe['to_id'];
                    $no_date = date('Y-m-d H:i:s');
mysqli_query($link, "insert into notifications (no_date_time, no_title, no_message, id) values('$no_date', '$no_title', '$no_message', '$no_for_id')");
        
                    $responseData = ['success' => '1', 'message' => 'PAN card uploaded'];
                    echo json_encode($responseData, JSON_PRETTY_PRINT);
                    http_response_code(200);
                }
                else
                {
                    $responseData = ['success' => '0', 'message' => 'Upload failed'];
                    echo json_encode($responseData, JSON_PRETTY_PRINT);

                    http_response_code(400);
                }
            }
        }
    }
    elseif(isset($_POST['to_phone']) && isset($_POST['to_name']) && isset($_POST['to_bank']) && isset($_POST['to_ifsc']))
    {
        $sql = "update truck_owners set to_name = '".$_POST['to_name']."', to_bank = '".$_POST['to_bank']."', to_ifsc = '".$_POST['to_ifsc']."' where to_phone = '".$_POST['to_phone']."'";
        $run = mysqli_query($link, $sql);

        if($run)
        {
            $own = "select * from truck_owners where to_phone = '".$_POST['to_phone']."'";
            $run_own = mysqli_query($link, $own);
            $row_own = mysqli_fetch_array($run_own, MYSQLI_ASSOC);

            $no_title = "Owner details";
            $no_message = "Truck owner ID ".$row_own['to_id']." updated his/her bank details";
            $no_for_id = $row_own['to_id'];
            $no_date = date('Y-m-d H:i:s');
mysqli_query($link, "insert into notifications (no_date_time, no_title, no_message, id) values('$no_date', '$no_title', '$no_message', '$no_for_id')");

            $responseData = ['success' => '1', 'message' => 'Details updated'];
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
        $responseData = ['success' => '0', 'message' => 'Something is missing'];
        echo json_encode($responseData, JSON_PRETTY_PRINT);

        http_response_code(400);
    }

    mysqli_close($link);

?>