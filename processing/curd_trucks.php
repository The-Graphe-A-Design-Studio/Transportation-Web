<?php

    include('../session.php');

    $connect = new PDO("mysql:host=$hostName; dbname=$dbName", $userName, $password);

    if(isset($_POST["action"]))
    {
        $query = "select * from trucks where trk_on = '1'";

        if(isset($_POST["active"]))
        {
            $query .= " AND trk_active = '1'";
        }

        if(isset($_POST["inactive"]))
        {
            $query .= " AND trk_active = '0'";
        }

        if(isset($_POST["nothing"]))
        {
            $query .= "";
        }

        if(isset($_POST["trip"]))
        {
            $query .= " AND trk_on_trip = '1'";
        }

        // if(!empty($_POST["start_date"]) && empty($_POST["end_date"]))
        // {
        //     $s_date = date_create($_POST["start_date"]);
        //     $s_date = date_format($s_date, "Y-m-d");
            
        //     $e_date = date_create($_POST["end_date"]);
        //     $e_date = date_format($e_date, "Y-m-d");

        //     $query .= " and to_registered >= '".$s_date."'";
        // }
        // elseif(empty($_POST["start_date"]) && !empty($_POST["end_date"]))
        // {
        //     $s_date = date_create($_POST["start_date"]);
        //     $s_date = date_format($s_date, "Y-m-d");
            
        //     $e_date = date_create($_POST["end_date"]);
        //     $e_date = date_format($e_date, "Y-m-d");

        //     $query .= " and to_registered <= '".$e_date."'";
        // }
        // elseif(!empty($_POST["start_date"]) && !empty($_POST["end_date"]))
        // {
        //     $s_date = date_create($_POST["start_date"]);
        //     $s_date = date_format($s_date, "Y-m-d");
            
        //     $e_date = date_create($_POST["end_date"]);
        //     $e_date = date_format($e_date, "Y-m-d");

        //     $query .= " and to_registered >= '".$s_date."' and to_registered <= '".$e_date."'";
        // }
        // else
        // {
        //     $query .= "";
        // }

        if(isset($_POST['search']))
        {
            $se = $_POST['search'];
            $query .= " AND trk_num LIKE '$se%' order by trk_id desc";
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
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="'.$row['trk_dr_license'].'" title="Download File" download="'.$row['trk_dr_license'].'_license"><i class="fas fa-file-download"></i></a>
                        </td>
                        <td data-column="Status">'.$sta.'</td>
                ';

                // $truck = "select count(*) from trucks where trk_owner = '".$row['to_id']."'";
                // $truck_get = mysqli_query($link, $truck);
                // $truck_row = mysqli_fetch_array($truck_get, MYSQLI_ASSOC);

                // if($truck_row['count(*)'] == 0)
                // {
                //     $truck_count = "Not added";
                // }
                // else
                // {
                //     $truck_count = $truck_row['count(*)'];
                // }

                // $output .=
                // '
                //         <td data-column="Trucks Own">'.$truck_count.'</td>
                //         <td data-column="City">'.$row['to_city'].'</td>
                //         <td data-column="Routes">'.$row['to_routes'].'</td>
                //         <td data-column="State Permits">'.$row['to_permits'].'</td>
                //         <td data-column="View">
                //             <a class="btn btn-icon btn-info" href="truck_owner_profile?owner_id='.$row['to_id'].'"><i class="fas fa-eye" title="View Details"></i></a>
                //         </td>
                // ';

                // if($row['to_verified'] == 0)
                // {
                //     $reg =
                //     '
                //         <div style="display: inline-flex">
                //             <form class="to_status">
                //                 <input type="text" name="to_id" value="'.$row['to_id'].'" hidden>
                //                 <input type="text" name="to_reg" value="1" hidden>
                //                 <button type="submit" class="btn btn-success btn-sm" title="Accept"><i class="fas fa-user-check"></i></button>
                //             </form>
                //             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                //             <form class="to_status">
                //                 <input type="text" name="to_id" value="'.$row['to_id'].'" hidden>
                //                 <input type="text" name="to_reg" value="2" hidden>
                //                 <button type="submit" class="btn btn-danger btn-sm" title="Reject"><i class="fas fa-user-minus"></i></button>
                //             </form>
                //         </div>
                //     ';
                // }
                // elseif($row['to_verified'] == 1)
                // {
                //     $reg = '<span class="btn btn-sm btn-success">Accepted</span>';
                // }
                // else
                // {
                //     $reg = '<span class="btn btn-sm btn-danger">Rejected</span>';
                // }

                // $output .=
                // '
                //         <td data-column="Accept / Reject">'.$reg.'</td>
                //     </tr>
                // ';
            }

            $output .=
            '
                    </tbody>
                </table>
            
                <script>
                    $(".to_status").submit(function(e)
                    {
                        var form_data = $(this).serialize();
                        // alert(form_data);
                        var button_content = $(this).find("button[type=submit]");
                        $.ajax({
                            url: "processing/curd_truck_owners.php",
                            data: form_data,
                            type: "POST",
                            success: function(data)
                            {
                                alert(data);
                                if(data === "Truck Owner Accepted" || data === "Truck Owner Rejected")
                                {
                                    $( "#refresh_btn" ).trigger( "click" );
                                }
                            }
                        });
                        e.preventDefault();
                    });
                </script>
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
    elseif(isset($_POST['to_id']) && isset($_POST['to_reg']))
	{
        if($_POST['to_reg'] == 1)
        {
            $acc = "update truck_owners set to_verified = '1' where to_id = '".$_POST['to_id']."'";
            if(mysqli_query($link, $acc))
            {
                echo "Truck Owner Accepted";
            }
            else
            {
                "Server error";
            }
        }
        elseif($_POST['to_reg'] == 2)
        {
            $acc = "update truck_owners set to_verified = '2' where to_id = '".$_POST['to_id']."'";
            if(mysqli_query($link, $acc))
            {
                echo "Truck Owner Rejected";
            }
            else
            {
                "Server error";
            }
        }
		else
		{
			echo "Something went wrong";
		}
	}
    else
    {
        echo "Server error";
    }
?>