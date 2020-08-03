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

        if(isset($_POST["reject"]))
        {
            $query .= " AND to_verified = '2'";
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
                        <th>ID</th>
                        <th>Reg. Date</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Trucks Own</th>
                        <th>City</th>
                        <th>Routes</th>
                        <th>State Permits</th>
                        <th>View</th>
                        <th>Accept / Reject</th>
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
                        <td data-column="ID">'.$row['to_id'].'</td>
                        <td data-column="Reg. Date">'.$date.'</td>
                        <td data-column="Name">'.$row['to_name'].'</td>
                        <td data-column="Email"><a href="mailto:'.$row['to_email'].'">'.$row['to_email'].'</td>
                        <td data-column="Phone">+'.$row['to_phone_code'].' '.$row['to_phone'].'</td>
                ';

                $truck = "select count(*) from trucks where trk_owner = '".$row['to_id']."'";
                $truck_get = mysqli_query($link, $truck);
                $truck_row = mysqli_fetch_array($truck_get, MYSQLI_ASSOC);

                if($truck_row['count(*)'] == 0)
                {
                    $truck_count = "Not added";
                }
                else
                {
                    $truck_count = $truck_row['count(*)'];
                }

                $output .=
                '
                        <td data-column="Trucks Own">'.$truck_count.'</td>
                        <td data-column="City">'.$row['to_city'].'</td>
                        <td data-column="Routes">'.$row['to_routes'].'</td>
                        <td data-column="State Permits">'.$row['to_permits'].'</td>
                        <td data-column="View">
                            <a class="btn btn-icon btn-info" href="truck_owner_profile?owner_id='.$row['to_id'].'"><i class="fas fa-eye" title="View Details"></i></a>
                        </td>
                ';

                if($row['to_verified'] == 0)
                {
                    $reg =
                    '
                        <div style="display: inline-flex">
                            <form class="to_status">
                                <input type="text" name="to_id" value="'.$row['to_id'].'" hidden>
                                <input type="text" name="to_reg" value="1" hidden>
                                <button type="submit" class="btn btn-success btn-sm" title="Accept"><i class="fas fa-user-check"></i></button>
                            </form>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <form class="to_status">
                                <input type="text" name="to_id" value="'.$row['to_id'].'" hidden>
                                <input type="text" name="to_reg" value="2" hidden>
                                <button type="submit" class="btn btn-danger btn-sm" title="Reject"><i class="fas fa-user-minus"></i></button>
                            </form>
                        </div>
                    ';
                }
                elseif($row['to_verified'] == 1)
                {
                    $reg = '<span class="btn btn-sm btn-success">Accepted</span>';
                }
                else
                {
                    $reg = '<span class="btn btn-sm btn-danger">Rejected</span>';
                }

                $output .=
                '
                        <td data-column="Accept / Reject">'.$reg.'</td>
                    </tr>
                ';
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