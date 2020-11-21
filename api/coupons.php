<?php
    //open connection to mysql db
    include('../dbcon.php');
    header('Content-Type: application/json');

    if(isset($_GET['user_type']) && isset($_GET['user_id']) && isset($_GET['coupon']))
    {
        $coup = "select * from coupons where co_name = '".$_GET['coupon']."' and co_users = '".$_GET['user_type']."'";
        $run_coup = mysqli_query($link, $coup);
        $count_coup = mysqli_num_rows($run_coup);

        if($count_coup == 0)
        {
            $responseData = ['success' => '0', 'message' => 'Coupon not valid'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
        }
        else
        {
            $row_coup = mysqli_fetch_array($run_coup, MYSQLI_ASSOC);

            $db_date_now = new DateTime(date('Y-m-d H:i:s'));
            $db_date2    = new DateTime(date_format(date_create($row_coup['co_expire_date']), 'Y-m-d H:i:s'));

            if($db_date_now > $db_date2)
            {
                $responseData = ['success' => '0', 'message' => 'Coupon expired'];
                echo json_encode($responseData, JSON_PRETTY_PRINT);
            }
            else
            {
                $sql = "select * from coupon_users where cu_coupon = '".$_GET['coupon']."' and cu_user_type = '".$_GET['user_type']."' and cu_user_id = '".$_GET['user_id']."'";
                $result = mysqli_query($link, $sql);
                $count = mysqli_num_rows($result);

                if($count == 0)
                {
                    $responseData = ['success' => '1', 'message' => 'Coupon valid'];
                    echo json_encode($responseData, JSON_PRETTY_PRINT);
                }
                else
                {
                    $responseData = ['success' => '0', 'message' => 'Already used'];
                    echo json_encode($responseData, JSON_PRETTY_PRINT);
                }
            }
        }        
    }

    //close the db connection
    mysqli_close($link);
?>