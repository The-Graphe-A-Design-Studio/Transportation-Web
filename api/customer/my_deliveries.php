<?php
    include('../../dbcon.php');

    header('Content-Type: application/json');

    if(isset($_POST['shipper_id']))
    {
        $sql = "select * from deliveries where cu_id = '".$_POST['shipper_id']."' and del_status <> 2";
        $run = mysqli_query($link, $sql);
        $number = mysqli_num_rows($run);

        if($number == 0)
        {
            $responseData = [];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
            http_response_code(206);
        }
        else
        {
            while($row = mysqli_fetch_array($run, MYSQLI_ASSOC))
            {
                $sql1 = "select * from cust_order where or_id = '".$row['or_id']."'";
                $result1 = mysqli_query($link, $sql1) or die("Error in Selecting " . mysqli_error($link));
                $row1 = mysqli_fetch_assoc($result1);

                $sources = $destinations = $truck_types = array();
                        
                $source = "select * from cust_order_source where or_uni_code = '".$row1['or_uni_code']."' order by so_id";
                $get_source = mysqli_query($link, $source);
                while($row_source = mysqli_fetch_array($get_source, MYSQLI_ASSOC))
                {
                    $sources[] = ['source' => $row_source['or_source'], 'lat' => $row_source['or_source_lat'], 'lng' => $row_source['or_source_lng'], 
                                    'city' => $row_source['or_source_city'], 'state' => $row_source['or_source_state']];
                }

                $destination = "select * from cust_order_destination where or_uni_code = '".$row1['or_uni_code']."' order by des_id";
                $get_destination = mysqli_query($link, $destination);
                while($row_destination = mysqli_fetch_array($get_destination, MYSQLI_ASSOC))
                {
                    $destinations[] = ['destination' => $row_destination['or_destination'], 'lat' => $row_destination['or_des_lat'], 'lng' => $row_destination['or_des_lng'], 
                    'city' => $row_destination['or_des_city'], 'state' => $row_destination['or_des_state']];
                }

                $type = "select cust_order_truck_pref.*, truck_cat_type.* from cust_order_truck_pref, truck_cat_type where cust_order_truck_pref.or_uni_code = '".$row1['or_uni_code']."'
                        and cust_order_truck_pref.or_truck_pref_type = truck_cat_type.ty_id order by cust_order_truck_pref.pref_id";
                $get_type = mysqli_query($link, $type);
                while($row_type = mysqli_fetch_array($get_type, MYSQLI_ASSOC))
                {
                    $truck_types[] = ['type' => $row_type['ty_name']];
                }

                $truck = "select * from truck_cat where trk_cat_id = '".$row1['or_truck_preference']."'";
                $get_truck = mysqli_query($link, $truck);
                $row_truck = mysqli_fetch_array($get_truck, MYSQLI_ASSOC);

                if($row1['or_price_unit'] == 1)
                {
                    $unit = "tonnage";
                }
                else
                {
                    $unit = "number of trucks";
                }

                $total_price = round(($row['deal_price'] * $row['quantity']), 2);

                $total_price = round(($total_price + ($total_price * ($row1['or_admin_expected_price']/100))), 2);

                if($row1['or_payment_mode'] == 1)
                {
                    $mode = "Negotiable";
                }
                elseif($row1['or_payment_mode'] == 2)
                {
                    $advance = round(($total_price * ($row1['or_advance_pay']/100)), 2);
                    $left = round(($total_price * ((100 - $row1['or_advance_pay'])/100)), 2);

                    $check_ad = "select * from load_payments where delivery_id = '".$row['del_id']."' and load_id = '".$row['or_id']."' and cu_id = '".$row['cu_id']."' and
                                to_id = '".$row['to_id']."' and pay_mode = '1'";
                    $run_check_ad = mysqli_query($link, $check_ad);
                    $row_check_ad = mysqli_fetch_array($run_check_ad, MYSQLI_ASSOC);
                    $count_check_ad = mysqli_num_rows($run_check_ad);
                    if($count_check_ad == 0)
                    {
                        $advance_status = ['pay id' => '', 'amount' => '', 'status' => ''];
                    }
                    else
                    {
                        $advance_status = ['pay id' => $row_check_ad['pay_id'], 'amount' => $row_check_ad['amount'], 'status' => $row_check_ad['pay_status']];
                    }

                    $check_left = "select * from load_payments where delivery_id = '".$row['del_id']."' and load_id = '".$row['or_id']."' and cu_id = '".$row['cu_id']."' and 
                                to_id = '".$row['to_id']."' and pay_mode = '2'";
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

                    $check_full = "select * from load_payments where delivery_id = '".$row['del_id']."' and load_id = '".$row['or_id']."' and cu_id = '".$row['cu_id']."' and 
                                to_id = '".$row['to_id']."' and pay_mode = '3'";
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

                $active = date_create($row1['or_active_on']);
                $active = date_format($active, "h:i a, d M Y");

                $ex = date_create($row1['or_expire_on']);
                $ex = date_format($ex, "h:i a, d M Y");

                $cust = "select * from customers where cu_id = '".$row1['or_cust_id']."'";
                $run_cust = mysqli_query($link, $cust);
                $row_cust = mysqli_fetch_array($run_cust, MYSQLI_ASSOC);

                $load_details = ['post id' => $row1['or_id'], 'customer id' => $row1['or_cust_id'], 'sources' => $sources, 'destinations' => $destinations, 'material' => $row1['or_product'], 'truck preference' => $row_truck['trk_cat_name'], 'truck types' => $truck_types, 'contact person' => $row1['or_contact_person_name'], 'contact person phone' => $row1['or_contact_person_phone']];

                $del_t = "select * from delivery_trucks where del_id = '".$row['del_id']."'";
                $run_del_t = mysqli_query($link, $del_t);
                $count_del_t = mysqli_num_rows($run_del_t);
                if($count_del_t == 0)
                {
                    $delivery_trucks = ['status' => '0', 'message' => 'No trucks added'];
                }
                else
                {
                    while($row_del_t = mysqli_fetch_array($run_del_t, MYSQLI_ASSOC))
                    {
                        $t_info = "select * from trucks where trk_id = '".$row_del_t['trk_id']."'";
                        $run_t_info = mysqli_query($link, $t_info);
                        $row_t_info = mysqli_fetch_array($run_t_info, MYSQLI_ASSOC);

                        if($row_del_t['otp_verified'] == 1)
                        {
                            $del_trucks[] = ['del truck id' => $row_del_t['del_trk_id'], 'truck number' => $row_t_info['trk_num'], 'driver name' => $row_t_info['trk_dr_name'], 
                                        'driver phone' => $row_t_info['trk_dr_phone'], 'latitude' => $row_del_t['lat'], 'longitude' => $row_del_t['lng'], 'status' => $row_del_t['status']];
                        }
                        else
                        {
                            $del_trucks[] = ['del truck id' => $row_del_t['del_trk_id'], 'truck number' => $row_t_info['trk_num'], 'driver name' => $row_t_info['trk_dr_name'], 
                                        'driver phone' => $row_t_info['trk_dr_phone'], 'latitude' => $row_del_t['lat'], 'longitude' => $row_del_t['lng'], 'otp' => $row_del_t['otp']];
                        }                    
                    }

                    $delivery_trucks = ['status' => '1', 'trucks' => $del_trucks];
                }

                $responseData[] = ['success' => $row['del_status'], 'delivery id' => $row['del_id'], 'price unit' => $unit, 'quantity' => $row['quantity'], 'deal price' => $row['deal_price'], 'total amount' => "$total_price", 'payment mode' => $mode, 'delivery trucks' => $delivery_trucks, 'delivery status' => $row['del_status'], 'load details' => $load_details];
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