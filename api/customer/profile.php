<?php

    include('../../dbcon.php');

    header('Content-Type: application/json');
    
    if(isset($_POST['cu_phone']) && !empty($_FILES['cu_pan_card']))
    {
        $sqle = "SELECT customers.*, customer_docs.* FROM customers, customer_docs WHERE customers.cu_phone = '".$_POST['cu_phone']."' AND 
                customers.cu_phone = customer_docs.doc_owner_phone";
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
            $file_name1 = $_FILES['cu_pan_card']['name'];
            $file_size1 = $_FILES['cu_pan_card']['size'];
            $file_tmp1 = $_FILES['cu_pan_card']['tmp_name'];
            $file_type1 = $_FILES['cu_pan_card']['type'];
            
            if($file_size1 >= 200000)
            {
                $responseData = ['success' => '0', 'message' => 'PAN card file size must be less than 200 kb'];
                echo json_encode($responseData, JSON_PRETTY_PRINT);

                http_response_code(400);
            }
            else
            {
                $file_name1 = $_FILES["cu_pan_card"]["name"];
                $rn1 = explode(".", $file_name1);
                $extension1 = end($rn1);
                $pan_card = "pan_card.".$extension1;

                $des = "../../assets/documents/shippers/shipper_".$rowe['cu_phone']."/".$pan_card;
                $dir = "../../assets/documents/shippers/shipper_".$rowe['cu_phone']."/";

                if(!is_dir($dir))
                {
                    mkdir("../../assets/documents/shippers/shipper_".$rowe['cu_phone']);
                }

                array_map('unlink', glob("$dir/pan_card.*"));

                move_uploaded_file($file_tmp1, $des);

                $des1 = "assets/documents/shippers/shipper_".$rowe['cu_phone']."/".$pan_card;

                $d_sql = "update customer_docs set doc_location = '$des1' where doc_owner_phone = '".$_POST['cu_phone']."' and doc_sr_num = 1";
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
    elseif(isset($_POST['cu_phone']) && isset($_POST['cu_address_type']) && !empty($_FILES['cu_address_front']))
    {
        $sqle = "SELECT customers.*, customer_docs.* FROM customers, customer_docs WHERE customers.cu_phone = '".$_POST['cu_phone']."' AND 
                customers.cu_phone = customer_docs.doc_owner_phone";
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

            $file_name1 = $_FILES['cu_address_front']['name'];
            $file_size1 = $_FILES['cu_address_front']['size'];
            $file_tmp1 = $_FILES['cu_address_front']['tmp_name'];
            $file_type1 = $_FILES['cu_address_front']['type'];
            
            if($file_size1 >= 200000)
            {
                $responseData = ['success' => '0', 'message' => 'Address file size must be less than 200 kb'];
                echo json_encode($responseData, JSON_PRETTY_PRINT);

                http_response_code(400);
            }
            else
            {
                $file_name1 = $_FILES["cu_address_front"]["name"];
                $rn1 = explode(".", $file_name1);
                $extension1 = end($rn1);
                $address_front = "address_front.".$extension1;

                $des = "../../assets/documents/shippers/shipper_".$rowe['cu_phone']."/".$address_front;
                $dir = "../../assets/documents/shippers/shipper_".$rowe['cu_phone']."/";

                if(!is_dir($dir))
                {
                    mkdir("../../assets/documents/shippers/shipper_".$rowe['cu_phone']);
                }

                array_map('unlink', glob("$dir/address_front.*"));

                move_uploaded_file($file_tmp1, $des);

                $des1 = "assets/documents/shippers/shipper_".$rowe['cu_phone']."/".$address_front;

                $d_sqlw = "update customers set cu_address_type = '".$_POST['cu_address_type']."' where cu_phone = '".$_POST['cu_phone']."'";
                $d_runw = mysqli_query($link, $d_sqlw);

                $d_sql = "update customer_docs set doc_location = '$des1' where doc_owner_phone = '".$_POST['cu_phone']."' and doc_sr_num = 2";
                $d_run = mysqli_query($link, $d_sql);

                if($d_run)
                {
                    $responseData = ['success' => '1', 'message' => 'Address front side uploaded'];
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
    elseif(isset($_POST['cu_phone']) && !empty($_FILES['cu_address_back']))
    {
        $sqle = "SELECT customers.*, customer_docs.* FROM customers, customer_docs WHERE customers.cu_phone = '".$_POST['cu_phone']."' AND 
                customers.cu_phone = customer_docs.doc_owner_phone";
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

            $file_name1 = $_FILES['cu_address_back']['name'];
            $file_size1 = $_FILES['cu_address_back']['size'];
            $file_tmp1 = $_FILES['cu_address_back']['tmp_name'];
            $file_type1 = $_FILES['cu_address_back']['type'];
            
            if($file_size1 >= 200000)
            {
                $responseData = ['success' => '0', 'message' => 'Address file size must be less than 200 kb'];
                echo json_encode($responseData, JSON_PRETTY_PRINT);

                http_response_code(400);
            }
            else
            {
                $file_name1 = $_FILES["cu_address_back"]["name"];
                $rn1 = explode(".", $file_name1);
                $extension1 = end($rn1);
                $address_back = "address_back.".$extension1;

                $des = "../../assets/documents/shippers/shipper_".$rowe['cu_phone']."/".$address_back;
                $dir = "../../assets/documents/shippers/shipper_".$rowe['cu_phone']."/";

                if(!is_dir($dir))
                {
                    mkdir("../../assets/documents/shippers/shipper_".$rowe['cu_phone']);
                }

                array_map('unlink', glob("$dir/address_back.*"));

                move_uploaded_file($file_tmp1, $des);

                $des1 = "assets/documents/shippers/shipper_".$rowe['cu_phone']."/".$address_back;

                $d_sql = "update customer_docs set doc_location = '$des1' where doc_owner_phone = '".$_POST['cu_phone']."' and doc_sr_num = 3";
                $d_run = mysqli_query($link, $d_sql);

                if($d_run)
                {
                    $responseData = ['success' => '1', 'message' => 'Address back side uploaded'];
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
    elseif(isset($_POST['cu_phone']) && !empty($_FILES['cu_selfie']))
    {
        $sqle = "SELECT customers.*, customer_docs.* FROM customers, customer_docs WHERE customers.cu_phone = '".$_POST['cu_phone']."' AND 
                customers.cu_phone = customer_docs.doc_owner_phone";
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

            $file_name1 = $_FILES['cu_selfie']['name'];
            $file_size1 = $_FILES['cu_selfie']['size'];
            $file_tmp1 = $_FILES['cu_selfie']['tmp_name'];
            $file_type1 = $_FILES['cu_selfie']['type'];
            
            if($file_size1 >= 200000)
            {
                $responseData = ['success' => '0', 'message' => 'Selfie file size must be less than 200 kb'];
                echo json_encode($responseData, JSON_PRETTY_PRINT);

                http_response_code(400);
            }
            else
            {
                $file_name1 = $_FILES["cu_selfie"]["name"];
                $rn1 = explode(".", $file_name1);
                $extension1 = end($rn1);
                $selfie = "selfie.".$extension1;

                $des = "../../assets/documents/shippers/shipper_".$rowe['cu_phone']."/".$selfie;
                $dir = "../../assets/documents/shippers/shipper_".$rowe['cu_phone']."/";

                if(!is_dir($dir))
                {
                    mkdir("../../assets/documents/shippers/shipper_".$rowe['cu_phone']);
                }

                array_map('unlink', glob("$dir/selfie.*"));

                move_uploaded_file($file_tmp1, $des);

                $des1 = "assets/documents/shippers/shipper_".$rowe['cu_phone']."/".$selfie;

                $d_sql = "update customer_docs set doc_location = '$des1' where doc_owner_phone = '".$_POST['cu_phone']."' and doc_sr_num = 4";
                $d_run = mysqli_query($link, $d_sql);

                if($d_run)
                {
                    $responseData = ['success' => '1', 'message' => 'Selfie uploaded'];
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
    elseif(isset($_POST['cu_phone']) && isset($_POST['cu_co_name']) && !empty($_FILES['cu_office_address']))
    {
        $sqle = "SELECT customers.*, customer_docs.* FROM customers, customer_docs WHERE customers.cu_phone = '".$_POST['cu_phone']."' AND 
                customers.cu_phone = customer_docs.doc_owner_phone";
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

            $file_name1 = $_FILES['cu_office_address']['name'];
            $file_size1 = $_FILES['cu_office_address']['size'];
            $file_tmp1 = $_FILES['cu_office_address']['tmp_name'];
            $file_type1 = $_FILES['cu_office_address']['type'];
            
            if($file_size1 >= 200000)
            {
                $responseData = ['success' => '0', 'message' => 'Office address file size must be less than 200 kb'];
                echo json_encode($responseData, JSON_PRETTY_PRINT);

                http_response_code(400);
            }
            else
            {
                $file_name1 = $_FILES["cu_office_address"]["name"];
                $rn1 = explode(".", $file_name1);
                $extension1 = end($rn1);
                $office_address = "office_address.".$extension1;

                $des = "../../assets/documents/shippers/shipper_".$rowe['cu_phone']."/".$office_address;
                $dir = "../../assets/documents/shippers/shipper_".$rowe['cu_phone']."/";

                if(!is_dir($dir))
                {
                    mkdir("../../assets/documents/shippers/shipper_".$rowe['cu_phone']);
                }

                array_map('unlink', glob("$dir/office_address.*"));

                move_uploaded_file($file_tmp1, $des);

                $des1 = "assets/documents/shippers/shipper_".$rowe['cu_phone']."/".$office_address;

                $d_sql11 = "update customer_docs set doc_location = '".$_POST['cu_co_name']."', doc_verified = 1 where doc_owner_phone = '".$_POST['cu_phone']."' and doc_sr_num = 5";
                $d_run11 = mysqli_query($link, $d_sql11);

                $d_sql = "update customer_docs set doc_location = '$des1' where doc_owner_phone = '".$_POST['cu_phone']."' and doc_sr_num = 6";
                $d_run = mysqli_query($link, $d_sql);

                if($d_run)
                {
                    $responseData = ['success' => '1', 'message' => 'Office address uploaded'];
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