<?php
    include('../../dbcon.php');

    header('Content-Type: application/json');

    if(isset($_POST['user_type']) && isset($_POST['user_id']) && isset($_POST['load_id']) && isset($_POST['expected_price']))
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
            $sql = "insert into bidding (bid_user_type, bid_user_id, load_id, bid_expected_price) values ('".$_POST['user_type']."', '".$_POST['user_id']."', '".$_POST['load_id']."', 
                '".$_POST['expected_price']."')";
            $run = mysqli_query($link, $sql);

            if($run)
            {
                $responseData[] = ['success' => '1', 'message' => 'Successful'];
                echo json_encode($responseData, JSON_PRETTY_PRINT);
                http_response_code(200);
            }
            else
            {
                $responseData[] = ['success' => '0', 'message' => 'Unsuccessful'];
                echo json_encode($responseData, JSON_PRETTY_PRINT);
                http_response_code(400);
            }
        }
    }
    else
    {
        $responseData = ['success' => '0', 'message' => 'Something is missing'];
        echo json_encode($responseData, JSON_PRETTY_PRINT);

        http_response_code(206);
    }
?>