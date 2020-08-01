<?php

    include('../session.php');

    $connect = new PDO("mysql:host=$hostName; dbname=$dbName", $userName, $password);

    if(isset($_POST["action"]))
    {
        $query = "select * from truck_owners where to_on = '1'";

        if(isset($_POST["active"]))
        {
            $query .= " AND to_verified = '1'";
        }

        if(isset($_POST["inactive"]))
        {
            $query .= " AND to_verified = '0'";
        }

        if(isset($_POST["nothing"]))
        {
            $query .= "";
        }

        if(!empty($_POST["start_date"]) && empty($_POST["end_date"]))
        {
            $s_date = date_create($_POST["start_date"]);
            $s_date = date_format($s_date, "Y-m-d");
            
            $e_date = date_create($_POST["end_date"]);
            $e_date = date_format($e_date, "Y-m-d");

            $query .= " and to_registered >= '".$s_date."'";
        }
        elseif(empty($_POST["start_date"]) && !empty($_POST["end_date"]))
        {
            $s_date = date_create($_POST["start_date"]);
            $s_date = date_format($s_date, "Y-m-d");
            
            $e_date = date_create($_POST["end_date"]);
            $e_date = date_format($e_date, "Y-m-d");

            $query .= " and to_registered <= '".$e_date."'";
        }
        elseif(!empty($_POST["start_date"]) && !empty($_POST["end_date"]))
        {
            $s_date = date_create($_POST["start_date"]);
            $s_date = date_format($s_date, "Y-m-d");
            
            $e_date = date_create($_POST["end_date"]);
            $e_date = date_format($e_date, "Y-m-d");

            $query .= " and to_registered >= '".$s_date."' and to_registered <= '".$e_date."'";
        }
        else
        {
            $query .= "";
        }

        if(isset($_POST['search']))
        {
            $se = $_POST['search'];
            $query .= " AND to_name LIKE '$se%' order by to_id desc";
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
                        <th>Date</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Trucks Own</th>
                        <th>City</th>
                        <th>Routes</th>
                        <th>State Permits</th>
                        <th>View</th>
                    </thead>
                    <tbody>
            ';

            foreach($result as $row)
            {
                $date = date_create($row['to_registered']);
                $date = date_format($date, "d M, Y");

                $output .=
                '
                    <tr>
                        <td data-column="Date">'.$date.'</td>
                        <td data-column="Name">'.$row['to_name'].'</td>
                        <td data-column="Email"><a href="mailto:'.$row['to_email'].'">'.$row['to_email'].'</td>
                        <td data-column="Phone">+'.$row['to_phone_code'].' '.$row['to_phone'].'</td>
                        <td data-column="Trucks Own"></td>
                        <td data-column="City">'.$row['to_city'].'</td>
                        <td data-column="Routes">'.$row['to_routes'].'</td>
                        <td data-column="State Permits">'.$row['to_permits'].'</td>
                        <td data-column="View">
                            <a href="truck_owner_profile?owner_id='.$row['to_id'].'" target="_blank"><i class="fas fa-eye"></i></a>
                        </td>
                    </tr>
                ';
            }

            $output .=
            '
                    </tbody>
                </table>
            ';
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

?>