<?php

    include('../session.php');

    $connect = new PDO("mysql:host=$hostName; dbname=$dbName", $userName, $password);

    if(isset($_POST["action"]))
    {
        $query = "select trucks.*, truck_owners.* from trucks, truck_owners where trucks.trk_owner = truck_owners.to_id and trucks.trk_on = '1'";

        if(isset($_POST["active"]))
        {
            $query .= " and trucks.trk_active = '1'";
        }

        if(isset($_POST["inactive"]))
        {
            $query .= " and trucks.trk_active = '0'";
        }

        if(isset($_POST["nothing"]))
        {
            $query .= "";
        }

        if(isset($_POST["trip"]))
        {
            $query .= " and trucks.trk_on_trip = '1'";
        }

        if(!empty($_POST['city']))
        {
            $se = $_POST['city'];
            $query .= " and truck_owners.to_city = '$se'";
        }

        if(isset($_POST['search']))
        {
            $se = $_POST['search'];
            $query .= " and trucks.trk_num LIKE '$se%' order by trucks.trk_id desc";
        }

        $statement = $connect->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        $total_row = $statement->rowCount();

        $output = '';
        
        if($total_row > 0)
        {
            $output .=
            "
                <table>
                    <thead>
                        <th>ID</th>
                        <th>Truck</th>
                        <th>Owner</th>
                        <th>Number</th>
                        <th>Load (in Tons)</th>
                        <th>Driver's Name</th>
                        <th>Driver's Phone</th>
                        <th>Driver's License</th>
                        <th>Status</th>
                    </thead>
                    <tbody>
            ";

            foreach($result as $row)
            {
                $cat = "select * from truck_cat where trk_cat_id = '".$row['trk_cat']."'";
                $g_cat = mysqli_query($link, $cat);
                $r_cat = mysqli_fetch_array($g_cat, MYSQLI_ASSOC);

                $cat_name = $r_cat['trk_cat_name'];

                $owner = "select * from truck_owners where to_id = '".$row['trk_owner']."'";
                $g_owner = mysqli_query($link, $owner);
                $r_owner = mysqli_fetch_array($g_owner, MYSQLI_ASSOC);

                $owner_name = $r_owner['to_name'];

                if($row['trk_active'] == 1)
                {
                    $sta = '<span class="btn btn-sm btn-success">Active</span>';
                }
                else
                {
                    $sta = '<span class="btn btn-sm btn-danger">Inactive</span>';
                }

                $output .=
                '
                    <tr>
                        <td data-column="ID">'.$row['trk_id'].'</td>
                        <td data-column="Truck">'.$cat_name.'</td>
                        <td data-column="Owner"><a href="truck_owner_profile?owner_id='.$row['trk_owner'].'">'.$owner_name.'</a></td>
                        <td data-column="Number">'.$row['trk_num'].'</td>
                        <td data-column="Load (in Tons)">'.$row['trk_load'].'</td>
                        <td data-column="Driver Name">'.$row['trk_dr_name'].'</td>
                        <td data-column="Driver Phone">+'.$row['trk_dr_phone_code'].' '.$row['trk_dr_phone'].'</td>
                        <td data-column="Driver License">
                            <a href="'.$row['trk_dr_license'].'" target="_blank" title="View File"><i class="fas fa-file"></i></a>
                            &nbsp;&nbsp;&nbsp;
                            <a href="'.$row['trk_dr_license'].'" title="Download File" download="'.$row['trk_dr_license'].'_license"><i class="fas fa-file-download"></i></a>
                        </td>
                        <td data-column="Status">'.$sta.'</td>
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
    else
    {
        echo "Server error";
    }
?>