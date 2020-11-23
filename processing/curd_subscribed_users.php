<?php

    include('../session.php');

    $connect = new PDO("mysql:host=$hostName; dbname=$dbName", $userName, $password);

    if(isset($_POST["action"]))
    {
        $query = "select * from subscribed_users where subs_default = 1";

        if(isset($_POST["active"]))
        {
            $query .= " and subs_user_type = '1'";
        }

        if(isset($_POST["inactive"]))
        {
            $query .= " and subs_user_type = '2'";
        }

        if(isset($_POST["truck"]))
        {
            $query .= " and subs_user_type = '3'";
        }

        if(isset($_POST["nothing"]))
        {
            $query .= "";
        }

        if(isset($_POST['search']))
        {
            $se = $_POST['search'];
            $query .= " AND razorpay_order_id LIKE '$se%' order by subs_id desc";
        }

        $statement = $connect->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        $total_row = $statement->rowCount();

        $output = '';
        
        if($total_row > 0)
        {
            $output .=
            "
                <table>
                    <thead>
                        <th>ID</th>
                        <th>Phone</th>
                        <th>User Type</th>
                        <th>Order ID</th>
                        <th>Payment ID</th>
                        <th>Coupon</th>
                        <th>Amount</th>
                        <th>Duration / Quantity</th>
                        <th>Start On</th>
                        <th>Expire On</th>
                        <th>Days Left</th>
                    </thead>
                    <tbody>
            ";

            foreach($result as $row)
            {
                // Calculating the difference in timestamps 
                $diff = strtotime($row['expire_datetime']) - strtotime(date('Y-m-d H:i:s')); 
                    
                // 1 day = 24 hours 
                // 24 * 60 * 60 = 86400 seconds 
                $t_left = abs(round($diff / 86400));

                if($row['subs_user_type'] == 1)
                {
                    $cu = "select * from customers where cu_id = '".$row['subs_user_id']."'";
                    $run_cu = mysqli_query($link, $cu);
                    $row_cu = mysqli_fetch_array($run_cu, MYSQLI_ASSOC);

                    $phone = '<a href="shipper_profile?shipper_id='.$row_cu['cu_id'].'">+'.$row_cu['cu_phone_code'].' '.$row_cu['cu_phone'].'</a>';
                    $user = "Shipper";
                    $quan = "Months";
                    $start = date_format(date_create($row['payment_datetime']), 'd M, Y h:i A');
                    $expire = date_format(date_create($row['expire_datetime']), 'd M, Y h:i A');
                    $d = $t_left;
                }
                elseif($row['subs_user_type'] == 2)
                {
                    $to = "select * from truck_owners where to_id = '".$row['subs_user_id']."'";
                    $run_to = mysqli_query($link, $to);
                    $row_to = mysqli_fetch_array($run_to, MYSQLI_ASSOC);

                    $phone = '<a href="truck_owner_profile?owner_id='.$row_to['to_id'].'">+'.$row_to['to_phone_code'].' '.$row_to['to_phone'].'</a>';
                    $user = "Truck Owner";
                    $quan = "Months";
                    $start = date_format(date_create($row['payment_datetime']), 'd M, Y h:i A');
                    $expire = date_format(date_create($row['expire_datetime']), 'd M, Y h:i A');
                    $d = $t_left;
                }
                else
                {
                    $to = "select * from truck_owners where to_id = '".$row['subs_user_id']."'";
                    $run_to = mysqli_query($link, $to);
                    $row_to = mysqli_fetch_array($run_to, MYSQLI_ASSOC);

                    $phone = '<a href="truck_owner_profile?owner_id='.$row_to['to_id'].'">+'.$row_to['to_phone_code'].' '.$row_to['to_phone'].'</a>';
                    $user = "Truck";
                    $quan = "";
                    $start = "-";
                    $expire = "-";
                    $d = "-";
                }

                if(!empty($row['coupon']))
                {
                    $coupon = $row['coupon'];
                }
                else
                {
                    $coupon = "-";
                }
                
                
                $output .=
                '
                    <tr>
                        <td data-column="ID">'.$row['subs_id'].'</td>
                        <td data-column="Phone">'.$phone.'</td>
                        <td data-column="User Type">'.$user.'</td>
                        <td data-column="Order ID">'.$row['razorpay_order_id'].'</td>
                        <td data-column="Payment ID">'.$row['razorpay_payment_id'].'</td>
                        <td data-column="Coupon">'.$coupon.'</td>
                        <td data-column="Amount">Rs. '.round($row['subs_amount'], 2).'</td>
                        <td data-column="Duration / Quantity">'.$row['subs_duration'].' '.$quan.'</td>
                        <td data-column="Start On">'.$start.'</td>
                        <td data-column="Expire On">'.$expire.'</td>
                        <td data-column="Days Left">'.$d.'</td>
                    </tr>
                ';

            }

            $output .=
            '
                    </tbody>
                </table>
            ';
        }
        else
        {
            $output = 
            '
                <table>
                    <tbody>
                        <tr>
                            <td colspan="10">No subscribed users found</td>
                        </tr>
                    </tbody>
                </table>
            ';
        }
        
        echo $output;

    }
    elseif(isset($_POST['newplanName']) && isset($_POST['newplanType']) && isset($_POST['newplanOriginal']) && isset($_POST['newplanSelling']) && isset($_POST['newplanDuration']))
    {
        $sql = "insert into subscription_plans (plan_name, plan_type, plan_original_price, plan_selling_price, plan_duration) values ('".$_POST['newplanName']."', 
                '".$_POST['newplanType']."', '".$_POST['newplanOriginal']."', '".$_POST['newplanSelling']."', '".$_POST['newplanDuration']."')";
        $run = mysqli_query($link, $sql);

        if($run)
        {
            echo "New Plan Created";
        }
        else
        {
            echo "Something went wrong";
        }
    }
    elseif(isset($_POST['edit_plan_id']) && isset($_POST['edit_plan_name']) && isset($_POST['edit_plan_original']) && isset($_POST['edit_plan_selling']) && isset($_POST['edit_plan_duration']))
    {
        $sql = "update subscription_plans set plan_name = '".$_POST['edit_plan_name']."', plan_original_price = '".$_POST['edit_plan_original']."', 
                plan_selling_price = '".$_POST['edit_plan_selling']."', plan_duration = '".$_POST['edit_plan_duration']."' where plan_id = '".$_POST['edit_plan_id']."'";
        $run = mysqli_query($link, $sql);

        if($run)
        {
            echo "Plan Updated";
        }
        else
        {
            echo "Something went wrong";
        }
    }
    elseif(isset($_POST['plan_id']) && isset($_POST['plan_status']))
    {

        $sql = "update subscription_plans set plan_status = '".$_POST['plan_status']."' where plan_id = '".$_POST['plan_id']."'";
        $run = mysqli_query($link, $sql);

        if($run && $_POST['plan_status'] == 1)
        {
            echo "Plan Activated";
        }
        elseif($run && $_POST['plan_status'] == 0)
        {
            echo "Plan Deactivated";
        }
        else
        {
            echo "Something went wrong";
        }
    }
    elseif(isset($_POST['delete_plan_id']))
    {
        $sql = "delete from subscription_plans where plan_id = '".$_POST['delete_plan_id']."'";
        $run = mysqli_query($link, $sql);

        if($run)
        {
            echo "Plan Deleted";
        }
        else
        {
            echo "Something went wrong";
        }
    }
    else
    {
        echo "Server error";
    }
?>