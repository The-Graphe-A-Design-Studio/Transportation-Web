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
            $query .= " and trucks.trk_cat = '$se'";
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
                        <th>Number</th>
                        <th>Category</th>
                        <th>Type</th>
                        <th>Owner</th>
                        <th>Driver's Pic</th>
                        <th>Driver's Name</th>
                        <th>Driver's Phone</th>
                        <th>Driver's License</th>
                        <th>Status</th>
                        <th>View</th>
                    </thead>
                    <tbody>
            ";

            foreach($result as $row)
            {
                $cat = "select * from truck_cat where trk_cat_id = '".$row['trk_cat']."'";
                $g_cat = mysqli_query($link, $cat);
                $r_cat = mysqli_fetch_array($g_cat, MYSQLI_ASSOC);

                $cat_name = $r_cat['trk_cat_name'];

                $typ = "select * from truck_cat_type where ty_id = '".$row['trk_cat_type']."'";
                $g_typ = mysqli_query($link, $typ);
                $r_typ = mysqli_fetch_array($g_typ, MYSQLI_ASSOC);

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
                        <td data-column="Number">'.$row['trk_num'].'</td>
                        <td data-column="Category">'.$cat_name.'</td>
                        <td data-column="Type">'.$r_typ['ty_name'].'</td>
                        <td data-column="Owner"><a href="truck_owner_profile?owner_id='.$row['trk_owner'].'">'.$owner_name.'</a></td>
                        <td data-column="Driver Pic">
                            <img alt="driver_selfie_'.$row['trk_dr_phone'].'" src="'.$row['trk_dr_pic'].'" style="width: 50px; height: 50px; border-radius: 50%">
                        </td>
                        <td data-column="Driver Name">'.$row['trk_dr_name'].'</td>
                        <td data-column="Driver Phone">+'.$row['trk_dr_phone_code'].' '.$row['trk_dr_phone'].' ('.$row['trk_otp'].')</td>
                        <td data-column="Driver License">
                            <button class="btn btn-icon btn-primary" data-toggle="modal" title="View" data-target="#license"><i class="fas fa-eye" style="font-size: 1em !important;"></i></button>
                            <!-- Modal -->
                            <div class="mymodal modal fade" id="license" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background: rgba(0, 0, 0, 0.78) none repeat scroll 0% 0%">
                                <div class="modal-dialog" role="document">
                                    <img src="'.$row['trk_dr_license'].'" style="max-width: 100%" alt="truck_driver_license_'.$row['trk_dr_phone'].'">
                                </div>
                            </div>
                        </td>
                        <td data-column="Status">'.$sta.'</td>
                        <td data-column="View">
                            <a class="btn btn-icon btn-info" href="truck_profile?truck_id='.$row['trk_id'].'"><i class="fas fa-eye" title="View Details"></i></a>
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
                <table>
                    <tbody>
                        <tr>
                            <td colspan="11">No trucks found</td>
                        </tr>
                    </tbody>
                </table>
            ';
        }
        
        echo $output;

    }
    else
    {
        echo "Server error";
    }
?>