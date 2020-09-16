<?php
    include('../../dbcon.php');

    header('Content-Type: application/json');

    if(isset($_POST['load_id']))
    {
        $sql = "select * from bidding where load_id = '".$_POST['load_id']."' and bid_status = 1";
        $run = mysqli_query($link, $sql);
        $counte = mysqli_num_rows($run);
        if($counte >= 1)
        {
            while($row = mysqli_fetch_array($run, MYSQLI_ASSOC))
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

                $responseData[] = ['bid id' => $row['bid_id'], 'bidder price' => $row['bid_expected_price'], 'bidder type' => $bidder_type, 'bidder details' => $bidder_detials];
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
    elseif(isset($_POST['bid_id']) && isset($_POST['bid_accept']))
    {
        $update = "update bidding set bid_status = '".$_POST['bid_accept']."' where bid_id = '".$_POST['bid_id']."'";
        $run = mysqli_query($link, $update);

        if($run)
        {
            
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