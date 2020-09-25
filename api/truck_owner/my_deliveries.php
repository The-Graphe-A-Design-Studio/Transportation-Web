<?php
    include('../../dbcon.php');

    header('Content-Type: application/json');

    if(isset($_POST['owner_id']))
    {
        $sql = "select * from deliveries where to_id = '".$_POST['owner_id']."'";
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

            $load_details = ['post id' => $row1['or_id'], 'customer id' => $row1['or_cust_id'], 'sources' => $sources, 'destinations' => $destinations, 'material' => $row1         ['or_product'], 'truck preference' => $row_truck['trk_cat_name'], 'truck types' => $truck_types, 'payment mode' => $mode, 'contact person' => $row1['or_contact_person_name'], 'contact person phone' => $row1['or_contact_person_phone']];

            $total_price = $row['deal_price'] * $row['quantity'];
            
            $responseData[] = ['delivery id' => $row['del_id'], 'price unit' => $unit, 'quantity' => $row['quantity'], 'deal price' => $row['deal_price'], 'total price' => "$total_price", 'delivery status' => $row['del_status'], 'load details' => $load_details];
        }
        echo json_encode($responseData, JSON_PRETTY_PRINT);
        http_response_code(200);
    }
    elseif(isset($_POST['truck_id']) && isset($_POST['delivery_id']))
    {
        $all_trucks = explode('* ', $_POST['truck_id']);

        foreach($all_trucks as $trucks)
        {
            if(!empty($trucks))
            {
                mysqli_query($link, "insert into delivery_trucks (del_id, trk_id) values ('".$_POST['delivery_id']."', '$trucks')");
            }
        }

        $responseData = ['success' => '1', 'message' => 'Trucks added'];
        echo json_encode($responseData, JSON_PRETTY_PRINT);
        http_response_code(200);
    }
    elseif(isset($_POST['truck_owner_id']) && isset($_POST['del_id']))
    {
        $sql = "select deliveries.*, delivery_trucks.* from deliveries, delivery_trucks where deliveries.to_id = '".$_POST['truck_owner_id']."' and deliveries.del_id = delivery_trucks.del_id";
        $get = mysqli_query($link, $sql);
        while($row = mysqli_fetch_array($get, MYSQLI_ASSOC))
        {
            $truck = "select * from trucks where trk_id = '".$row['trk_id']."'";
            $get_truck = mysqli_query($link, $truck);
            $row_truck = mysqli_fetch_array($get_truck, MYSQLI_ASSOC);

            $responseData[] = ['del truck id' => $row['del_trk_id'], 'truck id' => $row['trk_id'], 'truck number' => $row_truck['trk_num'], 'driver name' => $row_truck['trk_dr_name'], 'driver phone' => $row_truck['trk_dr_phone']];
        }
        echo json_encode($responseData, JSON_PRETTY_PRINT);
        http_response_code(200);
    }
    elseif(isset($_POST['del_id_remove_truck']))
    {
        $update = "delete from delivery_trucks where del_trk_id = '".$_POST['del_id_remove_truck']."'";
        $run = mysqli_query($link, $update);

        if($run)
        {

            $responseData = ['success' => '1', 'message' => 'Truck removed'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
            http_response_code(200);
        }
        else
        {
            $responseData = ['success' => '0', 'message' => 'Something went wrong'];
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