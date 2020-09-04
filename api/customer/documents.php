<?php

    include('../../dbcon.php');

    header('Content-Type: application/json');

    if(isset($_POST['customer_id']))
    {
        $sql = "select * from customers where cu_id = '".$_POST['customer_id']."'";
        $g_sql = mysqli_query($link, $sql);
        $row = mysqli_fetch_array($g_sql, MYSQLI_ASSOC);

        if($row['cu_address_type'] == 1)
        {
            $add_type = "Aadhar Card";
        }
        elseif($row['cu_address_type'] == 2)
        {
            $add_type = "Voter ID";
        }
        elseif($row['cu_address_type'] == 3)
        {
            $add_type = "Passport";
        }
        elseif($row['cu_address_type'] == 4)
        {
            $add_type = "Driving License";
        }
        else
        {
            $add_type = "No Document found";
        }

        $doc = "select * from customer_docs where doc_owner_phone = '".$row['cu_phone']."' and doc_sr_num = 1";
        $r_doc = mysqli_query($link, $doc);
        $pan_card = mysqli_fetch_array($r_doc, MYSQLI_ASSOC);

        $doc1 = "select * from customer_docs where doc_owner_phone = '".$row['cu_phone']."' and doc_sr_num = 2";
        $r_doc1 = mysqli_query($link, $doc1);
        $address_f = mysqli_fetch_array($r_doc1, MYSQLI_ASSOC);

        $doc2 = "select * from customer_docs where doc_owner_phone = '".$row['cu_phone']."' and doc_sr_num = 3";
        $r_doc2 = mysqli_query($link, $doc2);
        $address_b = mysqli_fetch_array($r_doc2, MYSQLI_ASSOC);

        $doc3 = "select * from customer_docs where doc_owner_phone = '".$row['cu_phone']."' and doc_sr_num = 4";
        $r_doc3 = mysqli_query($link, $doc3);
        $selfie = mysqli_fetch_array($r_doc3, MYSQLI_ASSOC);

        $doc4 = "select * from customer_docs where doc_owner_phone = '".$row['cu_phone']."' and doc_sr_num = 5";
        $r_doc4 = mysqli_query($link, $doc4);
        $com_name = mysqli_fetch_array($r_doc4, MYSQLI_ASSOC);

        $doc5 = "select * from customer_docs where doc_owner_phone = '".$row['cu_phone']."' and doc_sr_num = 6";
        $r_doc5 = mysqli_query($link, $doc5);
        $office_address = mysqli_fetch_array($r_doc5, MYSQLI_ASSOC);

        $responseData = ['id' => $row['cu_id'], 'phone number' => $row['cu_phone'],
                            'pan card' => $pan_card['doc_location'], 'pan card verified' => $pan_card['doc_verified'],
                            'address type' => $add_type, 'address front' => $address_f['doc_location'], 'address front verified' => $address_f['doc_verified'],
                                                            'address back' => $address_b['doc_location'], 'address back verified' => $address_b['doc_verified'],
                            'selfie' => $selfie['doc_location'], 'selfie verified' => $selfie['doc_verified'],
                            'company name' => $com_name['doc_location'], 'company name verified' => $com_name['doc_verified'],
                            'office address' => $office_address['doc_location'], 'office address verified' => $office_address['doc_verified']
                        ];
        echo json_encode($responseData, JSON_PRETTY_PRINT);
        http_response_code(200);
    }
    else
    {
        $responseData = ['success' => '0', 'message' => 'Something is missing'];
        echo json_encode($responseData, JSON_PRETTY_PRINT);
    }

?>