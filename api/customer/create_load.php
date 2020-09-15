<?php
    include('../../dbcon.php');

    header('Content-Type: application/json');

    include('../../geocoding/geocode.php');

    use getLocation\Geocoding;

    $geo = new Geocoding("AIzaSyDH8iEIWiHLIRcSsJWm8Fh1qbgwt0JRAc0");

    date_default_timezone_set("Asia/Kolkata");

    if(isset($_POST['cust_id']) && isset($_POST['source']) && isset($_POST['destination']) && isset($_POST['material']) && isset($_POST['price_unit']) && isset($_POST['quantity']) && 
        isset($_POST['truck_preference']) && isset($_POST['truck_types']) && isset($_POST['expected_price']) && isset($_POST['payment_mode']) && isset($_POST['advance_pay']) && 
        isset($_POST['expire_date_time']) && isset($_POST['contact_person_name']) && isset($_POST['contact_person_phone']))
    {
        date_default_timezone_set("Asia/Kolkata");
        $date = date('Y-m-d H:i:s');

        function generateRandomString($length = 7)
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

        $code = generateRandomString();

        $ex_date = date_create($_POST['expire_date_time']);
        $ex_date = date_format($ex_date, "Y-m-d H:i:s");

        $shipper_sql = "select * from customers where cu_id = '".$_POST['cust_id']."'";
        $shipper_run = mysqli_query($link, $shipper_sql);
        $shipper_row = mysqli_fetch_array($shipper_run, MYSQLI_ASSOC);

        if($shipper_row['cu_account_on'] == 1)
        {
            $date_now = new DateTime(date('Y-m-d H:i:s'));
            $date2    = new DateTime(date_format(date_create($shipper_row['cu_trial_expire_date']), 'Y-m-d H:i:s'));

            if($date_now > $date2)
            {
                $responseData = ['success' => '0', 'message' => 'Trial period expired'];
                echo json_encode($responseData, JSON_PRETTY_PRINT);
                http_response_code(400);
            }
            else
            {
                $kya = date('Y-m-d');
                $check_limit = "SELECT * FROM cust_order WHERE or_cust_id = '".$_POST['cust_id']."' AND DATE_FORMAT(or_active_on, '%Y-%m-%d') = '$kya'";
                $run_limit = mysqli_query($link, $check_limit);
                $row_limit = mysqli_fetch_array($run_limit, MYSQLI_ASSOC);
                $count_limit = mysqli_num_rows($run_limit);
                // echo $count_limit;
                if($count_limit <= 2)
                {
                    $all_sources = explode('* ', $_POST['source']);

                    foreach($all_sources as $sources)
                    {
                        if(!empty($sources))
                        {
                            $sources = mysqli_real_escape_string($link, $sources);

                            $coordinates = $geo->getCoordinates("$sources");
                    
                            extract($coordinates);
                            $lat = $latitude;
                            $lng = $longitude;
                            
                            $data = file_get_contents("https://maps.google.com/maps/api/geocode/json?latlng=$lat,$lng&key=AIzaSyDH8iEIWiHLIRcSsJWm8Fh1qbgwt0JRAc0");
                            $data = json_decode($data);
                            $add_array  = $data->results;
                            $add_array = $add_array[0];
                            $add_array = $add_array->address_components;
                            $state = "Not found"; 
                            $city = "Not found";
                            foreach ($add_array as $key)
                            {
                                if($key->types[0] == 'locality')
                                {
                                    $city = $key->long_name;
                                }
                                if($key->types[0] == 'administrative_area_level_1')
                                {
                                    $state = $key->long_name;
                                }
                            }
                            
                            mysqli_query($link, "insert into cust_order_source (or_uni_code, or_source, or_source_lat, or_source_lng, or_source_city, or_source_state) values 
                                        ('$code', '$sources', '$lat', '$lng', '$city', '$state')");
                        }
                    }

                    $all_destinations = explode('* ', $_POST['destination']);

                    foreach($all_destinations as $destinations)
                    {
                        if(!empty($destinations))
                        {
                            $destinations = mysqli_real_escape_string($link, $destinations);

                            $coordinates = $geo->getCoordinates("$destinations");
                    
                            extract($coordinates);
                            $lat = $latitude;
                            $lng = $longitude;
                            
                            $data = file_get_contents("https://maps.google.com/maps/api/geocode/json?latlng=$lat,$lng&key=AIzaSyDH8iEIWiHLIRcSsJWm8Fh1qbgwt0JRAc0");
                            $data = json_decode($data);
                            $add_array  = $data->results;
                            $add_array = $add_array[0];
                            $add_array = $add_array->address_components;
                            $state = "Not found"; 
                            $city = "Not found";
                            foreach ($add_array as $key)
                            {
                                if($key->types[0] == 'locality')
                                {
                                    $city = $key->long_name;
                                }
                                if($key->types[0] == 'administrative_area_level_1')
                                {
                                    $state = $key->long_name;
                                }
                            }

                            mysqli_query($link, "insert into cust_order_destination (or_uni_code, or_destination, or_des_lat, or_des_lng, or_des_city, or_des_state) values 
                                        ('$code', '$destinations', '$lat', '$lng', '$city', '$state')");
                        }
                    }

                    $all_types = explode('* ', $_POST['truck_types']);

                    foreach($all_types as $trucks)
                    {
                        if(!empty($trucks))
                        {
                            $trucks = mysqli_real_escape_string($link, $trucks);

                            mysqli_query($link, "insert into cust_order_truck_pref (or_uni_code, or_truck_pref_type) values ('$code', '$trucks')");
                        }
                    }

                    $sql = "insert into cust_order (or_cust_id, or_uni_code, or_product, or_price_unit, or_quantity, or_truck_preference, or_expected_price, or_payment_mode, or_advance_pay, 
                            or_expire_on, or_contact_person_name, or_contact_person_phone) values ('".$_POST['cust_id']."', '$code', '".$_POST['material']."', '".$_POST['price_unit']."', 
                            '".$_POST['quantity']."', '".$_POST['truck_preference']."', '".$_POST['expected_price']."', '".$_POST['payment_mode']."', '".$_POST['advance_pay']."', '$ex_date', 
                            '".$_POST['contact_person_name']."', '".$_POST['contact_person_phone']."')";
                    $set = mysqli_query($link, $sql);
                    
                    if($set)
                    {
                        $responseData = ['success' => '1', 'message' => 'Order Created'];
                        echo json_encode($responseData, JSON_PRETTY_PRINT);
                        http_response_code(200);
                    }
                    else
                    {
                        $responseData = ['success' => '0', 'message' => 'Something went wrong. Error'];
                        echo json_encode($responseData, JSON_PRETTY_PRINT);
                        http_response_code(400);
                    }
                }
                else
                {
                    $responseData = ['success' => '0', 'message' => 'You can post only 3 posts per day in trial period'];
                    echo json_encode($responseData, JSON_PRETTY_PRINT);
                    http_response_code(400);
                }
            }
        }
        elseif($shipper_row['cu_account_on'] == 2)
        {
            $date_now = new DateTime(date('Y-m-d H:i:s'));
            $date2    = new DateTime(date_format(date_create($shipper_row['cu_subscription_expire_date']), 'Y-m-d H:i:s'));

            if($date_now > $date2)
            {
                $responseData = ['success' => '0', 'message' => 'Subscription period expired'];
                echo json_encode($responseData, JSON_PRETTY_PRINT);
                http_response_code(400);
            }
            else
            {
                $all_sources = explode('* ', $_POST['source']);

                foreach($all_sources as $sources)
                {
                    if(!empty($sources))
                    {
                        $sources = mysqli_real_escape_string($link, $sources);

                        mysqli_query($link, "insert into cust_order_source (or_uni_code, or_source) values ('$code', '$sources')");
                    }
                }

                $all_destinations = explode('* ', $_POST['destination']);

                foreach($all_destinations as $destinations)
                {
                    if(!empty($destinations))
                    {
                        $destinations = mysqli_real_escape_string($link, $destinations);

                        mysqli_query($link, "insert into cust_order_destination (or_uni_code, or_destination) values ('$code', '$destinations')");
                    }
                }

                $all_types = explode('* ', $_POST['truck_types']);

                foreach($all_types as $trucks)
                {
                    if(!empty($trucks))
                    {
                        $trucks = mysqli_real_escape_string($link, $trucks);

                        mysqli_query($link, "insert into cust_order_truck_pref (or_uni_code, or_truck_pref_type) values ('$code', '$trucks')");
                    }
                }

                $sql = "insert into cust_order (or_cust_id, or_uni_code, or_product, or_price_unit, or_quantity, or_truck_preference, or_expected_price, or_payment_mode, or_advance_pay, 
                        or_expire_on, or_contact_person_name, or_contact_person_phone) values ('".$_POST['cust_id']."', '$code', '".$_POST['material']."', '".$_POST['price_unit']."', 
                        '".$_POST['quantity']."', '".$_POST['truck_preference']."', '".$_POST['expected_price']."', '".$_POST['payment_mode']."', '".$_POST['advance_pay']."', '$ex_date', 
                        '".$_POST['contact_person_name']."', '".$_POST['contact_person_phone']."')";
                $set = mysqli_query($link, $sql);
                
                if($set)
                {
                    $responseData = ['success' => '1', 'message' => 'Order Created'];
                    echo json_encode($responseData, JSON_PRETTY_PRINT);
                    http_response_code(200);
                }
                else
                {
                    $responseData = ['success' => '0', 'message' => 'Something went wrong. Error'];
                    echo json_encode($responseData, JSON_PRETTY_PRINT);
                    http_response_code(400);
                }
            }
        }
        else
        {
            $responseData = ['success' => '0', 'message' => 'You have no plan. Get subscription'];
            echo json_encode($responseData, JSON_PRETTY_PRINT);
            http_response_code(400);
        }
    }
    else
    {
        $responseData = ['success' => '0', 'message' => 'Something is missing'];
        echo json_encode($responseData, JSON_PRETTY_PRINT);

        http_response_code(400);
    }
?>