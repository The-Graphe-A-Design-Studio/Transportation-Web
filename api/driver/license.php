<?php

    include('../../dbcon.php');

    header('Content-Type: application/json');

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
            $new_trk_dr_license = "license.".$extension1;

            move_uploaded_file($file_tmp1,"../../assets/documents/truck_owners/truck_owners_id_".$truck_owner."/".$truck_num."/".$new_trk_dr_license);

            
        }
    }
    else
    {
        $responseData = ['success' => '0', 'message' => 'License is missing'];
        echo json_encode($responseData, JSON_PRETTY_PRINT);

        http_response_code(206);
    }

?>