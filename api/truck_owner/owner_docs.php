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

                $des = "../../assets/documents/truck_owners/truck_owner_".$rowe['to_phone']."/".$pan_card;
                $dir = "../../assets/documents/truck_owners/truck_owner_".$rowe['to_phone']."/";

                if(!is_dir($dir))
                {
                    mkdir("../../assets/documents/truck_owners/truck_owner_".$rowe['to_phone']);
                }

                array_map('unlink', glob("$dir/pan_card.*"));

                move_uploaded_file($file_tmp1, $des);

                $des1 = "assets/documents/truck_owners/truck_owner_".$rowe['to_phone']."/".$pan_card;

                $d_sql = "update truck_owner_docs set to_doc_location = '$des1' where to_doc_owner_phone = '".$_POST['to_phone']."' and to_doc_sr_num = 1";
                $d_run = mysqli_query($link, $d_sql);

                if($d_run)
                {
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
    else
    {
        $responseData = ['success' => '0', 'message' => 'Something went wrong'];
        echo json_encode($responseData, JSON_PRETTY_PRINT);

        http_response_code(400);
    }

?>