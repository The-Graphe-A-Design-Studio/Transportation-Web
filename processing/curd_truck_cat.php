<?php
    include('../session.php');

    if(isset($_POST['new_page_truck_cat']))
    {
        foreach($_POST['new_page_truck_cat'] as $truck_cat)
        {
            if(!empty($truck_cat))
            {
                $truck_cat = mysqli_real_escape_string($link, $truck_cat);

                $rtt = "insert into truck_cat (trk_cat_name) values ('$truck_cat')";
                mysqli_query($link, $rtt);
            }
        }
        echo "New Truck Categories Added";
    }
    elseif(isset($_POST['edit_truck_cat_total']))
    {
        for($i = 1; $i <= $_POST['edit_truck_cat_total']; $i++)
        {
            $edit_truck_cat = $_POST['edit_truck_cat_'.$i];
            $edit_truck_cat_id = $_POST['edit_truck_cat_id_'.$i];
            
            $edit_truck_cat = mysqli_real_escape_string($link, $edit_truck_cat);
            
            mysqli_query($link, "update truck_cat set trk_cat_name = '$edit_truck_cat' where trk_cat_id = '$edit_truck_cat_id'");
        }
        echo "Truck Categories Details Updated";
    }
    elseif(isset($_POST['delete_truck_cat_id']))
    {
        $delete = "delete from truck_cat where trk_cat_id = '".$_POST['delete_truck_cat_id']."'";
        $run_delete = mysqli_query($link, $delete);

        if($run_delete)
        {
            echo "Truck Category Removed";
        }
        else
        {
            echo "Something went wrong";
        }
    }
    else
    {
        echo "Server not responding. Try again";
    }
?>