<?php

    include('../dbcon.php');

    header('Content-Type: application/json');

    if(isset($_POST['truck_cat_id']))
    {
        $sql = "SELECT * FROM truck_cat_type WHERE ty_cat = '".$_POST['truck_cat_id']."'";
        $result = mysqli_query($link, $sql);
        
        //create an array
        $emparray = array();
        while($row =mysqli_fetch_assoc($result))
        {
            $emparray[] = $row;
        }
        header('Content-Type: application/json');
        echo json_encode($emparray, JSON_PRETTY_PRINT);
        http_response_code(200);
    }
    else
    {
        $responseData = ['success' => '0', 'message' => 'Truck category missing'];
        echo json_encode($responseData, JSON_PRETTY_PRINT);

        http_response_code(206);
    }

?>