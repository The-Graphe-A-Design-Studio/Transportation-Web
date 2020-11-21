<?php

    include('../session.php');

    $connect = new PDO("mysql:host=$hostName; dbname=$dbName", $userName, $password);

    if(isset($_POST["action"]))
    {
        $query = "select * from coupons where co_default = 1";

        if(isset($_POST["active"]))
        {
            $query .= " and co_status = '1'";
        }

        if(isset($_POST["inactive"]))
        {
            $query .= " and co_status = '0'";
        }

        if(isset($_POST["nothing"]))
        {
            $query .= "";
        }

        if(isset($_POST["branch"]))
        {
            if(!empty($_POST["branch"]))
            {
                $query .= " and co_users = '".$_POST["branch"]."'";
            }
        }

        $query .= " order by co_id desc";

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
                        <th>Name/Code</th>
                        <th>Users</th>
                        <th>Discount</th>
                        <th>Start Date</th>
                        <th>Expire Date</th>
                        <th>Status</th>
                        <th>Delete</th>
                        <th>User Count</th>
                    </thead>
                    <tbody>
            ";

            foreach($result as $row)
            {

                if($row['co_users'] == 1)
                {
                    $type = 
                    '
                        Shippers
                    ';
                }
                elseif($row['co_users'] == 2)
                {
                    $type = 
                    '
                        Truck Owners
                    ';
                }
                else
                {
                    $type = 
                    '
                        Add on Trucks
                    ';
                }

                if($row['co_status'] == 0)
                {
                    $stat = 
                    '
                        <form class="edit_coupon">
                            <input type="text" class="form-control" name="co_id" value="'.$row['co_id'].'" hidden>
                            <input type="text" class="form-control" name="co_status" value="1" hidden>
                            <button type="submit" class="btn btn-danger btn-md">Inactive</button>
                        </form>
                    ';
                }
                else
                {
                    $stat = 
                    '
                        <form class="edit_coupon">
                            <input type="text" class="form-control" name="co_id" value="'.$row['co_id'].'" hidden>
                            <input type="text" class="form-control" name="co_status" value="0" hidden>
                            <button type="submit" class="btn btn-success btn-md">Active</button>
                        </form>
                    ';
                }

                $cu = "select * from coupon_users where cu_coupon = '".$row['co_name']."'";
                $cu_r = mysqli_query($link, $cu);
                $count_cu = mysqli_num_rows($cu_r);

                $output .=
                '
                    <tr>
                        <td data-column="ID">'.$row['co_id'].'</td>
                        <td data-column="Name/Code">'.$row['co_name'].'</td>
                        <td data-column="Users">'.$type.'</td>
                        <td data-column="Discount">'.$row['co_discount'].'%</td>
                        <td data-column="Start Date">'.date_format(date_create($row['co_start_date']), 'd M, Y h:i:s A').'</td>
                        <td data-column="Expire Date">'.date_format(date_create($row['co_expire_date']), 'd M, Y h:i:s A').'</td>
                        <td data-column="Status">'.$stat.'</td>
                        <td data-column="Delete">
                            <form class="edit_coupon">
                                <input type="text" class="form-control" name="delete_co_id" value="'.$row['co_id'].'" hidden>
                                <button type="submit" class="btn btn-icon btn-danger btn-md"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                        <td data-column="User Count">'.$count_cu.'</td>
                    </tr>
                ';

            }

            $output .=
            '
                    </tbody>
                </table>

                <script type="text/javascript">
                    $(".edit_coupon").submit(function(e)
                    {
                        var form_data = $(this).serialize();
                        // alert(form_data);
                        var button_content = $(this).find("button[type=submit]");
                        $.ajax({
                            url: "processing/curd_coupons.php",
                            data: form_data,
                            type: "POST",
                            success: function(data)
                            {
                                alert(data);
                                if(data === "Coupon Activated" || data === "Coupon Deactivated" || data === "Coupon Deleted")
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
                            <td colspan="10">No coupons found</td>
                        </tr>
                    </tbody>
                </table>
            ';
        }
        
        echo $output;

    }
    elseif(isset($_POST['newcouponName']) && isset($_POST['newcouponType']) && isset($_POST['newcouponDiscount']) && isset($_POST['newcouponExpire']))
    {
        $start_date = date('Y-m-d H:i:s');
        
        $expire_date = date_create($_POST['newcouponExpire']);
        $expire_date = date_format($expire_date, "Y-m-d 23:59:59");

        $c = "select * from coupons where co_name = '".$_POST['newcouponName']."'";
        $r = mysqli_query($link, $c);
        $count = mysqli_num_rows($r);

        if($count >= 1)
        {
            echo "Coupon already exists";
        }
        else
        {
            $sql = "insert into coupons (co_name, co_users, co_discount, co_start_date, co_expire_date) values ('".$_POST['newcouponName']."', '".$_POST['newcouponType']."', 
                '".$_POST['newcouponDiscount']."', '$start_date', '$expire_date')";
            $run = mysqli_query($link, $sql);

            if($run)
            {
                echo "New Coupon Created";
            }
            else
            {
                echo "Something went wrong";
            }
        }
    }
    elseif(isset($_POST['co_id']) && isset($_POST['co_status']))
    {

        $sql = "update coupons set co_status = '".$_POST['co_status']."' where co_id = '".$_POST['co_id']."'";
        $run = mysqli_query($link, $sql);

        if($run && $_POST['co_status'] == 1)
        {
            echo "Coupon Activated";
        }
        elseif($run && $_POST['co_status'] == 0)
        {
            echo "Coupon Deactivated";
        }
        else
        {
            echo "Something went wrong";
        }
    }
    elseif(isset($_POST['delete_co_id']))
    {
        $sql = "delete from coupons where co_id = '".$_POST['delete_co_id']."'";
        $run = mysqli_query($link, $sql);

        if($run)
        {
            echo "Coupon Deleted";
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