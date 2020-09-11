<?php

    include('../session.php');

    $connect = new PDO("mysql:host=$hostName; dbname=$dbName", $userName, $password);

    if(isset($_POST["action"]))
    {
        $query = "select cust_order.*, customers.* from cust_order, customers where cust_order.or_cust_id = customers.cu_id";

        if(isset($_POST["active"]))
        {
            $query .= " AND cust_order.or_status = '1'";
        }

        if(isset($_POST["inactive"]))
        {
            $query .= " AND cust_order.or_status = '0'";
        }

        if(isset($_POST["nothing"]))
        {
            $query .= "";
        }

        if(isset($_POST["cancel"]))
        {
            $query .= " AND cust_order.or_status = '2'";
        }

        if(!empty($_POST["start_date"]) && empty($_POST["end_date"]))
        {
            $s_date = date_create($_POST["start_date"]);
            $s_date = date_format($s_date, "Y-m-d");
            
            $e_date = date_create($_POST["end_date"]);
            $e_date = date_format($e_date, "Y-m-d");

            $query .= " and cust_order.or_active_on >= '".$s_date."'";
        }
        elseif(empty($_POST["start_date"]) && !empty($_POST["end_date"]))
        {
            $s_date = date_create($_POST["start_date"]);
            $s_date = date_format($s_date, "Y-m-d");
            
            $e_date = date_create($_POST["end_date"]);
            $e_date = date_format($e_date, "Y-m-d");

            $query .= " and cust_order.or_active_on <= '".$e_date."'";
        }
        elseif(!empty($_POST["start_date"]) && !empty($_POST["end_date"]))
        {
            $s_date = date_create($_POST["start_date"]);
            $s_date = date_format($s_date, "Y-m-d");
            
            $e_date = date_create($_POST["end_date"]);
            $e_date = date_format($e_date, "Y-m-d");

            $query .= " and cust_order.or_active_on >= '".$s_date."' and cust_order.or_active_on <= '".$e_date."'";
        }
        else
        {
            $query .= "";
        }

        if(isset($_POST['search']))
        {
            $se = $_POST['search'];
            $query .= " AND customers.cu_phone LIKE '$se%' order by cust_order.or_id desc limit 20";
        }

        // echo $query;

        $statement = $connect->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        $total_row = $statement->rowCount();

        $output = '';
        
        if($total_row > 0)
        {
            $output .=
            '
                <table>
                    <thead>
                        <th>ID</th>
                        <th>Reg. Date</th>
                        <th>Shipper</th>
                        <th>Source & End</th>
                        <th>Product</th>
                        <th>Price Unit</th>
                        <th>Quantity</th>
                        <th>Contact Person</th>
                        <th>View</th>
                    </thead>
                    <tbody>
            ';

            foreach($result as $row)
            {
                $date = date_create($row['or_active_on']);
                $date = date_format($date, "d M, Y");

                $output .=
                '
                    <tr>
                        <td data-column="ID">'.$row['or_id'].'</td>
                        <td data-column="Reg. Date">'.$date.'</td>
                        <td data-column="Shipper">+'.$row['cu_phone_code'].' '.$row['cu_phone'].'</td>
                ';

                $source = "select * from cust_order_source where or_uni_code = '".$row['or_uni_code']."'";
                $get_source = mysqli_query($link, $source);
                $row_source = mysqli_fetch_array($get_source, MYSQLI_ASSOC);

                $end = "select * from cust_order_destination where or_uni_code = '".$row['or_uni_code']."' order by des_id desc";
                $get_end = mysqli_query($link, $end);
                $row_end = mysqli_fetch_array($get_end, MYSQLI_ASSOC);

                if($row['or_price_unit'] == 1)
                {
                    $unit = "Tonnage";
                    $quant = $row['or_quantity']." Tons";
                }
                else
                {
                    $unit = "Number of Trucks";
                    $quant = $row['or_quantity']." Trucks";
                }

                $output .=
                '
                        <td data-column="Source & End">
                            '.$row_source['or_source'].'
                            <br>to<br>
                            '.$row_end['or_destination'].'
                        </td>
                        <td data-column="Product">'.$row['or_product'].'</td>
                        <td data-column="Price Unit">'.$unit.'</td>
                        <td data-column="Quantity">'.$quant.'</td>
                        <td data-column="Contact Person">
                            Name - '.$row['or_contact_person_name'].'
                            <br>
                            Phone - '.$row['or_contact_person_phone'].'
                        </td>
                        <td data-column="View">
                            <a class="btn btn-icon btn-info" href="loads_by_id?load_id='.$row['or_id'].'"><i class="fas fa-eye" title="View Details"></i></a>
                        </td>
                    </tr>
                ';
            }

        }
        else
        {
            $output = 
            '
                No Data Found
            ';
        }
        
        echo $output;

    }
    else
    {
        echo "Server error";
    }
?>