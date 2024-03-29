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
                <div class="card-stats-items" style="margin-bottom: 1vh;">
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
                </div>
                <div class="card-stats-items">
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
                </div>
            ';
        }
        else
        {
            $output = 
            '
            <div class="card-stats-items" style="margin-bottom: 1vh;">
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
            </div>
            <div class="card-stats-items">
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
            </div>
            ';
        }
        
        echo $output;

    }
    elseif(isset($_POST["chart_action"]))
    {
        $query = "select * from cust_order where or_default = 1";

        $year = '';

        if(!empty($_POST["chart_year"]))
        {
            $query .= " and DATE_FORMAT(or_active_on, '%Y') = '".$_POST["chart_year"]."'";
            $year = " and DATE_FORMAT(or_active_on, '%Y') = '".$_POST["chart_year"]."'";
        }
        else
        {
            $query .= " and DATE_FORMAT(or_active_on, '%Y') = '".date('Y')."'";
            $year = " and DATE_FORMAT(or_active_on, '%Y') = '".date('Y')."'";
        }

        $statement = $connect->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        $total_row = $statement->rowCount();

        $output = array();
        $i = 1;
        $uptoMonth = date('n');

        if($total_row > 0)
        {
            for($i; $i <= $uptoMonth; $i++)
            {
                $complete = "select * from cust_order where DATE_FORMAT(or_active_on, '%m') = ".$i.$year;
                $run_complete = mysqli_query($link, $complete);
                $count_complete = mysqli_num_rows($run_complete);
                
                $output[] = ["y" => $count_complete, "label" => date("M", mktime(0, 0, 0, $i, 10))];
            }
        }
        else
        {
            for($i; $i <= 12; $i++)
            {
                $output[] = ["y" => 0, "label" => date("M", mktime(0, 0, 0, $i, 10))];
            }
        }
        echo json_encode($output);

    }
    elseif(isset($_POST["profitchart_action"]))
    {
        $query = "select * from subscribed_users where subs_default = 1";

        $year = '';

        if(!empty($_POST["profitchart_year"]))
        {
            $query .= " and DATE_FORMAT(payment_datetime, '%Y') = '".$_POST["profitchart_year"]."'";
            $year = " and DATE_FORMAT(payment_datetime, '%Y') = '".$_POST["profitchart_year"]."'";
        }
        else
        {
            $query .= " and DATE_FORMAT(payment_datetime, '%Y') = '".date('Y')."'";
            $year = " and DATE_FORMAT(payment_datetime, '%Y') = '".date('Y')."'";
        }

        $statement = $connect->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        $total_row = $statement->rowCount();

        $shippersss = $ownersss = $trucksss = array();
        $i = 1;
        $uptoMonth = date('n');

        if($total_row > 0)
        {
            for($i; $i <= $uptoMonth; $i++)
            {
                $shipper = "select sum(subs_amount) from subscribed_users where subs_user_type = 1 and DATE_FORMAT(payment_datetime, '%m') = ".$i.$year;
                $run_shipper = mysqli_query($link, $shipper);
                $row_shipper = mysqli_fetch_array($run_shipper, MYSQLI_ASSOC);

                if(!empty($row_shipper['sum(subs_amount)']))
                {
                    $total_shipper = $row_shipper['sum(subs_amount)'];
                }
                else
                {
                    $total_shipper = 0;
                }


                $owner = "select sum(subs_amount) from subscribed_users where subs_user_type = 2 and DATE_FORMAT(payment_datetime, '%m') = ".$i.$year;
                $run_owner = mysqli_query($link, $owner);
                $row_owner = mysqli_fetch_array($run_owner, MYSQLI_ASSOC);

                if(!empty($row_owner['sum(subs_amount)']))
                {
                    $total_owner = $row_owner['sum(subs_amount)'];
                }
                else
                {
                    $total_owner = 0;
                }



                $truck = "select sum(subs_amount) from subscribed_users where subs_user_type = 3 and DATE_FORMAT(payment_datetime, '%m') = ".$i.$year;
                $run_truck = mysqli_query($link, $truck);
                $row_truck = mysqli_fetch_array($run_truck, MYSQLI_ASSOC);

                if(!empty($row_truck['sum(subs_amount)']))
                {
                    $total_truck = $row_truck['sum(subs_amount)'];
                }
                else
                {
                    $total_truck = 0;
                }

                $shippersss[] = ['y' => $total_shipper, 'label' => date("M", mktime(0, 0, 0, $i, 10))];
                $ownersss[] = ['y' => $total_owner, 'label' => date("M", mktime(0, 0, 0, $i, 10))];
                $trucksss[] = ['y' => $total_truck, 'label' => date("M", mktime(0, 0, 0, $i, 10))];
            }

            $output = ["shipper" => $shippersss, "owner" => $ownersss, "truck" => $trucksss];
        }
        else
        {
            for($i; $i <= 12; $i++)
            {
                $shippersss[] = ["y" => 0, "label" => date("M", mktime(0, 0, 0, $i, 10))];
                $ownersss[] = ["y" => 0, "label" => date("M", mktime(0, 0, 0, $i, 10))];
                $trucksss[] = ["y" => 0, "label" => date("M", mktime(0, 0, 0, $i, 10))];
            }
            $output = ["shipper" => $shippersss, "owner" => $ownersss, "truck" => $trucksss];
        }

        echo json_encode($output, JSON_NUMERIC_CHECK);
    }
    elseif(isset($_POST["notify_action"]))
    {
        $query = "SELECT * FROM notifications WHERE no_default = 1";

        if(isset($_POST["seen"]))
        {
            $query .= " AND no_status = '1'";
        }

        if(isset($_POST["not_seen"]))
        {
            $query .= " AND no_status = '0'";
        }

        if(isset($_POST["notify_action"]))
        {
            $query .= " order by no_id desc";
        }

        $statement = $connect->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        $total_row = $statement->rowCount();

        $output = '';
        
        if($total_row > 0)
        {
            foreach($result as $row)
            {
                
                if($row['no_status'] == 0)
                {
                    $beep = "beep";
                }
                else
                {
                    $beep = "";
                }

                $link = "";

                if($row['no_title'] == "Bidding" || $row['no_title'] == "Load" || $row['no_title'] == "Load Payment" || $row['no_title'] == "Trip started" || $row['no_title'] == "Trip completed" || 
                    $row['no_title'] == "Deal accepted" || $row['no_title'] == "Deal cancelled" || $row['no_title'] == "Trucks assigned")
                {
                    $link = "loads_by_id?load_id=".$row['id'];
                }
                
                if($row['no_title'] == "New Load")
                {
                    $link = "loads";
                }
                
                if($row['no_title'] == "Shipper Subscription" || $row['no_title'] == "Owner Subscription" || $row['no_title'] == "Truck Subscription")
                {
                    $link = "subscribed_users?order_id=".$row['id'];
                }
                
                if($row['no_title'] == "Shipper Docs")
                {
                    $link = "shipper_profile?shipper_id=".$row['id'];
                }
                
                if($row['no_title'] === "New Shipper Registered")
                {
                    $link = "shippers";
                }
                
                if($row['no_title'] == "Driver Docs" || $row['no_title'] == "Truck docs" || $row['no_title'] == "Truck status")
                {
                    $link = "truck_profile?truck_id=".$row['id'];
                }
                
                if($row['no_title'] == "New Truck Registered")
                {                    
                    $link = "trucks";
                }
                
                if($row['no_title'] == "Owner docs" || $row['no_title'] == "Owner details" || $row['no_title'] == "Truck removed")
                {
                    $link = "truck_owner_profile?owner_id=".$row['id'];
                }
                
                if($row['no_title'] == "New Owner Registered")
                {
                    $link = "truck_owners";
                }
                
                $no_day = date_format(date_create($row['no_date_time']), 'd M, Y h:i A');

                $output .=
                '
                    <li class="media">
                        <div class="media-body">
                            <div class="float-right" style="margin-right: 10px">
                                <div class="font-weight-600 text-muted text-small">
                                    <span class="'.$beep.'"></span>
                                </div>
                            </div>
                            <div id="link_id'.$row['no_id'].'" style="cursor: pointer; text-decoration: none;" class="media-title">'.$row['no_message'].'</div>
                            <div class="mt-1">
                                <div class="budget-price">
                                    <div class="budget-price-label" style="margin-left: 0 !important">
                                    '.$no_day.'
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <script>
                        $(document).ready(function(){
                            $("#link_id'.$row['no_id'].'").on("click", function(){
                                $.ajax({
                                    type: "POST",
                                    url: "processing/curd_dashboard.php",
                                    data: {notification_id:'.$row['no_id'].'},
                                    success: function(data)
                                    {
                                        if(data == "Seen")
                                        {
                                            location.href="'.$link.'";
                                        }
                                    }
                                });
                            });
                        });
                    </script>
                ';
                
            }

        }
        else
        {
            $output = 
            '
                
            ';
        }
        
        echo $output;

    }
    elseif(isset($_POST['notification_id']))
    {
        $sql = "update notifications set no_status = 1 where no_id = '".$_POST['notification_id']."'";
        $run = mysqli_query($link, $sql);

        if($run)
        {
            echo "Seen";
        }
        else
        {
            echo "error";
        }
    }
    else
    {
        echo "Server error";
    }
?>