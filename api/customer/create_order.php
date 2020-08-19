<?php
    include('../../dbcon.php');

    header('Content-Type: application/json');

    if(isset($_POST['source']) && isset($_POST['destination']) && isset($_POST['date']) && isset($_POST['material']) && isset($_POST['truck_type']) && 
    isset($_POST['load']) && isset($_POST['quote']))
    {
        date_default_timezone_set("Asia/Kolkata");
        $date = date('Y-m-d');

        $or_date = date_create($_POST["date"]);
        $or_date = date_format($or_date, "Y-m-d");

        $sql = "insert into cust_order (or_source, or_destination, or_date, or_material, or_truck_type, or_load, or_quote, or_registered) values ('".$_POST['source']."', 
                '".$_POST['destination']."', '$or_date', '".$_POST['material']."', '".$_POST['truck_type']."', '".$_POST['load']."', '".$_POST['quote']."', '$date')";
        $set = mysqli_query($link, $sql);

        if($set)
        {
            $responseData = ['success' => '1', 'message' => 'Order Created'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
            http_response_code(200);
        }
        else
        {
            $responseData = ['success' => '0', 'message' => 'Something went wrong. Error'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
            http_response_code(400);
        }
    }
?>