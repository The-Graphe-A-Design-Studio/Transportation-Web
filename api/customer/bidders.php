<?php
    include('../../dbcon.php');

    header('Content-Type: application/json');

    if(isset($_POST['load_id']))
    {
        $sql = "select * from bidding where load_id = '".$_POST['load_id']."'";
        $run = mysqli_query($link, $sql);
        $counte = mysqli_num_rows($run);
        if($counte >= 1)
        {
            while($row = mysqli_fetch_array($run, MYSQLI_ASSOC))
            {
                if($row['bid_status'] == 1)
                {
                    $bidder_detials = array();
                
                    if($row['bid_user_type'] == 1)
                    {
                        $bidder_type = "Truck Owner";
                        $bidder_id = $row['bid_user_id'];

                        $owner = "select * from truck_owners where to_id = '$bidder_id'";
                        $get_owner = mysqli_query($link, $owner);
                        $row_owner = mysqli_fetch_array($get_owner, MYSQLI_ASSOC);
                        
                        $bidder_detials[] = ['bidder id' => $row_owner['to_id'], 'bidder name' => $row_owner['to_name'], 
                                            'bidder phone' => '+'.$row_owner['to_phone_code'].' '.$row_owner['to_phone']];
                    }
                    else
                    {
                        $bidder_type = "Driver";

                        $bidder_id = $row['bid_user_id'];

                        $driver = "select * from trucks where trk_id = '$bidder_id'";
                        $get_driver = mysqli_query($link, $driver);
                        $row_driver = mysqli_fetch_array($get_driver, MYSQLI_ASSOC);
                        
                        $bidder_detials[] = ['bidder id' => $row_driver['trk_id'], 'bidder name' => $row_driver['trk_dr_name'], 
                                            'bidder phone' => '+'.$row_driver['trk_dr_phone_code'].' '.$row_driver['trk_dr_phone']];
                    }

                    $loads = "select * from cust_order where or_id = '".$_POST['load_id']."'";
                    $get_loads = mysqli_query($link, $loads);
                    $row_loads = mysqli_fetch_array($get_loads, MYSQLI_ASSOC);

                    if($row_loads['or_shipper_on'] == 1)
                    {
                        $new_price = $row['bid_expected_price'] + ($row['bid_expected_price'] * ($row_loads['or_admin_expected_price']/100));
                    }
                    else
                    {
                        $new_price = $row['bid_expected_price'];
                    }

                    $responseData[] = ['bid id' => $row['bid_id'], 'bidder price' => "$new_price", 'bid status' => '1', 'bidder type' => $bidder_type, 
                    'bidder details' => $bidder_detials];
                }
                elseif($row['bid_status'] == 2)
                {
                    $bidder_detials = array();
                
                    if($row['bid_user_type'] == 1)
                    {
                        $bidder_type = "Truck Owner";
                        $bidder_id = $row['bid_user_id'];

                        $owner = "select * from truck_owners where to_id = '$bidder_id'";
                        $get_owner = mysqli_query($link, $owner);
                        $row_owner = mysqli_fetch_array($get_owner, MYSQLI_ASSOC);
                        
                        $bidder_detials[] = ['bidder id' => $row_owner['to_id'], 'bidder name' => $row_owner['to_name'], 
                                            'bidder phone' => '+'.$row_owner['to_phone_code'].' '.$row_owner['to_phone']];
                    }
                    else
                    {
                        $bidder_type = "Driver";

                        $bidder_id = $row['bid_user_id'];

                        $driver = "select * from trucks where trk_id = '$bidder_id'";
                        $get_driver = mysqli_query($link, $driver);
                        $row_driver = mysqli_fetch_array($get_driver, MYSQLI_ASSOC);
                        
                        $bidder_detials[] = ['bidder id' => $row_driver['trk_id'], 'bidder name' => $row_driver['trk_dr_name'], 
                                            'bidder phone' => '+'.$row_driver['trk_dr_phone_code'].' '.$row_driver['trk_dr_phone']];
                    }

                    $loads = "select * from cust_order where or_id = '".$_POST['load_id']."'";
                    $get_loads = mysqli_query($link, $loads);
                    $row_loads = mysqli_fetch_array($get_loads, MYSQLI_ASSOC);

                    if($row_loads['or_shipper_on'] == 1)
                    {
                        $new_price = $row['bid_expected_price'] + ($row['bid_expected_price'] * ($row_loads['or_admin_expected_price']/100));
                    }
                    else
                    {
                        $new_price = $row['bid_expected_price'];
                    }

                    $responseData[] = ['bid id' => $row['bid_id'], 'bidder price' => "$new_price", 'bid status' => '2', 'bidder type' => $bidder_type, 
                    'bidder details' => $bidder_detials];
                }
                else
                {

                }
            }
            echo json_encode($responseData, JSON_PRETTY_PRINT);
            http_response_code(200);
        }
        else
        {
            $responseData = ['success' => '0', 'message' => 'No Bidders found'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
            http_response_code(400);
        }
    }
    elseif(isset($_POST['load_id_for_accepting']) && isset($_POST['bid_id_for_accepting']))
    {
        $sql = "select * from bidding where load_id = '".$_POST['load_id_for_accepting']."' and bid_status = 2";
        $run = mysqli_query($link, $sql);
        $counte = mysqli_num_rows($run);
        if($counte >= 1)
        {
            $responseData = ['success' => '0', 'message' => 'One Bid is already accepted'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
            http_response_code(400);
        }
        else
        {
            $update = "update bidding set bid_status = 2 where bid_id = '".$_POST['bid_id_for_accepting']."'";
            $run = mysqli_query($link, $update);

            if($run)
            {

                $responseData = ['success' => '1', 'message' => 'Bid accepted'];
                echo json_encode($responseData, JSON_PRETTY_PRINT);
                http_response_code(200);
            }
            else
            {
                $responseData = ['success' => '0', 'message' => 'Something went wrong'];
                echo json_encode($responseData, JSON_PRETTY_PRINT);
                http_response_code(200);
            }
        }
    }
    elseif(isset($_POST['bid_id_for_rejecting']))
    {
        $update = "update bidding set bid_status = 1 where bid_id = '".$_POST['bid_id_for_rejecting']."'";
        $run = mysqli_query($link, $update);

        if($run)
        {

            $responseData = ['success' => '1', 'message' => 'Bid rejected'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
            http_response_code(200);
        }
        else
        {
            $responseData = ['success' => '0', 'message' => 'Something went wrong'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
            http_response_code(200);
        }
    }
    elseif(isset($_POST['bid_id_for_removing']))
    {
        $update = "delete from bidding where bid_id = '".$_POST['bid_id_for_removing']."'";
        $run = mysqli_query($link, $update);

        if($run)
        {

            $responseData = ['success' => '1', 'message' => 'Bid removed'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
            http_response_code(200);
        }
        else
        {
            $responseData = ['success' => '0', 'message' => 'Something went wrong'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
            http_response_code(200);
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