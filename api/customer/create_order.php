<?php
    include('../../dbcon.php');

    header('Content-Type: application/json');

    if(isset($_POST['source']) && isset($_POST['destination']) && isset($_POST['material']) && isset($_POST['price_unit']) && isset($_POST['quantity']) && 
        isset($_POST['truck_preference']) && isset($_POST['truck_types']) && isset($_POST['expected_price']) && isset($_POST['payment_mode']) && isset($_POST['advance_pay']) && isset($_POST['expire_date_time'])
        && isset($_POST['contact_person_name']) && isset($_POST['contact_person_phone']))
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

        $or_date = date_create($_POST["date"]);
        $or_date = date_format($or_date, "Y-m-d");

        foreach($_POST['source'] as $sources)
        {
            if(!empty($sources))
            {
                $sources = mysqli_real_escape_string($link, $sources);

                mysqli_query($link, "insert into cust_order_source (or_uni_code, or_source) values ('$code', '$sources')");
            }
        }

        foreach($_POST['destination'] as $destinations)
        {
            if(!empty($destinations))
            {
                $destinations = mysqli_real_escape_string($link, $destinations);

                mysqli_query($link, "insert into cust_order_destination (or_uni_code, or_destination) values ('$code', '$destinations')");
            }
        }

        foreach($_POST['truck_types'] as $trucks)
        {
            if(!empty($trucks))
            {
                $trucks = mysqli_real_escape_string($link, $trucks);

                mysqli_query($link, "insert into cust_order_truck_pref (or_uni_code, or_truck_pref_type) values ('$code', '$trucks')");
            }
        }

        $sql = "insert into cust_order (or_uni_code, or_product, or_price_unit, or_qunatity, or_truck_preference, or_expected_price, or_payment_mode, or_advance_pay, or_expire_on, 
                or_contact_person_name, or_contact_person_phone) values ('$code', '".$_POST['material']."', '".$_POST['price_unit']."', '".$_POST['quantity']."', 
                '".$_POST['truck_preference']."', '".$_POST['expected_price']."', '".$_POST['payment_mode']."', '".$_POST['advance_pay']."', '".$_POST['expire_date_time']."', 
                '".$_POST['contact_person_name']."', '".$_POST['contact_person_phone']."',)";
        $set = mysql_query($link, $sql);
        
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
?>