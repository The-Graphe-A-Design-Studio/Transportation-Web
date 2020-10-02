<?php
    include('../../dbcon.php');

    header('Content-Type: application/json');

    if(isset($_POST['del_trk_id']) && isset($_POST['lat']) && isset($_POST['lng']))
    {
        $active = "update delivery_trucks set lat = '".$_POST['lat']."', lng = '".$_POST['lng']."' where del_trk_id = '".$_POST['del_trk_id']."'";
        $set = mysqli_query($link, $active);

        if($set)
        {
            $responseData = ['success' => '1', 'message' => 'Location updated'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
            http_response_code(200);
        }
        else
        { 
            $responseData = ['success' => '0', 'message' => 'Location update failed'];
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