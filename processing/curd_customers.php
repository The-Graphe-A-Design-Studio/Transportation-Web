<?php

    include('../session.php');

    $connect = new PDO("mysql:host=$hostName; dbname=$dbName", $userName, $password);

    if(isset($_POST["action"]))
    {
        $query = "select * from customers where cu_type = '1'";

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
            $query .= " AND cu_name LIKE '$se%' order by cu_id desc";
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
                        <th>City</th>
                        <th>View</th>
                        <th>Accept / Reject</th>
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
                        <td data-column="Name">'.$row['cu_name'].'</td>
                        <td data-column="Email"><a href="mailto:'.$row['cu_email'].'">'.$row['cu_email'].'</td>
                        <td data-column="Phone">+'.$row['cu_phone_code'].' '.$row['cu_phone'].'</td>
                        <td data-column="City">'.$row['cu_city'].'</td>
                        <td data-column="View">
                            <a class="btn btn-icon btn-info" href="customer_profile?customer_id='.$row['cu_id'].'"><i class="fas fa-eye" title="View Details"></i></a>
                        </td>
                ';

                if($row['cu_verified'] == 0)
                {
                    $reg =
                    '
                        <div style="display: inline-flex">
                            <form class="cu_status">
                                <input type="text" name="cu_id" value="'.$row['cu_id'].'" hidden>
                                <input type="text" name="cu_reg" value="1" hidden>
                                <button type="submit" class="btn btn-success btn-sm" title="Accept"><i class="fas fa-user-check"></i></button>
                            </form>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <form class="cu_status">
                                <input type="text" name="cu_id" value="'.$row['cu_id'].'" hidden>
                                <input type="text" name="cu_reg" value="2" hidden>
                                <button type="submit" class="btn btn-danger btn-sm" title="Reject"><i class="fas fa-user-minus"></i></button>
                            </form>
                        </div>
                    ';
                }
                elseif($row['cu_verified'] == 1)
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
                    $(".cu_status").submit(function(e)
                    {
                        var form_data = $(this).serialize();
                        // alert(form_data);
                        var button_content = $(this).find("button[type=submit]");
                        $.ajax({
                            url: "processing/curd_customers.php",
                            data: form_data,
                            type: "POST",
                            success: function(data)
                            {
                                alert(data);
                                if(data === "Customer Accepted" || data === "Customer Rejected")
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
    elseif(isset($_POST["action1"]))
    {
        $query = "select * from customers where cu_type = '2'";

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
            $query .= " AND cu_name LIKE '$se%' order by cu_id desc";
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
                        <th>Company Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>City</th>
                        <th>View</th>
                        <th>Accept / Reject</th>
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
                        <td data-column="Name">'.$row['cu_name'].'</td>
                        <td data-column="Company Name">'.$row['cu_co_name'].'</td>
                        <td data-column="Email"><a href="mailto:'.$row['cu_email'].'">'.$row['cu_email'].'</td>
                        <td data-column="Phone">+'.$row['cu_phone_code'].' '.$row['cu_phone'].'</td>
                        <td data-column="City">'.$row['cu_city'].'</td>
                        <td data-column="View">
                            <a class="btn btn-icon btn-info" href="customer_profile?customer_id='.$row['cu_id'].'"><i class="fas fa-eye" title="View Details"></i></a>
                        </td>
                ';

                if($row['cu_verified'] == 0)
                {
                    $reg =
                    '
                        <div style="display: inline-flex">
                            <form class="cu_status">
                                <input type="text" name="cu_id" value="'.$row['cu_id'].'" hidden>
                                <input type="text" name="cu_reg" value="1" hidden>
                                <button type="submit" class="btn btn-success btn-sm" title="Accept"><i class="fas fa-user-check"></i></button>
                            </form>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <form class="cu_status">
                                <input type="text" name="cu_id" value="'.$row['cu_id'].'" hidden>
                                <input type="text" name="cu_reg" value="2" hidden>
                                <button type="submit" class="btn btn-danger btn-sm" title="Reject"><i class="fas fa-user-minus"></i></button>
                            </form>
                        </div>
                    ';
                }
                elseif($row['cu_verified'] == 1)
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
                    $(".cu_status").submit(function(e)
                    {
                        var form_data = $(this).serialize();
                        // alert(form_data);
                        var button_content = $(this).find("button[type=submit]");
                        $.ajax({
                            url: "processing/curd_customers.php",
                            data: form_data,
                            type: "POST",
                            success: function(data)
                            {
                                alert(data);
                                if(data === "Customer Accepted" || data === "Customer Rejected")
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
    elseif(isset($_POST['cu_id']) && isset($_POST['cu_reg']))
	{
        if($_POST['cu_reg'] == 1)
        {
            $acc = "update customers set cu_verified = '1' where cu_id = '".$_POST['cu_id']."'";
            if(mysqli_query($link, $acc))
            {
                echo "Customer Accepted";
            }
            else
            {
                "Server error";
            }
        }
        elseif($_POST['cu_reg'] == 2)
        {
            $acc = "update customers set cu_verified = '2' where cu_id = '".$_POST['cu_id']."'";
            if(mysqli_query($link, $acc))
            {
                echo "Customer Rejected";
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