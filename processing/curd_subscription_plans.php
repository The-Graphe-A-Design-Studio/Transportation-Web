<?php

    include('../session.php');

    $connect = new PDO("mysql:host=$hostName; dbname=$dbName", $userName, $password);

    if(isset($_POST["action"]))
    {
        $query = "select * from subscription_plans where plan_reg = 1";

        if(isset($_POST["active"]))
        {
            $query .= " and plan_status = '1'";
        }

        if(isset($_POST["inactive"]))
        {
            $query .= " and plan_status = '0'";
        }

        if(isset($_POST["branch"]))
        {
            if(!empty($_POST["branch"]))
            {
                $query .= " and plan_type = '".$_POST["branch"]."'";
            }
        }

        if(isset($_POST["nothing"]))
        {
            $query .= "";
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
                        <th>Name</th>
                        <th>Type</th>
                        <th>Original Price</th>
                        <th>Selling Price</th>
                        <th>Discount</th>
                        <th>Duration</th>
                        <th>Edit</th>
                        <th>Status</th>
                        <th>Delete</th>
                    </thead>
                    <tbody>
            ";

            foreach($result as $row)
            {

                if($row['plan_type'] == 1)
                {
                    $type = 
                    '
                        Shipper
                    ';
                }
                else
                {
                    $type = 
                    '
                        Truck Owner
                    ';
                }

                $discount = ( ( ( $row['plan_original_price'] - $row['plan_selling_price'] ) / $row['plan_original_price'] ) * 100 );

                if($row['plan_status'] == 0)
                {
                    $stat = 
                    '
                        <form class="edit_subs_plan">
                            <input type="text" class="form-control" name="plan_id" value="'.$row['plan_id'].'" hidden>
                            <input type="text" class="form-control" name="plan_status" value="1" hidden>
                            <button type="submit" class="btn btn-danger btn-md">Inactive</button>
                        </form>
                    ';
                }
                else
                {
                    $stat = 
                    '
                        <form class="edit_subs_plan">
                            <input type="text" class="form-control" name="plan_id" value="'.$row['plan_id'].'" hidden>
                            <input type="text" class="form-control" name="plan_status" value="0" hidden>
                            <button type="submit" class="btn btn-success btn-md">Active</button>
                        </form>
                    ';
                }

                $output .=
                '
                    <tr>
                        <td data-column="ID">'.$row['plan_id'].'</td>
                        <td data-column="Name">'.$row['plan_name'].'</td>
                        <td data-column="Type">'.$type.'</td>
                        <td data-column="Original Price">₹ '.$row['plan_original_price'].'</td>
                        <td data-column="Selling Price">₹ '.$row['plan_selling_price'].'</td>
                        <td data-column="Discount">'.round($discount, 2).'%</td>
                        <td data-column="Duration">'.$row['plan_duration'].' Months</td>
                        <td data-column="Edit">
                            <button class="btn btn-warning btn-md" data-toggle="collapse" data-target="#collapse_'.$row['plan_id'].'" 
                            aria-expanded="true" aria-controls="collapse_'.$row['plan_id'].'">Edit</button>
                        </td>
                        <td data-column="Status">'.$stat.'</td>
                        <td data-column="Delete">
                            <form class="edit_subs_plan">
                                <input type="text" class="form-control" name="delete_plan_id" value="'.$row['plan_id'].'" hidden>
                                <button type="submit" class="btn btn-icon btn-danger btn-md"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <tr class="collapse edit_plan" id="collapse_'.$row['plan_id'].'" style="border:2px solid rgb(255, 164, 38);">
                        <td colspan="10" style="padding: 0">
                            <form class="edit_subs_plan">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td style="border: none;" data-column="Name"><input type="text" class="form-control" name="edit_plan_name" value="'.$row['plan_name'].'"></td>
                                            <td style="border: none;" data-column="Original Price"><input type="text" class="form-control" name="edit_plan_original" value="'.$row['plan_original_price'].'"></td>
                                            <td style="border: none;" data-column="Selling Price"><input type="text" class="form-control" name="edit_plan_selling" value="'.$row['plan_selling_price'].'"></td>
                                            <td style="border: none;" data-column="Duration"><input type="text" class="form-control" name="edit_plan_duration" value="'.$row['plan_duration'].'"></td>
                                            <td style="border: none;" data-column="Update">
                                                <input type="text" class="form-control" name="edit_plan_id" value="'.$row['plan_id'].'" hidden>
                                                <button type="submit" class="btn btn-info btn-md">Update</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>
                        </td>
                    </tr>
                ';

            }

            $output .=
            '
                    </tbody>
                </table>

                <script type="text/javascript">
                    $(".edit_subs_plan").submit(function(e)
                    {
                        var form_data = $(this).serialize();
                        // alert(form_data);
                        var button_content = $(this).find("button[type=submit]");
                        $.ajax({
                            url: "processing/curd_subscription_plans.php",
                            data: form_data,
                            type: "POST",
                            success: function(data)
                            {
                                alert(data);
                                if(data === "Plan Updated" || data === "Plan Activated" || data === "Plan Deactivated" || data === "Plan Deleted")
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
    elseif(isset($_POST['newplanName']) && isset($_POST['newplanType']) && isset($_POST['newplanOriginal']) && isset($_POST['newplanSelling']) && isset($_POST['newplanDuration']))
    {
        $sql = "insert into subscription_plans (plan_name, plan_type, plan_original_price, plan_selling_price, plan_duration) values ('".$_POST['newplanName']."', 
                '".$_POST['newplanType']."', '".$_POST['newplanOriginal']."', '".$_POST['newplanSelling']."', '".$_POST['newplanDuration']."')";
        $run = mysqli_query($link, $sql);

        if($run)
        {
            echo "New Plan Created";
        }
        else
        {
            echo "Something went wrong";
        }
    }
    elseif(isset($_POST['edit_plan_id']) && isset($_POST['edit_plan_name']) && isset($_POST['edit_plan_original']) && isset($_POST['edit_plan_selling']) && isset($_POST['edit_plan_duration']))
    {
        $sql = "update subscription_plans set plan_name = '".$_POST['edit_plan_name']."', plan_original_price = '".$_POST['edit_plan_original']."', 
                plan_selling_price = '".$_POST['edit_plan_selling']."', plan_duration = '".$_POST['edit_plan_duration']."' where plan_id = '".$_POST['edit_plan_id']."'";
        $run = mysqli_query($link, $sql);

        if($run)
        {
            echo "Plan Updated";
        }
        else
        {
            echo "Something went wrong";
        }
    }
    elseif(isset($_POST['plan_id']) && isset($_POST['plan_status']))
    {

        $sql = "update subscription_plans set plan_status = '".$_POST['plan_status']."' where plan_id = '".$_POST['plan_id']."'";
        $run = mysqli_query($link, $sql);

        if($run && $_POST['plan_status'] == 1)
        {
            echo "Plan Activated";
        }
        elseif($run && $_POST['plan_status'] == 0)
        {
            echo "Plan Deactivated";
        }
        else
        {
            echo "Something went wrong";
        }
    }
    elseif(isset($_POST['delete_plan_id']))
    {
        $sql = "delete from subscription_plans where plan_id = '".$_POST['delete_plan_id']."'";
        $run = mysqli_query($link, $sql);

        if($run)
        {
            echo "Plan Deleted";
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