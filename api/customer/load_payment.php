<?php
    include('../../dbcon.php');

    header('Content-Type: application/json');

    if(isset($_POST['pay_id']) && isset($_POST['razorpay_order_id']) && isset($_POST['razorpay_payment_id']) && isset($_POST['razorpay_signature']) && isset($_POST['pay_method']))
    {
        $date = date("Y-m-d H:i:s");

        $check = "select * from load_payments where pay_id = '".$_POST['pay_id']."'";
        $run_check = mysqli_query($link, $check);
        $row_check = mysqli_fetch_array($run_check, MYSQLI_ASSOC);

        if($row_check['pay_status'] == 1)
        {
            $responseData = ['success' => '0', 'message' => 'Payment already done'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
            http_response_code(400);
        }
        else
        {
            if($_POST['pay_method'] == 1 && !empty($_POST['razorpay_order_id']) && !empty($_POST['razorpay_payment_id']) && !empty($_POST['razorpay_signature']))
            {
                $sql = "update load_payments set razorpay_order_id = '".$_POST['razorpay_order_id']."', razorpay_payment_id = '".$_POST['razorpay_payment_id']."', razorpay_signature = '".$_POST['razorpay_signature']."', payment_date = '$date', pay_method = 1, pay_status = 1 where pay_id = '".$_POST['pay_id']."'";

                $run = mysqli_query($link, $sql);

                if($run)
                {
                    $responseData = ['success' => '1', 'message' => 'Payment complete'];
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
                function generateRandomString($length = 15)
                {
                    $characters = 'aAbBc0CdDeE1fFgGh2HiIjJ3kKlLm4MnNoO5pPqQr6RsStT7uUvVw8WxXyY9zZ';
                    $charactersLength = strlen($characters);
                    $randomString = '';
                    for ($i = 0; $i < $length; $i++)
                    {
                        $randomString .= $characters[rand(0, $charactersLength - 1)];
                    }
                    return $randomString;
                }

                $order = 'order_'.generateRandomString();
                $payid = 'pay_'.generateRandomString();

                $sql = "update load_payments set razorpay_order_id = '$order', razorpay_payment_id = '$payid', razorpay_signature = '0', payment_date = '$date', pay_method = '2', pay_status = 1 where pay_id = '".$_POST['pay_id']."'";

                $run = mysqli_query($link, $sql);

                if($run)
                {
                    $responseData = ['success' => '1', 'message' => 'Payment complete'];
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