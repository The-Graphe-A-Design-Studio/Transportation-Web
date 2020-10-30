<?php
    include('../dbcon.php');

    header('Content-Type: application/json');

    if(isset($_POST['truck_id']))
    {
        $sql = "select * from trucks where trk_id = '".$_POST['truck_id']."'";
        $run = mysqli_query($link, $sql);
        $row = mysqli_fetch_array($run, MYSQLI_ASSOC);

        $responseData = ['truck id' => $row['trk_id'], 'lat' => $row['trk_lat'], 'lng' => $row['trk_lng']];
        echo json_encode($responseData, JSON_PRETTY_PRINT);

        http_response_code(200);
    }
    else
    {
        $responseData = ['success' => '0', 'message' => 'Something is missing'];
        echo json_encode($responseData, JSON_PRETTY_PRINT);

        http_response_code(206);
    }

    mysqli_close($link);
?>