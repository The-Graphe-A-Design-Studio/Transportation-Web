<?php

    include('../session.php');

    $connect = new PDO("mysql:host=$hostName; dbname=$dbName", $userName, $password);

    if(isset($_POST["action"]))
    {
        $query = "select * from customers where cu_default = 1";

        if(isset($_POST["active"]))
        {
            $query .= " AND cu_verified = '1'";
        }

        if(isset($_POST["inactive"]))
        {
            $query .= " AND cu_verified = '0'";
        }

        if(isset($_POST["nothing"]))
        {
            $query .= "";
        }

        if(isset($_POST["reject"]))
        {
            $query .= " AND cu_verified = '2'";
        }

        if(!empty($_POST["start_date"]) && empty($_POST["end_date"]))
        {
            $s_date = date_create($_POST["start_date"]);
            $s_date = date_format($s_date, "Y-m-d");
            
            $e_date = date_create($_POST["end_date"]);
            $e_date = date_format($e_date, "Y-m-d");

            $query .= " and cu_registered >= '".$s_date."'";
        }
        elseif(empty($_POST["start_date"]) && !empty($_POST["end_date"]))
        {
            $s_date = date_create($_POST["start_date"]);
            $s_date = date_format($s_date, "Y-m-d");
            
            $e_date = date_create($_POST["end_date"]);
            $e_date = date_format($e_date, "Y-m-d");

            $query .= " and cu_registered <= '".$e_date."'";
        }
        elseif(!empty($_POST["start_date"]) && !empty($_POST["end_date"]))
        {
            $s_date = date_create($_POST["start_date"]);
            $s_date = date_format($s_date, "Y-m-d");
            
            $e_date = date_create($_POST["end_date"]);
            $e_date = date_format($e_date, "Y-m-d");

            $query .= " and cu_registered >= '".$s_date."' and cu_registered <= '".$e_date."'";
        }
        else
        {
            $query .= "";
        }

        if(isset($_POST['search']))
        {
            $se = $_POST['search'];
            $query .= " AND cu_phone LIKE '$se%' order by cu_id desc limit 20";
        }

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
                        <th>Phone</th>
                        <th>Company Name</th>
                        <th>View</th>
                    </thead>
                    <tbody>
            ';

            foreach($result as $row)
            {
                $date = date_create($row['cu_registered']);
                $date = date_format($date, "d M, Y");

                $output .=
                '
                    <tr>
                        <td data-column="ID">'.$row['cu_id'].'</td>
                        <td data-column="Reg. Date">'.$date.'</td>
                ';

                $doc = "select doc_location from customer_docs where doc_owner_phone = '".$row['cu_phone']."' and doc_sr_num = 4";
                $r_doc = mysqli_query($link, $doc);
                $doc_row = mysqli_fetch_array($r_doc, MYSQLI_ASSOC);

                $doc1 = "select doc_location from customer_docs where doc_owner_phone = '".$row['cu_phone']."' and doc_sr_num = 5";
                $r_doc1 = mysqli_query($link, $doc1);
                $doc_row1 = mysqli_fetch_array($r_doc1, MYSQLI_ASSOC);

                $output .=
                '
                        <td data-column="Shipper">
                            <img alt="shipper_selfie_'.$row['cu_phone'].'" src="'.$doc_row['doc_location'].'" style="width: 50px; height: 50px; border-radius: 50%">
                        </td>
                        <td data-column="Phone">+'.$row['cu_phone_code'].' '.$row['cu_phone'].' ('.$row['cu_otp'].')</td>
                        <td data-column="Company Name">'.$doc_row1['doc_location'].'</td>
                        <td data-column="View">
                            <a class="btn btn-icon btn-info" href="shipper_profile?shipper_id='.$row['cu_id'].'"><i class="fas fa-eye" title="View Details"></i></a>
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
    elseif(isset($_POST['doc_id']) && isset($_POST['doc_status']))
    {
        if($_POST['doc_status'] === '0')
        {
            $sql = mysqli_query($link, "update customer_docs set doc_verified = 1 where doc_id = '".$_POST['doc_id']."'");

            if($sql)
            {
                echo "Document verified";
            }
            else
            {
                echo "Something went wrong";
            }
        }
        elseif($_POST['doc_status'] === '1')
        {
            $sql = mysqli_query($link, "update customer_docs set doc_verified = 0 where doc_id = '".$_POST['doc_id']."'");

            if($sql)
            {
                echo "Set to not verified";
            }
            else
            {
                echo "Something went wrong";
            }
        }
        else
        {
            echo "Something missing";
        }
    }
    else
    {
        echo "Server error";
    }
?>