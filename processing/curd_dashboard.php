<?php

    include('../session.php');

    $connect = new PDO("mysql:host=$hostName; dbname=$dbName", $userName, $password);

    if(isset($_POST["action"]))
    {
        $query = "select * from cust_order where or_default = 1";

        $month = $year = '';

        if(!empty($_POST["month"]))
        {
            $query .= " and DATE_FORMAT(or_active_on, '%m') = '".$_POST["month"]."'";
            $month = " and DATE_FORMAT(or_active_on, '%m') = '".$_POST["month"]."'";
        }

        if(!empty($_POST["year"]))
        {
            $query .= " and DATE_FORMAT(or_active_on, '%Y') = '".$_POST["year"]."'";
            $year = " and DATE_FORMAT(or_active_on, '%Y') = '".$_POST["year"]."'";
        }

        // echo $query;

        $statement = $connect->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        $total_row = $statement->rowCount();

        // echo $total_row; 

        $output = '';
        
        if($total_row > 0)
        {
            $expire = "select * from cust_order where or_status = 0 ".$month.$year;
            $run_expire = mysqli_query($link, $expire);
            $count_expire = mysqli_num_rows($run_expire);

            $active = "select * from cust_order where or_status = 1 ".$month.$year;
            $run_active = mysqli_query($link, $active);
            $count_active = mysqli_num_rows($run_active);

            $hold = "select * from cust_order where or_status = 2 ".$month.$year;
            $run_hold = mysqli_query($link, $hold);
            $count_hold = mysqli_num_rows($run_hold);

            $cancel = "select * from cust_order where or_status = 3 ".$month.$year;
            $run_cancel = mysqli_query($link, $cancel);
            $count_cancel = mysqli_num_rows($run_cancel);

            $going = "select * from cust_order where or_status = 4 ".$month.$year;
            $run_going = mysqli_query($link, $going);
            $count_going = mysqli_num_rows($run_going);

            $complete = "select * from cust_order where or_status = 5 ".$month.$year;
            $run_complete = mysqli_query($link, $complete);
            $count_complete = mysqli_num_rows($run_complete);

            $output .=
            '
                <div class="card-stats-item">
                    <div class="card-stats-item-count" id="active">'.$count_active.'</div>
                    <div class="card-stats-item-label">Active</div>
                </div>
                <div class="card-stats-item">
                    <div class="card-stats-item-count" id="hold">'.$count_hold.'</div>
                    <div class="card-stats-item-label">On Hold</div>
                </div>
                <div class="card-stats-item">
                    <div class="card-stats-item-count" id="going">'.$count_going.'</div>
                    <div class="card-stats-item-label">On Going</div>
                </div>
                <div class="card-stats-item">
                    <div class="card-stats-item-count" id="complete">'.$count_complete.'</div>
                    <div class="card-stats-item-label">Completed</div>
                </div>
                <div class="card-stats-item">
                    <div class="card-stats-item-count" id="expire">'.$count_expire.'</div>
                    <div class="card-stats-item-label">Expired</div>
                </div>
                
                <div class="card-stats-item">
                    <div class="card-stats-item-count" id="cancel">'.$count_cancel.'</div>
                    <div class="card-stats-item-label">Cancelled</div>
                </div>
            ';
        }
        else
        {
            $output = 
            '
            <div class="card-stats-item">
                <div class="card-stats-item-count" id="active">0</div>
                <div class="card-stats-item-label">Active</div>
            </div>
            <div class="card-stats-item">
                <div class="card-stats-item-count" id="hold">0</div>
                <div class="card-stats-item-label">On Hold</div>
            </div>
            <div class="card-stats-item">
                <div class="card-stats-item-count" id="going">0</div>
                <div class="card-stats-item-label">On Going</div>
            </div>
            <div class="card-stats-item">
                <div class="card-stats-item-count" id="complete">0</div>
                <div class="card-stats-item-label">Completed</div>
            </div>
            <div class="card-stats-item">
                <div class="card-stats-item-count" id="expire">0</div>
                <div class="card-stats-item-label">Expired</div>
            </div>
            
            <div class="card-stats-item">
                <div class="card-stats-item-count" id="cancel">0</div>
                <div class="card-stats-item-label">Cancelled</div>
            </div>
            ';
        }
        
        echo $output;

    }
    else
    {
        echo "Server error";
    }

?>