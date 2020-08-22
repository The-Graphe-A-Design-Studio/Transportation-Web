<?php
    include('../session.php');

    if(isset($_POST['newcatName']))
    {
        mysqli_query($link, "insert into truck_cat (trk_cat_name) values ('".$_POST['newcatName']."')");

        echo "New Category created";
    }
    elseif(isset($_POST['cat_delete_id']))
    {
        $ser = mysqli_query($link, "delete from truck_cat_type where ty_cat = '".$_POST['cat_delete_id']."'");
        if($ser)
        {
            $cat = mysqli_query($link, "delete from truck_cat where trk_cat_id = '".$_POST['cat_delete_id']."'");

            if($cat)
            {
                echo "Category Deleted";
            }
            else
            {
                echo "Something went wrong";
            }
        }
        else
        {
            echo "Something went wrong";
        }
    }
    elseif(isset($_POST['categoryEditName']) && isset($_POST['categoryEditId']))
    {
        $name = mysqli_real_escape_string($link, $_POST['categoryEditName']);
        $edi_ca = mysqli_query($link, "update truck_cat set trk_cat_name = '$name' where trk_cat_id = '".$_POST['categoryEditId']."'");

        if($edi_ca)
        {
            echo "Category name updated";
        }
        else
        {
            echo "Something went wrong";
        }
    }
    elseif(isset($_POST['category_id']))
    {
        foreach($_POST['new_service'] as $service)
        {
            if(!empty($service))
            {
                $service = mysqli_real_escape_string($link, $service);

                $rtt = "insert into truck_cat_type (ty_cat, ty_name) values ('".$_POST['category_id']."', '$service')";
                mysqli_query($link, $rtt);
            }
        }
        echo "New services updated";
    }
    elseif(isset($_POST['edit_category_id']) && isset($_POST['edit_category_total']))
    {
        for($i = 1; $i <= $_POST['edit_category_total']; $i++)
        {
            $edit_service = $_POST['edit_service_'.$i];
            $edit_service_id = $_POST['edit_service_id_'.$i];
            
            $edit_service = mysqli_real_escape_string($link, $edit_service);
            
            mysqli_query($link, "update truck_cat_type set ty_name = '$edit_service' where ty_id = '$edit_service_id'");
        }
        echo "Services updated";
    }
    elseif(isset($_POST['delete_service_id']))
    {
        $delete = "delete from truck_cat_type where ty_id = '".$_POST['delete_service_id']."'";
        $run_delete = mysqli_query($link, $delete);

        if($run_delete)
        {
            echo "Service Deleted";
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