<?php

    include('../dbcon.php');

    if(isset($_POST['set']))
    {
        $sql = "select * from admin where admin_id = 1";
        $run = mysqli_query($link, $sql);
        $row = mysqli_fetch_array($run, MYSQLI_ASSOC);

        if($row['admin_toggle'] == 1)
        {
            mysqli_query($link, "update admin set admin_toggle = 0 where admin_id = 1");
        }
        else
        {
            mysqli_query($link, "update admin set admin_toggle = 1 where admin_id = 1");
        }
    }

?>