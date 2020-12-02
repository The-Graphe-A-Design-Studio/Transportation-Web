<?php
    include('../dbcon.php');

    header('Content-Type: application/json');

    if(isset($_POST['truck_id']))
    {
        $sql = "select * from trucks where trk_id = '".$_POST['truck_id']."'";
        $run = mysqli_query($link, $sql);
        $row = mysqli_fetch_array($run, MYSQLI_ASSOC);

        $responseData = ['truck id' => $row['trk_id'], 'lat' => $row['trk_lat'], 'lng' => $row['trk_lng'], 'last updated at' => date_format(date_create($row['trk_last_updated_at']), 'd M, Y h:i A')];
        echo json_encode($responseData, JSON_PRETTY_PRINT);

        http_response_code(200);
    }
    elseif(isset($_POST['delivery_truck_id']))
    {
        $sql = "select * from delivery_trucks where del_trk_id = '".$_POST['delivery_truck_id']."'";
        $run = mysqli_query($link, $sql);
        $row = mysqli_fetch_array($run, MYSQLI_ASSOC);

        $responseData = ['delivery truck id' => $row['del_trk_id'], 'lat' => $row['lat'], 'lng' => $row['lng'], 'last updated at' => date_format(date_create($row['last_updated_at']), 'd M, Y h:i A')];
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