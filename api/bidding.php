<?php
    include('../dbcon.php');
    include('../FCM/notification.php');

    header('Content-Type: application/json');

    if(isset($_POST['get_user_type']) && isset($_POST['get_user_id']))
    {
        $sql = "select * from bidding where bid_user_type = '".$_POST['get_user_type']."' and bid_user_id = '".$_POST['get_user_id']."'";
        $run = mysqli_query($link, $sql);
        while($row = mysqli_fetch_array($run, MYSQLI_ASSOC))
        {
            $responseData[] = ['bid id' => $row['bid_id'], 'load id' => $row['load_id'], 'price' => $row['bid_expected_price']];
        }
        echo json_encode($responseData, JSON_PRETTY_PRINT);
        http_response_code(200);
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
            $owner = "select * from truck_owners where to_id = '".$_POST['user_type']."' and to_verified = 1";
            $run_owner = mysqli_query($link, $owner);
            $count_owner = mysqli_num_rows($run_owner);
            if($count_owner == 0)
            {
                $responseData = ['success' => '0', 'message' => 'Your documents are not verified.'];
                echo json_encode($responseData, JSON_PRETTY_PRINT);
                http_response_code(400);
            }
            else
            {
                $sqlee1 = "SELECT * FROM cust_order where or_id = '".$_POST['load_id']."'";
                $checkee1 = mysqli_query($link, $sqlee1);
                $rowee1 = mysqli_fetch_array($checkee1, MYSQLI_ASSOC);

                $including_commision = round(($rowee1['or_expected_price'] - ($rowee1['or_expected_price'] * ($rowee1['or_admin_expected_price']/100))), 2);

                if($_POST['expected_price'] > $including_commision)
                {
                    $responseData = ['success' => '0', 'message' => 'Bidding price is greater than shipper expected price'];
                    echo json_encode($responseData, JSON_PRETTY_PRINT);
                    http_response_code(400);
                }
                else
                {
                    $sqlee12 = "SELECT * FROM customers where cu_id = '".$rowee1['or_cust_id']."'";
                    $checkee12 = mysqli_query($link, $sqlee12);
                    $rowee12 = mysqli_fetch_array($checkee12, MYSQLI_ASSOC);

                    if($rowee1['or_shipper_on'] == 1 || $rowee1['or_shipper_on'] == 2 || $rowee1['or_shipper_on'] == 3)
                    {
                        $including_commision = round(($_POST['expected_price'] + ($_POST['expected_price'] * ($rowee1['or_admin_expected_price']/100))), 2);

                        $sql = "insert into bidding (bid_user_type, bid_user_id, load_id, bid_expected_price, bid_status) values ('".$_POST['user_type']."', '".$_POST['user_id']."', 
                            '".$_POST['load_id']."', '$including_commision', 1)";
                        $run = mysqli_query($link, $sql);

                        if($run)
                        {
                            $device_id = $rowee12['cu_token'];
                            $title = "New Bidding";
                            $message = "Your load with ID ".$rowee1['or_id']." has a new bid";

                            $sent = push_notification_android($device_id, $title, $message);

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
                        $responseData = ['success' => '0', 'message' => 'Server error'];
                        echo json_encode($responseData, JSON_PRETTY_PRINT);
                        http_response_code(400);
                    }
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