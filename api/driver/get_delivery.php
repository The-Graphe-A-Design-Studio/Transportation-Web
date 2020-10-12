<?php
    include('../../dbcon.php');

    header('Content-Type: application/json');

    if(isset($_POST['driver_phone']))
    {
        $sql = "select * from trucks where trk_dr_phone = '".$_POST['driver_phone']."'";
        $run = mysqli_query($link, $sql);
        $row = mysqli_fetch_array($run, MYSQLI_ASSOC);

        $del_sql = "select * from delivery_trucks where trk_id = '".$row['trk_id']."'";
        $del_run = mysqli_query($link, $del_sql);
        $del_count = mysqli_num_rows($del_run);

        if($del_count == 0)
        {
            $responseData = ['success' => '0', 'message' => 'No new delivery found'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
            http_response_code(400);
        }
        else
        {
            $responseData = array();
            while($del_row = mysqli_fetch_array($del_run, MYSQLI_ASSOC))
            {
                if($del_row['status'] == 1)
                {
                    $responseData[] = ['delivery id of truck' => $del_row['del_trk_id'], 'truck id' => $del_row['trk_id'], 'status' => '1', 'message' => 'trip started'];
                    
                }
                elseif($del_row['status'] == 2)
                {
                    $responseData[] = ['delivery id of truck' => $del_row['del_trk_id'], 'truck id' => $del_row['trk_id'], 'status' => '2', 'message' => 'trip completed'];
                }
                else
                {
                    $responseData[] = ['delivery id of truck' => $del_row['del_trk_id'], 'truck id' => $del_row['trk_id'], 'status' => '0', 'message' => 'trip not started'];
                }
            }
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