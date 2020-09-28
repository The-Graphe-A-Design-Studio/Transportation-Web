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
            $query .= " AND cust_order.or_status = '2'";
        }

        if(isset($_POST["nothing"]))
        {
            $query .= "";
        }

        if(isset($_POST["going"]))
        {
            $query .= " AND cust_order.or_status = '4'";
        }

        if(isset($_POST["complete"]))
        {
            $query .= " AND cust_order.or_status = '5'";
        }

        if(isset($_POST["cancel"]))
        {
            $query .= " AND cust_order.or_status = '3'";
        }

        if(isset($_POST["expired"]))
        {
            $query .= " AND cust_order.or_status = '0'";
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

        if(isset($_POST['id']))
        {
            $seid = $_POST['id'];
            $query .= " AND customers.cu_id LIKE '$seid%'";
        }

        if(isset($_POST['search']))
        {
            $se = $_POST['search'];
            $query .= " AND customers.cu_phone LIKE '$se%' order by cust_order.or_id";
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

                if($row['or_status'] == 1)
                {
                    $live = "border-left: 3px solid #008080";
                }
                elseif($row['or_status'] == 2)
                {
                    $live = "border-left: 3px solid #4682B4";
                }
                elseif($row['or_status'] == 3)
                {
                    $live = "border-left: 3px solid #FFA500";
                }
                elseif($row['or_status'] == 4)
                {
                    $live = "border-left: 3px solid #4169E1";
                }
                elseif($row['or_status'] == 5)
                {
                    $live = "border-left: 3px solid #4B0082";
                }
                else
                {
                    $live = "border-left: 3px solid #FF0000";
                }

                $output .=
                '
                    <tr>
                        <td data-column="ID" style="'.$live.'">'.$row['or_id'].'</td>
                        <td data-column="Reg. Date">'.$date.'</td>
                        <td data-column="Shipper"><a href="shipper_profile?shipper_id='.$row['cu_id'].'">+'.$row['cu_phone_code'].' '.$row['cu_phone'].'</a></td>
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
                            '.$row_source['or_source_city'].', '.$row_source['or_source_state'].'
                            <br>to<br>
                            '.$row_end['or_des_city'].', '.$row_end['or_des_state'].'
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
    elseif(isset($_POST['admin_expected_price']) && isset($_POST['load_id']))
    {
        $sql = "update cust_order set or_admin_expected_price = '".$_POST['admin_expected_price']."', or_status = 2 where or_id = '".$_POST['load_id']."'";
        $run_aql = mysqli_query($link, $sql);

        if($run_aql)
        {
            echo "Load reset, activate again & Admin Expected Price Updated";
        }
        else
        {
            echo "Something went wrong";
        }
    }
    elseif(isset($_POST['load_status']) && isset($_POST['load_id_to_set']))
    {
        $load = "select * from cust_order where or_id = '".$_POST['load_id_to_set']."'";
        $run_load = mysqli_query($link, $load);
        $row_load = mysqli_fetch_array($run_load, MYSQLI_ASSOC);

        if($row_load['or_admin_expected_price'] == 0.00)
        {
            echo "Set Expected Price";
        }
        else
        {
            $sql = "update cust_order set or_status = '".$_POST['load_status']."' where or_id = '".$_POST['load_id_to_set']."'";
            $run_aql = mysqli_query($link, $sql);

            if($run_aql)
            {
                echo "Load Status Updated";
            }
            else
            {
                echo "Something went wrong";
            }
        }
    }
    elseif(isset($_POST['truck_owner_data']) && isset($_POST['load_id']))
    {
        $query = "select * from truck_owners where to_on = 1";

        if(isset($_POST['search_id']))
        {
            $se = $_POST['search_id'];
            $query .= " and to_id LIKE '$se%'";
        }

        if(isset($_POST['search_num']))
        {
            $see = $_POST['search_num'];
            $query .= " and to_phone LIKE '$see%'";
        }

        $query .= " limit 1";

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
                        <th>Phone</th>
                        <th>Name</th>
                        <th>Subscription</th>
                    </thead>
                    <tbody>
            ';

            foreach($result as $row)
            {
                if($row['to_account_on'] == 1)
                {
                    $subs = '<span class="btn btn-md btn-success">Yes</span>';
                }
                else
                {
                    $subs = '<span class="btn btn-md btn-warning">No</span>';
                }
                $output .=
                '
                    <tr>
                        <td data-column="ID">'.$row['to_id'].'</td>
                        <td data-column="Phone">'.$row['to_phone'].'</td>
                        <td data-column="Name">'.$row['to_name'].'</td>
                        <td data-column="Subscription">'.$subs.'</td>
                    </tr>
                    <tr>
                        <td colspan="4" style="padding-left: 8px !important">
                            <form class="set-owner-bid text-left">
                                <lable><b>Set Bid</b></lable>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="owner_bid_price" value="">
                                    <input type="text" name="owner_id" value="'.$row['to_id'].'" hidden>
                                    <input type="text" name="load_id" value="'.$_POST['load_id'].'" hidden>
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">Set</button>
                                    </div>
                                </div>
                            </form>
                        </td>
                    </tr>
                ';
            }

            $output .=
            '
                    </tbody>
                </table>

                <script>
                    $(".set-owner-bid").submit(function(e)
                    {
                        var form_data = $(this).serialize();
                        // alert(form_data);
                        var button_content = $(this).find("button[type=submit]");
                        button_content.addClass("disabled btn-progress");
                        $.ajax({
                            url: "processing/curd_loads.php",
                            data: form_data,
                            type: "POST",
                            success: function(data)
                            {
                                alert(data);
                                button_content.removeClass("disabled btn-progress");
                                if(data === "Bid set")
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
                Search Truck Owner
            ';
        }
        
        echo $output;
    }
    elseif(isset($_POST['owner_id']) && isset($_POST['load_id']) && isset($_POST['owner_bid_price']))
    {
        $sqle = "SELECT * FROM bidding where bid_user_type = '1' and bid_user_id = '".$_POST['owner_id']."' and load_id = '".$_POST['load_id']."'";
        $checke = mysqli_query($link, $sqle);
        $rowe = mysqli_fetch_array($checke, MYSQLI_ASSOC);
        $counte = mysqli_num_rows($checke);
        if($counte >= 1)
        {
            echo "Bidding already done";
        }
        else
        {
            $sql = "insert into bidding (bid_user_type, bid_user_id, load_id, bid_expected_price) values ('1', '".$_POST['owner_id']."', '".$_POST['load_id']."', 
                '".$_POST['owner_bid_price']."')";
            $run = mysqli_query($link, $sql);

            if($run)
            {
                echo "Bid set";
            }
            else
            {
                echo "Something went wrong";
            }
        }
    }
    elseif(isset($_POST['driver_data']) && isset($_POST['load_id']))
    {
        $query = "select trucks.*, truck_owners.* from trucks, truck_owners where trucks.trk_owner = truck_owners.to_id and truck_owners.to_verified = 1";

        if(isset($_POST['search_driver_id']))
        {
            $se = $_POST['search_driver_id'];
            $query .= " and trk_id LIKE '$se%'";
        }

        if(isset($_POST['search_driver_num']))
        {
            $see = $_POST['search_driver_num'];
            $query .= " and trk_dr_phone LIKE '$see%'";
        }

        $query .= " limit 1";

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
                        <th>Phone</th>
                        <th>Name</th>
                        <th>Truck Num</th>
                    </thead>
                    <tbody>
            ';

            foreach($result as $row)
            {
                $output .=
                '
                    <tr>
                        <td data-column="ID">'.$row['trk_id'].'</td>
                        <td data-column="Phone">'.$row['trk_dr_phone'].'</td>
                        <td data-column="Name">'.$row['trk_dr_name'].'</td>
                        <td data-column="Truck Num">'.$row['trk_num'].'</td>
                    </tr>
                    <tr>
                        <td colspan="4" style="padding-left: 8px !important">
                            <form class="set-driver-bid text-left">
                                <lable><b>Set Bid</b></lable>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="driver_bid_price" value="">
                                    <input type="text" name="driver_id" value="'.$row['trk_id'].'" hidden>
                                    <input type="text" name="load_id" value="'.$_POST['load_id'].'" hidden>
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">Set</button>
                                    </div>
                                </div>
                            </form>
                        </td>
                    </tr>
                ';
            }

            $output .=
            '
                    </tbody>
                </table>

                <script>
                    $(".set-driver-bid").submit(function(e)
                    {
                        var form_data = $(this).serialize();
                        // alert(form_data);
                        var button_content = $(this).find("button[type=submit]");
                        button_content.addClass("disabled btn-progress");
                        $.ajax({
                            url: "processing/curd_loads.php",
                            data: form_data,
                            type: "POST",
                            success: function(data)
                            {
                                alert(data);
                                button_content.removeClass("disabled btn-progress");
                                if(data === "Bid set")
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
                Search Driver
            ';
        }
        
        echo $output;
    }
    elseif(isset($_POST['driver_id']) && isset($_POST['load_id']) && isset($_POST['driver_bid_price']))
    {
        $sqle = "SELECT * FROM bidding where bid_user_type = '2' and bid_user_id = '".$_POST['driver_id']."' and load_id = '".$_POST['load_id']."'";
        $checke = mysqli_query($link, $sqle);
        $rowe = mysqli_fetch_array($checke, MYSQLI_ASSOC);
        $counte = mysqli_num_rows($checke);
        if($counte >= 1)
        {
            echo "Bidding already done";
        }
        else
        {
            $sql = "insert into bidding (bid_user_type, bid_user_id, load_id, bid_expected_price) values ('2', '".$_POST['driver_id']."', '".$_POST['load_id']."', 
                '".$_POST['driver_bid_price']."')";
            $run = mysqli_query($link, $sql);

            if($run)
            {
                echo "Bid set";
            }
            else
            {
                echo "Something went wrong";
            }
        }
    }
    elseif(isset($_POST["bidding_data"]) && isset($_POST['load_id']))
    {
        $query = "SELECT * from bidding WHERE load_id = '".$_POST['load_id']."' AND bid_default = 1";

        if(isset($_POST["owners"]))
        {
            $query .= " AND bid_user_type = '1'";
        }

        if(isset($_POST["drivers"]))
        {
            $query .= " AND bid_user_type = '2'";
        }

        if(isset($_POST["nothing"]))
        {
            $query .= "";
        }

        $query .= " ORDER BY bid_id DESC";

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
                        <th>Type</th>
                        <th>User ID</th>
                        <th>Phone</th>
                        <th>Bid Price</th>
                        <th>Status</th>
                        <th>Delete</th>
                    </thead>
                    <tbody>
            ';

            foreach($result as $row)
            {
                if($row['bid_user_type'] == 1)
                {
                    $type = "Truck Onwer";

                    $owner = "select * from truck_owners where to_id = '".$row['bid_user_id']."'";
                    $get_owner = mysqli_query($link, $owner);
                    $row_owner = mysqli_fetch_array($get_owner, MYSQLI_ASSOC);

                    $id = $row_owner['to_id'];
                    $phone = $row_owner['to_phone_code'].' '.$row_owner['to_phone'];
                }
                else
                {
                    $type = "Driver";

                    $driver = "select * from trucks where trk_id = '".$row['bid_user_id']."'";
                    $get_driver = mysqli_query($link, $driver);
                    $row_driver = mysqli_fetch_array($get_driver, MYSQLI_ASSOC);

                    $id = $row_driver['trk_id'];
                    $phone = $row_driver['trk_dr_phone_code'].' '.$row_driver['trk_dr_phone'];
                }

                if($row['bid_status'] == 0)
                {
                    $status =
                    '
                        <form class="edit-bid-status">
                            <input type="text" name="bid_id" value="'.$row['bid_id'].'" hidden>
                            <input type="text" name="bid_status_value" value="1" hidden>
                            <button class="btn btn-md btn-success" type="submit">Accept</button>
                        </form>
                    ';
                }
                elseif($row['bid_status'] == 1)
                {
                    $status =
                    '
                        <form class="edit-bid-status">
                            <input type="text" name="bid_id" value="'.$row['bid_id'].'" hidden>
                            <input type="text" name="bid_status_value" value="0" hidden>
                            <button class="btn btn-md btn-danger" type="submit">Reject</button>
                        </form>
                    ';
                }
                elseif($row['bid_status'] == 2)
                {
                    $status =
                    '
                        <span class="btn btn-md btn-info">Accepted by Shipper</span>
                    ';
                }
                else
                {
                    $status =
                    '
                        <span class="btn btn-md btn-success">Accepted by Owner</span>
                    ';
                }

                $output .=
                '
                    <tr>
                        <td data-column="ID">'.$row['bid_id'].'</td>
                        <td data-column="Type">'.$type.'</td>
                        <td data-column="User ID">'.$id.'</td>
                        <td data-column="Phone">+'.$phone.'</td>
                        <td data-column="Bid Price">'.$row['bid_expected_price'].'</td>
                        <td data-column="Status">'.$status.'</td>
                        <td data-column="Delete">
                            <form class="edit-bid-status">
                                <input type="text" name="delete_bid_id" value="'.$row['bid_id'].'" hidden>
                                <button class="btn btn-md btn-icon btn-danger" type="submit"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    
                ';
            }

            $output .=
            '
                    </tbody>
                </table>

                <script>
                    $(".edit-bid-status").submit(function(e)
                    {
                        var form_data = $(this).serialize();
                        // alert(form_data);
                        var button_content = $(this).find("button[type=submit]");
                        button_content.addClass("disabled btn-progress");
                        $.ajax({
                            url: "processing/curd_loads.php",
                            data: form_data,
                            type: "POST",
                            success: function(data)
                            {
                                alert(data);
                                button_content.removeClass("disabled btn-progress");
                                if(data === "Bid Status Updated" || data === "Bid removed")
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
    elseif(isset($_POST['edit_bid_price']) && isset($_POST['bid_id']))
    {
        $sql = "update bidding set bid_expected_price = '".$_POST['edit_bid_price']."' where bid_id = '".$_POST['bid_id']."'";
        $run = mysqli_query($link, $sql);

        if($run)
        {
            echo "Bid Updated";
        }
        else
        {
            echo "Something went wrong";
        }
    }
    elseif(isset($_POST['bid_id']) && isset($_POST['bid_status_value']))
    {
        $sql = "update bidding set bid_status = '".$_POST['bid_status_value']."' where bid_id = '".$_POST['bid_id']."'";
        $run = mysqli_query($link, $sql);

        if($run)
        {
            echo "Bid Status Updated";
        }
        else
        {
            echo "Something went wrong";
        }
    }
    elseif(isset($_POST['delete_bid_id']))
    {
        $sql = "delete from bidding where bid_id = '".$_POST['delete_bid_id']."'";
        $run = mysqli_query($link, $sql);

        if($run)
        {
            echo "Bid removed";
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