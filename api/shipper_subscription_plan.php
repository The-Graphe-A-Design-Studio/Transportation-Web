<?php
    //open connection to mysql db
    include('../dbcon.php');

    //fetch table rows from mysql db
    $sql = "select * from subscription_plans where plan_type = 1";
    $result = mysqli_query($link, $sql) or die("Error in Selecting " . mysqli_error($link));

    //create an array
    $emparray = array();
    while($row =mysqli_fetch_assoc($result))
    {
        $discount = ( ( ( $row['plan_original_price'] - $row['plan_selling_price'] ) / $row['plan_original_price'] ) * 100 );
        $emparray[] = ['plan id' => $row['plan_id'], 'plan type' => $row['plan_type'], 'plan original price' => $row['plan_original_price'], 'plan selling price' => $row['plan_selling_price'], 
                        'plan discount' => $discount.'%', 'plan duration' => $row['plan_duration'].' months'];
    }
    header('Content-Type: application/json');
    echo json_encode($emparray, JSON_PRETTY_PRINT);
    http_response_code(200);

    //close the db connection
    mysqli_close($link);
?>