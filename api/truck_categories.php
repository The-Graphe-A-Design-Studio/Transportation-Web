<?php
    //open connection to mysql db
    include('../dbcon.php');

    //fetch table rows from mysql db
    $sql = "select * from truck_cat";
    $result = mysqli_query($link, $sql) or die("Error in Selecting " . mysqli_error($link));

    //create an array
    $emparray = array();
    while($row =mysqli_fetch_assoc($result))
    {
        $emparray[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($emparray, JSON_PRETTY_PRINT);
    http_response_code(200);

    //close the db connection
    mysqli_close($link);
?>