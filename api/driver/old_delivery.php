<?php
    include('../../dbcon.php');

    header('Content-Type: application/json');

    if(isset($_POST['truck_id']))
    {
        $tr = "select * from trucks where trk_id = '".$_POST['truck_id']."'";
        $run_tr = mysqli_query($link, $tr);
        $row_tr = mysqli_fetch_array($run_tr, MYSQLI_ASSOC);

        $sql = "select * from delivery_trucks where trk_id = '".$row_tr['trk_id']."' and status = 2";
        $run = mysqli_query($link, $sql);
        $count = mysqli_num_rows($run);        

        if($count == 0)
        {
            $responseData = [];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
            http_response_code(400);
        }
        else
        {          
            while($row = mysqli_fetch_array($run, MYSQLI_ASSOC))
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
                    $si = "ton";
                }
                else
                {
                    $unit = "number of trucks";
                    $si = "truck";
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

                $responseData[] = ['delivery id of truck' => $row['del_trk_id'], 
                                'truck id' => $row['trk_id'], 
                                'truck on trip' => $row_tr['trk_on_trip'], 
                                'post id' => $row_load['or_id'], 
                                'sources' => $sources, 
                                'destinations' => $destinations, 
                                'material' => $row_load['or_product'],
                                'quantity' => $row_load['or_quantity']." ".$si,
                                'payment mode' => $mode, 
                                'trip start' => date_format(date_create($row['trip_start']), 'd M, Y h:i A'),
                                'trip end' => date_format(date_create($row['trip_end']), 'd M, Y h:i A'),
                                'contact person' => $row_load['or_contact_person_name'], 
                                'contact person phone' => $row_load['or_contact_person_phone']];
                
            }

            echo json_encode($responseData, JSON_PRETTY_PRINT);
            http_response_code(200);
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