<?php
    include('../../dbcon.php');

    header('Content-Type: application/json');

    if(isset($_POST['owner_id']) && isset($_POST['delivery_status']))
    {
        $sql = "select * from deliveries where to_id = '".$_POST['owner_id']."' and del_status = '".$_POST['delivery_status']."'";
        $run = mysqli_query($link, $sql);
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

            if($row1['or_payment_mode'] == 1)
            {
                $mode = "Negotiable";
            }
            elseif($row1['or_payment_mode'] == 2)
            {
                $mode = $row1['or_advance_pay']."% Advance Pay";
            }
            else
            {
                $mode = "Pay driver after unloading";
            }

            $active = date_create($row1['or_active_on']);
            $active = date_format($active, "h:i a, d M Y");

            $ex = date_create($row1['or_expire_on']);
            $ex = date_format($ex, "h:i a, d M Y");

            $cust = "select * from customers where cu_id = '".$row1['or_cust_id']."'";
            $run_cust = mysqli_query($link, $cust);
            $row_cust = mysqli_fetch_array($run_cust, MYSQLI_ASSOC);

            if($row_cust['cu_account_on'] == 1)
            {
                $admin_price = ($row1['or_expected_price'] - ($row1['or_expected_price'] * ($row1['or_admin_expected_price']/100)));

                $load_details = ['post id' => $row1['or_id'], 'customer id' => $row1['or_cust_id'], 'sources' => $sources, 'destinations' => $destinations, 'material' => $row1['or_product'], 
                            $unit => $row1['or_quantity'], 'truck preference' => $row_truck['trk_cat_name'], 'truck types' => $truck_types, 
                            'expected price' => "$admin_price", 'payment mode' => $mode, 'created on' => $active, 'expired on' => $ex, 
                            'contact person' => $row1['or_contact_person_name'], 'contact person phone' => $row1['or_contact_person_phone']];
            }
            
            if($row_cust['cu_account_on'] == 2)
            {
                $load_details = ['post id' => $row1['or_id'], 'customer id' => $row1['or_cust_id'], 'sources' => $sources, 'destinations' => $destinations, 'material' => $row1['or_product'], 
                            $unit => $row1['or_quantity'], 'truck preference' => $row_truck['trk_cat_name'], 'truck types' => $truck_types, 
                            'expected price' => $row1['or_expected_price'], 'payment mode' => $mode, 'created on' => $active, 'expired on' => $ex, 
                            'contact person' => $row1['or_contact_person_name'], 'contact person phone' => $row1['or_contact_person_phone']];
            }

            if($row['bid_status'] == 0)
            {
                $bid_status = ['success' => '0', 'message' => 'On hold'];
            }
            elseif($row['bid_status'] == 1)
            {
                $bid_status = ['success' => '1', 'message' => 'Accepted by Admin'];
            }
            else
            {
                $bid_status = ['success' => '1', 'message' => 'Accepted by Shipper'];
            }
            
            $responseData[] = ['bid id' => $row['bid_id'],'my price' => $row['bid_expected_price'], 'bid status' => $bid_status, 'load details' => $load_details];
        }
        echo json_encode($responseData, JSON_PRETTY_PRINT);
        http_response_code(200);
    }
    elseif(isset($_POST['bid_id_for_accepting']))
    {
        $update = "update bidding set bid_status = 3 where bid_id = '".$_POST['bid_id_for_accepting']."'";
        $run = mysqli_query($link, $update);

        if($run)
        {

            $responseData = ['success' => '1', 'message' => 'Deal accepted'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
            http_response_code(200);
        }
        else
        {
            $responseData = ['success' => '0', 'message' => 'Something went wrong'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
            http_response_code(200);
        }
    }
    elseif(isset($_POST['bid_id_for_removing']))
    {
        $update = "delete from bidding where bid_id = '".$_POST['bid_id_for_removing']."'";
        $run = mysqli_query($link, $update);

        if($run)
        {

            $responseData = ['success' => '1', 'message' => 'Bid removed'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
            http_response_code(200);
        }
        else
        {
            $responseData = ['success' => '0', 'message' => 'Something went wrong'];
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