<?php
    //open connection to mysql db
    include('../dbcon.php');

    //fetch table rows from mysql db
    $sql = "select * from subscription_plans where plan_type = 3";
    $result = mysqli_query($link, $sql) or die("Error in Selecting " . mysqli_error($link));

    //create an array
    $emparray = array();
    while($row =mysqli_fetch_assoc($result))
    {
        if($row['plan_duration'] > 1)
        {
            $truck = $row['plan_duration'].' Trucks';
        }
        else
        {
            $truck = $row['plan_duration'].' Truck';
        }

        $total_price = round(($row['plan_selling_price'] + ($row['plan_selling_price'] * 0.18)), 2);

        $discount = ( ( ( $row['plan_original_price'] - $row['plan_selling_price'] ) / $row['plan_original_price'] ) * 100 );

        $emparray[] = ['plan id' => $row['plan_id'], 'plan name' => $row['plan_name'], 'plan type' => $row['plan_type'], 'plan original price' => $row['plan_original_price'], 'plan selling price' => $row['plan_selling_price'], 
                        'plan discount' => round($discount, 2).'%', 'GST' => '18%', 'final price' => "$total_price", 'quantity' => $trucks];
    }
    header('Content-Type: application/json');
    echo json_encode($emparray, JSON_PRETTY_PRINT);
    http_response_code(200);

    //close the db connection
    mysqli_close($link);
?>