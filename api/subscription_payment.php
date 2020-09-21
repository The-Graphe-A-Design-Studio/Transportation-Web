<?php
    include('../dbcon.php');

    header('Content-Type: application/json');

    date_default_timezone_set("Asia/Kolkata");

    if(isset($_POST['user_type']) && isset($_POST['user_id']) && isset($_POST['amount']) && isset($_POST['duration']) && isset($_POST['razorpay_order_id']) && 
        isset($_POST['razorpay_payment_id']) && isset($_POST['razorpay_signature']))
    {
        $date = date("Y-m-d H:i:s");

        $days = $_POST['duration'] * 30.4167;

        $expire_date = date('Y-m-d H:i:s', strtotime($date. ' +'.round($days).' days'));

        $sql = "insert into subscribed_users (subs_user_type, subs_user_id, subs_amount, subs_duration, razorpay_order_id, razorpay_payment_id, razorpay_signature, 
                payment_datetime, expire_datetime) values ('".$_POST['user_type']."', '".$_POST['user_id']."', '".$_POST['amount']."', '".$_POST['duration']."', 
                '".$_POST['razorpay_order_id']."', '".$_POST['razorpay_payment_id']."', '".$_POST['razorpay_signature']."', '$date', '$expire_date')";
        $run = mysqli_query($link, $sql);

        if($run)
        {
            if($_POST['user_type'] == 1)
            {
                $shipper = "update customers set cu_account_on = 2, cu_subscription_start_date = '$date', cu_subscription_order_id = '".$_POST['razorpay_order_id']."', 
                            cu_subscription_expire_date = '$expire_date' where cu_id = '".$_POST['user_id']."'";
                $run_ship = mysqli_query($link, $shipper);

                if($run_ship)
                {
                    $responseData = ['success' => '1', 'message' => 'Thank you for subscribing'];
                    echo json_encode($responseData, JSON_PRETTY_PRINT);
                    http_response_code(200);
                }
                else
                {
                    $responseData = ['success' => '0', 'message' => 'Something went wrong'];
                    echo json_encode($responseData, JSON_PRETTY_PRINT);
                    http_response_code(206);
                }
            }
            elseif($_POST['user_type'] == 2)
            {
                $shipper = "update truck_owners set to_account_on = 1, to_subscription_start_date = '$date', to_subscription_order_id = '".$_POST['razorpay_order_id']."', 
                            to_subscription_expire_date = '$expire_date' where to_id = '".$_POST['user_id']."'";
                $run_ship = mysqli_query($link, $shipper);

                if($run_ship)
                {
                    $responseData = ['success' => '1', 'message' => 'Thank you for subscribing'];
                    echo json_encode($responseData, JSON_PRETTY_PRINT);
                    http_response_code(200);
                }
                else
                {
                    $responseData = ['success' => '0', 'message' => 'Something went wrong'];
                    echo json_encode($responseData, JSON_PRETTY_PRINT);
                    http_response_code(206);
                }
            }
            else
            {
                $responseData = ['success' => '0', 'message' => 'User type not found'];
                echo json_encode($responseData, JSON_PRETTY_PRINT);
                http_response_code(206);
            }
        }
        else
        {
            $responseData = ['success' => '0', 'message' => 'Server error'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);

            http_response_code(206);
        }
    }
    else
    {
        $responseData = ['success' => '0', 'message' => 'Something is missing'];
        echo json_encode($responseData, JSON_PRETTY_PRINT);

        http_response_code(206);
    }
?>