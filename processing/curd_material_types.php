<?php
    include('../session.php');

    if(isset($_POST['new_material_type']))
    {
        foreach($_POST['new_material_type'] as $truck_cat)
        {
            if(!empty($truck_cat))
            {
                $truck_cat = mysqli_real_escape_string($link, $truck_cat);

                $rtt = "insert into material_types (mat_name) values ('$truck_cat')";
                mysqli_query($link, $rtt);
            }
        }
        echo "New Material Types Added";
    }
    elseif(isset($_POST['edit_material_type_total']))
    {
        for($i = 1; $i <= $_POST['edit_material_type_total']; $i++)
        {
            $edit_material_type = $_POST['edit_material_type_'.$i];
            $edit_material_type_id = $_POST['edit_material_type_id_'.$i];
            
            $edit_material_type = mysqli_real_escape_string($link, $edit_material_type);
            
            mysqli_query($link, "update material_types set mat_name = '$edit_material_type' where mat_id = '$edit_material_type_id'");
        }
        echo "Material Types Details Updated";
    }
    elseif(isset($_POST['delete_material_type_id']))
    {
        $delete = "delete from material_types where mat_id = '".$_POST['delete_material_type_id']."'";
        $run_delete = mysqli_query($link, $delete);

        if($run_delete)
        {
            echo "Material Types Removed";
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