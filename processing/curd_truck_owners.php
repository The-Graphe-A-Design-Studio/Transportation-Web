<?php

    include('../session.php');

    $connect = new PDO("mysql:host=$hostName; dbname=$dbName", $userName, $password);

    if(isset($_POST["action"]))
    {
        $query = "select * from truck_owners where to_on = '1'";

        if(isset($_POST["trial"]))
        {
            $query .= " AND to_account_on = '1'";
        }

        if(isset($_POST["subscription"]))
        {
            $query .= " AND to_account_on = '2'";
        }

        if(isset($_POST["nothing"]))
        {
            $query .= "";
        }

        if(isset($_POST["not_verified"]))
        {
            $query .= " AND to_verified = '0'";
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
            $query .= " AND to_phone LIKE '$se%' order by to_id desc";
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
                        <th>Phone</th>
                        <th>Bank A/c</th>
                        <th>IFSC</th>
                        <th>Trucks Own</th>
                        <th>Total Service</th>
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
                        <td data-column="ID">'.$row['to_id'].'</td>
                        <td data-column="Reg. Date">'.$date.'</td>
                        <td data-column="Name">'.$row['to_name'].'</td>
                        <td data-column="Phone">+'.$row['to_phone_code'].' '.$row['to_phone'].' ('.$row['to_otp'].')</td>
                        <td data-column="Bank A/c">'.$row['to_bank'].'</td>
                        <td data-column="IFSC">'.$row['to_ifsc'].'</td>
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

                $service = "select count(*) from deliveries where to_id = '".$row['to_id']."' and del_status = 2";
                $service_get = mysqli_query($link, $service);
                $service_row = mysqli_fetch_array($service_get, MYSQLI_ASSOC);

                if($service_row['count(*)'] == 0)
                {
                    $service_count = "Nothing";
                }
                else
                {
                    $service_count = $service_row['count(*)'];
                }

                $output .=
                '
                        <td data-column="Trucks Own">'.$truck_count.'</td>
                        <td data-column="Total Service">'.$service_count.'</td>
                        <td data-column="View">
                            <a class="btn btn-icon btn-info" href="truck_owner_profile?owner_id='.$row['to_id'].'"><i class="fas fa-eye" title="View Details"></i></a>
                        </td>
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
                <table>
                    <tbody>
                        <tr>
                            <td colspan="9">No truck owners found</td>
                        </tr>
                    </tbody>
                </table>
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
    elseif(isset($_POST['to_doc_id']) && isset($_POST['to_doc_status']))
    {
        if($_POST['to_doc_status'] === '0')
        {
            $sql = mysqli_query($link, "update truck_owner_docs set to_doc_verified = 1 where to_doc_id = '".$_POST['to_doc_id']."'");

            if($sql)
            {
                echo "Document verified";
            }
            else
            {
                echo "Something went wrong";
            }
        }
        elseif($_POST['to_doc_status'] === '1')
        {
            $sql = mysqli_query($link, "update truck_owner_docs set to_doc_verified = 0 where to_doc_id = '".$_POST['to_doc_id']."'");

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