<?php
    include('../../dbcon.php');

    header('Content-Type: application/json');

    if(isset($_POST['delivery_truck_id']))
    {
        $sql = "select * from delivery_trucks where del_trk_id = '".$_POST['delivery_truck_id']."'";
        $run = mysqli_query($link, $sql);
        $count = mysqli_num_rows($run);
        $row = mysqli_fetch_array($run, MYSQLI_ASSOC);

        $tr = "select * from trucks where trk_id = '".$row['trk_id']."'";
        $run_tr = mysqli_query($link, $tr);
        $row_tr = mysqli_fetch_array($run_tr, MYSQLI_ASSOC);

        if($count == 0)
        {
            $responseData = ['success' => '0', 'message' => 'No new delivery found'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
            http_response_code(400);
        }
        else
        {          

            $del = "select * from deliveries where del_id = '".$row['del_id']."'";
            $run_del = mysqli_query($link, $del);
            $row_del = mysqli_fetch_array($run_del, MYSQLI_ASSOC);

            $load = "select * from cust_order where or_id = '".$row_del['or_id']."'";
            $run_load = mysqli_query($link, $load);
            $row_load = mysqli_fetch_array($run_load, MYSQLI_ASSOC);

            $sources = $destinations = $truck_types = array();
                    
            $source = "select * from cust_order_source where or_uni_code = '".$row_load['or_uni_code']."' order by so_id";
            $get_source = mysqli_query($link, $source);
            while($row_source = mysqli_fetch_array($get_source, MYSQLI_ASSOC))
            {
                $sources[] = ['source' => $row_source['or_source'], 'lat' => $row_source['or_source_lat'], 'lng' => $row_source['or_source_lng'], 
                                'city' => $row_source['or_source_city'], 'state' => $row_source['or_source_state']];
            }

            $destination = "select * from cust_order_destination where or_uni_code = '".$row_load['or_uni_code']."' order by des_id";
            $get_destination = mysqli_query($link, $destination);
            while($row_destination = mysqli_fetch_array($get_destination, MYSQLI_ASSOC))
            {
                $destinations[] = ['destination' => $row_destination['or_destination'], 'lat' => $row_destination['or_des_lat'], 'lng' => $row_destination['or_des_lng'], 
                'city' => $row_destination['or_des_city'], 'state' => $row_destination['or_des_state']];
            }
            
            if($row_load['or_price_unit'] == 1)
            {
                $unit = "tonnage";
            }
            else
            {
                $unit = "number of trucks";
            }

            $total_price = round(($row_del['deal_price'] * $row_del['quantity']), 2);

            if($row_load['or_payment_mode'] == 1)
            {
                $mode = "Negotiable";
            }
            elseif($row_load['or_payment_mode'] == 2)
            {
                $advance = round((($total_price * ($row_load['or_advance_pay']/100)) + ($total_price * ($row_load['or_admin_expected_price']/100))), 2);
                $left = round(($total_price * ((100 - $row_load['or_advance_pay'])/100)), 2);

                $check_ad = "select * from load_payments where delivery_id = '".$row_del['del_id']."' and load_id = '".$row_del['or_id']."' and cu_id = '".$row_del['cu_id']."' and
                            to_id = '".$row_del['to_id']."' and pay_mode = '1'";
                $run_check_ad = mysqli_query($link, $check_ad);
                $row_check_ad = mysqli_fetch_array($run_check_ad, MYSQLI_ASSOC);
                $count_check_ad = mysqli_num_rows($run_check_ad);
                if($count_check_ad == 0)
                {
                    $advance_status = ['pay id' => '', 'amount' => '', 'status' => ''];
                }
                else
                {
                    $without_service_tax = round(($row_check_ad['amount'] - ($total_price * (($row_load['or_admin_expected_price'])/100))), 2);
                    $advance_status = ['pay id' => $row_check_ad['pay_id'], 'amount' => "$without_service_tax", 'status' => $row_check_ad['pay_status']];
                }

                $check_left = "select * from load_payments where delivery_id = '".$row_del['del_id']."' and load_id = '".$row_del['or_id']."' and cu_id = '".$row_del['cu_id']."' and 
                            to_id = '".$row_del['to_id']."' and pay_mode = '2'";
                $run_check_left = mysqli_query($link, $check_left);
                $row_check_left = mysqli_fetch_array($run_check_left, MYSQLI_ASSOC);
                $count_check_left = mysqli_num_rows($run_check_left);
                if($count_check_left == 0)
                {
                    $left_status = ['pay id' => '', 'amount' => '', 'status' => ''];
                }
                else
                {
                    $left_status = ['pay id' => $row_check_left['pay_id'], 'amount' => $row_check_left['amount'], 'status' => $row_check_left['pay_status']];
                }

                $payment = ['advance amount' => $advance_status, 'remaining amount' => $left_status];
                
                $mode = ['mode' => '2', 'mode name' => 'Advance Pay', 'payment' => $payment];
            }
            else
            {
                $full = round($total_price, 2);

                $check_full = "select * from load_payments where delivery_id = '".$row_del['del_id']."' and load_id = '".$row_del['or_id']."' and cu_id = '".$row_del['cu_id']."' and 
                            to_id = '".$row_del['to_id']."' and pay_mode = '3'";
                $run_check_full = mysqli_query($link, $check_full);
                $row_check_full = mysqli_fetch_array($run_check_full, MYSQLI_ASSOC);
                $count_check_full = mysqli_num_rows($run_check_full);
                if($count_check_full == 0)
                {
                    $advance_status = ['pay id' => '', 'amount' => '', 'status' => ''];
                    $left_status = ['pay id' => '', 'amount' => '', 'status' => ''];
                }
                else
                {
                    $advance_status = ['pay id' => '', 'amount' => '', 'status' => ''];
                    $left_status = ['pay id' => $row_check_full['pay_id'], 'amount' => $row_check_full['amount'], 'status' => $row_check_full['pay_status']];
                }

                $payment = ['advance amount' => $advance_status, 'remaining amount' => $left_status];

                $mode = ['mode' => '3', 'mode name' => 'Pay full after unloading', 'payment' => $payment];
            }

            $active = date_create($row_load['or_active_on']);
            $active = date_format($active, "h:i a, d M Y");

            $ex = date_create($row_load['or_expire_on']);
            $ex = date_format($ex, "h:i a, d M Y");

            $cust = "select * from customers where cu_id = '".$row_load['or_cust_id']."'";
            $run_cust = mysqli_query($link, $cust);
            $row_cust = mysqli_fetch_array($run_cust, MYSQLI_ASSOC);

            $responseData = ['delivery id of truck' => $row['del_trk_id'], 'truck id' => $row['trk_id'], 'truck on trip' => $row_tr['trk_on_trip'], 'post id' => $row_load['or_id'], 'customer id' => $row_load['or_cust_id'], 'sources' => $sources, 'destinations' => $destinations, 
                            'material' => $row_load['or_product'], 'payment mode' => $mode, 'contact person' => $row_load['or_contact_person_name'], 
                            'contact person phone' => $row_load['or_contact_person_phone']];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
            http_response_code(200);
        }
    }
    elseif(isset($_POST['truck_id']) && isset($_POST['del_trk_id']) && isset($_POST['otp']))
    {
        $otp_sql = "select * from delivery_trucks where del_trk_id = '".$_POST['del_trk_id']."' and trk_id = '".$_POST['truck_id']."' and otp = '".$_POST['otp']."'";
        $otp_run = mysqli_query($link, $otp_sql);
        $otp_row = mysqli_fetch_array($otp_run, MYSQLI_ASSOC);

        if($otp_row['otp'] === $_POST['otp'])
        {
            $active = "update delivery_trucks set otp_verified = 1, status = 1 where del_trk_id = '".$_POST['del_trk_id']."' and otp = '".$_POST['otp']."'";
            $set = mysqli_query($link, $active);

            $truck = "update trucks set trk_on_trip = 2 where trk_id = '".$_POST['truck_id']."'";
            $set = mysqli_query($link, $truck);

            $responseData = ['success' => '1', 'message' => 'OTP Verified. Trip started'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
            http_response_code(200);
        }
        else
        { 
            $responseData = ['success' => '0', 'message' => 'Wrong OTP'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
            http_response_code(400);
        }
    }
    elseif(isset($_POST['del_trk_id']))
    {
        $otp_sql = "select * from delivery_trucks where del_trk_id = '".$_POST['del_trk_id']."'";
        $otp_run = mysqli_query($link, $otp_sql);
        $otp_row = mysqli_fetch_array($otp_run, MYSQLI_ASSOC);

        if($otp_row['status'] == 1)
        {
            $active = "update delivery_trucks set status = 2 where del_trk_id = '".$_POST['del_trk_id']."'";
            $set = mysqli_query($link, $active);

            $truck = "update trucks set trk_on_trip = 0 where trk_id = '".$otp_row['trk_id']."'";
            $set = mysqli_query($link, $truck);

            // Checking for delivery completed or not
            $sql11 = "select * from deliveries where del_status = 1";
            $run11 = mysqli_query($link, $sql11);
            $row11 = mysqli_fetch_array($run11, MYSQLI_ASSOC);

            $i1 = 0;
            $sql21 = "select * from delivery_trucks where del_id = '".$row11['del_id']."'";
            $run21 = mysqli_query($link, $sql21);
            $count21 = mysqli_num_rows($run21);
            while($row21 = mysqli_fetch_array($run21, MYSQLI_ASSOC))
            {
                if($row21['status'] == 2)
                {
                    $i1++;
                }
            }

            if($count21 == $i1)
            {
                mysqli_query($link, "update deliveries set del_status = 2 where del_id = '".$row11['del_id']."'");
            }

            $responseData = ['success' => '1', 'message' => 'Trip Completed'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
            http_response_code(200);
        }
        else
        { 
            $responseData = ['success' => '0', 'message' => 'Server error'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
            http_response_code(400);
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