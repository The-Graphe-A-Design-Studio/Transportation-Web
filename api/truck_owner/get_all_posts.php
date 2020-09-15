<?php

    include('../../dbcon.php');

    header('Content-Type: application/json');

    // Checking for older posts
    $sql = "select * from cust_order";
    $run = mysqli_query($link, $sql);
    while($row = mysqli_fetch_array($run, MYSQLI_ASSOC))
    {
        $date_now = new DateTime(date('Y-m-d H:i:s'));
        $date2    = new DateTime(date_format(date_create($row['or_expire_on']), 'Y-m-d H:i:s'));

        if($date_now > $date2)
        {
            $update = "update cust_order set or_status = 0 where or_id = '".$row['or_id']."'";
            mysqli_query($link, $update);
        }
    }

    $sql = "select * from cust_order where or_status = 1";
    $result = mysqli_query($link, $sql) or die("Error in Selecting " . mysqli_error($link));
    $counte = mysqli_num_rows($result);
    if($counte == 0)
    {
        $responseData[] = ['success' => '0', 'message' => 'No posts found'];
        echo json_encode($responseData, JSON_PRETTY_PRINT);
        http_response_code(400);
    }
    else
    {
        while($row = mysqli_fetch_assoc($result))
        {

            $sources = $destinations = $truck_types = array();
            $i = $j = $k = 1;

            $source = "select * from cust_order_source where or_uni_code = '".$row['or_uni_code']."' order by so_id";
            $get_source = mysqli_query($link, $source);
            while($row_source = mysqli_fetch_array($get_source, MYSQLI_ASSOC))
            {
                $sources[] = ['source '.$i => $row_source['or_source'], 'lat' => $row_source['or_source_lat'], 'lng' => $row_source['or_source_lng'], 
                                'city' => $row_source['or_source_city'], 'state' => $row_source['or_source_state']];
                $i++;
            }

            $destination = "select * from cust_order_destination where or_uni_code = '".$row['or_uni_code']."' order by des_id";
            $get_destination = mysqli_query($link, $destination);
            while($row_destination = mysqli_fetch_array($get_destination, MYSQLI_ASSOC))
            {
                $destinations[] = ['destination '.$j => $row_destination['or_destination'], 'lat' => $row_destination['or_des_lat'], 'lng' => $row_destination['or_des_lng'], 
                'city' => $row_destination['or_des_city'], 'state' => $row_destination['or_des_state']];
                $j++;
            }

            $type = "select cust_order_truck_pref.*, truck_cat_type.* from cust_order_truck_pref, truck_cat_type where cust_order_truck_pref.or_uni_code = '".$row['or_uni_code']."'
                    and cust_order_truck_pref.or_truck_pref_type = truck_cat_type.ty_id order by cust_order_truck_pref.pref_id";
            $get_type = mysqli_query($link, $type);
            while($row_type = mysqli_fetch_array($get_type, MYSQLI_ASSOC))
            {
                $truck_types[] = ['type '.$k => $row_type['ty_name']];
                $k++;
            }

            $truck = "select * from truck_cat where trk_cat_id = '".$row['or_truck_preference']."'";
            $get_truck = mysqli_query($link, $truck);
            $row_truck = mysqli_fetch_array($get_truck, MYSQLI_ASSOC);

            if($row['or_price_unit'] == 1)
            {
                $unit = "tonnage";
            }
            else
            {
                $unit = "number of trucks";
            }

            if($row['or_payment_mode'] == 1)
            {
                $mode = "Negotiable";
            }
            elseif($row['or_payment_mode'] == 2)
            {
                $mode = $row['or_advance_pay']."% Advance Pay";
            }
            else
            {
                $mode = "Pay driver after unloading";
            }

            $active = date_create($row['or_active_on']);
            $active = date_format($active, "h:i a, d M Y");

            $ex = date_create($row['or_expire_on']);
            $ex = date_format($ex, "h:i a, d M Y");

            $cust = "select * from customers where cu_id = '".$row['or_cust_id']."'";
            $run_cust = mysqli_query($link, $cust);
            $row_cust = mysqli_fetch_array($run_cust, MYSQLI_ASSOC);

            if($row_cust['cu_account_on'] == 1)
            {
                $responseData[] = ['post id' => $row['or_id'], 'customer id' => $row['or_cust_id'], 'sources' => $sources, 'destinations' => $destinations, 'material' => $row['or_product'], 
                            $unit => $row['or_quantity'], 'truck preference' => $row_truck['trk_cat_name'], 'truck types' => $truck_types, 
                            'expected price' => $row['or_admin_expected_price'], 'payment mode' => $mode, 'created on' => $active, 'expired on' => $ex, 
                            'contact person' => $row['or_contact_person_name'], 'contact person phone' => $row['or_contact_person_phone']];

                echo json_encode($responseData, JSON_PRETTY_PRINT);
            }
            
            if($row_cust['cu_account_on'] == 2)
            {
                $responseData[] = ['post id' => $row['or_id'], 'customer id' => $row['or_cust_id'], 'sources' => $sources, 'destinations' => $destinations, 'material' => $row['or_product'], 
                            $unit => $row['or_quantity'], 'truck preference' => $row_truck['trk_cat_name'], 'truck types' => $truck_types, 
                            'expected price' => $row['or_expected_price'], 'payment mode' => $mode, 'created on' => $active, 'expired on' => $ex, 
                            'contact person' => $row['or_contact_person_name'], 'contact person phone' => $row['or_contact_person_phone']];

                echo json_encode($responseData, JSON_PRETTY_PRINT);
            }
        
        }

        http_response_code(200);
        
        mysqli_close($link);
    }

?>