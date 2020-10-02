<?php
    include('../dbcon.php');

    header('Content-Type: application/json');

    if(isset($_POST['driver_phone']))
    {
        $sql = "select trucks.*, delivery_trucks.* from trucks, delivery_trucks where trucks.trk_dr_phone = '".$_POST['driver_phone']."' and 
                trucks.trk_id = delivery_trucks.trk_id and trucks.trk_on_trip = 1";
        $run = mysqli_query($link, $sql);
        $count = mysqli_num_rows($run);
        
        if($count == 0)
        {
            $responseData = ['success' => '0', 'message' => 'No new delivery found'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
            http_response_code(400);
        }
        else
        {
            $row = mysqli_fetch_array($run, MYSQLI_ASSOC);

            $del = "select * from deliveries where del_id = '".$row['del_id']."'";
            $run_del = mysqli_query($link, $del);
            $row_del = mysqli_fetch_array($run_del, MYSQLI_ASSOC);

            $load = "select * from cust_order where or_id = '".$row_del['or_id']."'";
            $run_load = mysqli_query($link, $load);
            $row_load = mysqli_fetch_array($run_load, MYSQLI_ASSOC);

            $responseData = [''];
        }
    }
    elseif(isset($_POST['user_type']) && isset($_POST['user_id']) && isset($_POST['load_id']) && isset($_POST['expected_price']))
    {
        $sqle = "SELECT * FROM bidding where bid_user_type = '".$_POST['user_type']."' and bid_user_id = '".$_POST['user_id']."' and load_id = '".$_POST['load_id']."'";
        $checke = mysqli_query($link, $sqle);
        $rowe = mysqli_fetch_array($checke, MYSQLI_ASSOC);
        $counte = mysqli_num_rows($checke);
        if($counte >= 1)
        {
            $responseData = ['success' => '0', 'message' => 'Bidding already done'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
            http_response_code(400);
        }
        else
        {
            $sqlee1 = "SELECT * FROM cust_order where or_id = '".$_POST['load_id']."'";
            $checkee1 = mysqli_query($link, $sqlee1);
            $rowee1 = mysqli_fetch_array($checkee1, MYSQLI_ASSOC);

            if($rowee1['or_shipper_on'] == 2)
            {
                $sql = "insert into bidding (bid_user_type, bid_user_id, load_id, bid_expected_price, bid_status) values ('".$_POST['user_type']."', '".$_POST['user_id']."', 
                    '".$_POST['load_id']."', '".$_POST['expected_price']."', 1)";
                $run = mysqli_query($link, $sql);

                if($run)
                {
                    $responseData = ['success' => '1', 'message' => 'Successful'];
                    echo json_encode($responseData, JSON_PRETTY_PRINT);
                    http_response_code(200);
                }
                else
                {
                    $responseData = ['success' => '0', 'message' => 'Unsuccessful'];
                    echo json_encode($responseData, JSON_PRETTY_PRINT);
                    http_response_code(400);
                }
            }
            else
            {
                $sql = "insert into bidding (bid_user_type, bid_user_id, load_id, bid_expected_price) values ('".$_POST['user_type']."', '".$_POST['user_id']."', 
                    '".$_POST['load_id']."', '".$_POST['expected_price']."')";
                $run = mysqli_query($link, $sql);

                if($run)
                {
                    $responseData = ['success' => '1', 'message' => 'Successful'];
                    echo json_encode($responseData, JSON_PRETTY_PRINT);
                    http_response_code(200);
                }
                else
                {
                    $responseData = ['success' => '0', 'message' => 'Unsuccessful'];
                    echo json_encode($responseData, JSON_PRETTY_PRINT);
                    http_response_code(400);
                }
            }
        }
    }
    elseif(isset($_POST['bid_id']) && isset($_POST['edit_expected_price']))
    {
        $sql = "update bidding set bid_expected_price = '".$_POST['edit_expected_price']."', bid_status = 0 where bid_id = '".$_POST['bid_id']."'";
        $run = mysqli_query($link, $sql);

        if($run)
        {
            $responseData = ['success' => '1', 'message' => 'Update successful'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
            http_response_code(200);
        }
        else
        {
            $responseData = ['success' => '0', 'message' => 'Update unsuccessful'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
            http_response_code(400);
        }
    }
    elseif(isset($_POST['delete_bid_id']))
    {
        $sql = "delete from bidding where bid_id = '".$_POST['delete_bid_id']."'";
        $run = mysqli_query($link, $sql);

        if($run)
        {
            $responseData = ['success' => '1', 'message' => 'Successful'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
            http_response_code(200);
        }
        else
        {
            $responseData = ['success' => '0', 'message' => 'Unsuccessful'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
            http_response_code(400);
        }
    }
    else
    {
        $responseData = ['success' => '0', 'message' => 'Something is missing'];
        echo json_encode($responseData, JSON_PRETTY_PRINT);

        http_response_code(206);
    }

    mysqli_close($link);
?>