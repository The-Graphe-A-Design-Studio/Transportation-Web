<?php

    include('../../dbcon.php');

    header('Content-Type: application/json');

    if(isset($_POST['customer_id']) && isset($_POST['post_status']))
    {
        $sql = "select * from cust_order where or_cust_id = '".$_POST['customer_id']."' and or_status = '".$_POST['post_status']."'";
        $result = mysqli_query($link, $sql) or die("Error in Selecting " . mysqli_error($link));

        while($row = mysqli_fetch_assoc($result))
        {

            $sources = $destinations = $truck_types = array();
            
            $source = "select or_source from cust_order_source where or_uni_code = '".$row['or_uni_code']."' order by so_id";
            $get_source = mysqli_query($link, $source);
            while($row_source = mysqli_fetch_array($get_source, MYSQLI_ASSOC))
            {
                $sources[] = ['source' => $row_source['or_source']];
            }

            $destination = "select or_destination from cust_order_destination where or_uni_code = '".$row['or_uni_code']."' order by des_id";
            $get_destination = mysqli_query($link, $destination);
            while($row_destination = mysqli_fetch_array($get_destination, MYSQLI_ASSOC))
            {
                $destinations[] = ['destination' => $row_destination['or_destination']];
            }

            $type = "select cust_order_truck_pref.*, truck_cat_type.* from cust_order_truck_pref, truck_cat_type where cust_order_truck_pref.or_uni_code = '".$row['or_uni_code']."'
                    and cust_order_truck_pref.or_truck_pref_type = truck_cat_type.ty_id order by cust_order_truck_pref.pref_id";
            $get_type = mysqli_query($link, $type);
            while($row_type = mysqli_fetch_array($get_type, MYSQLI_ASSOC))
            {
                $truck_types[] = ['type' => $row_type['ty_name']];
            }

            $truck = "select * from truck_cat where trk_cat_id = '".$row['or_truck_preference']."'";
            $get_truck = mysqli_query($link, $truck);
            $row_truck = mysqli_fetch_array($get_truck, MYSQLI_ASSOC);

            if($row['or_price_unit'] == 1)
            {
                $unit = "tonnage";
                $per = "ton";
            }
            else
            {
                $unit = "number of trucks";
                $per = "truck";
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

            $responseData[] = ['post id' => $row['or_id'], 'customer id' => $row['or_cust_id'], 'sources' => $sources, 'destinations' => $destinations, 'material' => $row['or_product'], 
                            'quantity' => $row['or_quantity'], 'unit' => $per, 'truck preference' => $row_truck['trk_cat_name'], 'truck types' => $truck_types, 
                            'expected price' => $row['or_expected_price'], 'payment mode' => $mode, 'created on' => $active, 'expired on' => $ex, 
                            'contact person' => $row['or_contact_person_name'], 'contact person phone' => $row['or_contact_person_phone']];
        }
        
        echo json_encode($responseData, JSON_PRETTY_PRINT);

        http_response_code(200);
        
        mysqli_close($link);
    }
    else
    {
        $responseData = ['success' => '0', 'message' => 'Something went wrong'];
        echo json_encode($responseData, JSON_PRETTY_PRINT);

        http_response_code(400);
    }

?>