<?php
    include('../../dbcon.php');

    header('Content-Type: application/json');

    if(isset($_POST['truck_id']) && isset($_POST['del_trk_id']) && isset($_POST['lat']) && isset($_POST['lng']))
    {
        $date = date('Y-m-d H:i:s');

        if(empty($_POST['del_trk_id']))
        {
            $location = "update trucks set trk_lat = '".$_POST['lat']."', trk_lng = '".$_POST['lng']."', trk_last_updated_at = '$date' where trk_id = '".$_POST['truck_id']."'";
            $run_loc = mysqli_query($link, $location);

            if($run_loc)
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
            $active = "update delivery_trucks set lat = '".$_POST['lat']."', lng = '".$_POST['lng']."', last_updated_at = '$date' where del_trk_id = '".$_POST['del_trk_id']."' and trk_id = '".$_POST['truck_id']."'";
            $set = mysqli_query($link, $active);

            if($set)
            {
                mysqli_query($link, "update trucks set trk_lat = '".$_POST['lat']."', trk_lng = '".$_POST['lng']."', trk_last_updated_at = '$date' where trk_id = '".$_POST['truck_id']."'");

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
    }
    else
    {
        $responseData = ['success' => '0', 'message' => 'Something is missing'];
        echo json_encode($responseData, JSON_PRETTY_PRINT);

        http_response_code(206);
    }

    mysqli_close($link);
?>