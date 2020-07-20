<?php
    include('../session.php');

    if(isset($_POST['name']) && isset($_POST['username']))
    {
        mysqli_query($link, "update admin set admin_name = '".$_POST['name']."', admin_username = '".$_POST['username']."'");

        echo "Admin details updated";
    }
    elseif(isset($_POST['old_pass']) && isset($_POST['pass']) && isset($_POST['cnf_pass']))
    {
        $old = md5($_POST['old_pass']);
        $new = md5($_POST['pass']);
        $c_new = md5($_POST['cnf_pass']);
         
        $login_sql = "SELECT * FROM admin WHERE admin_pass = '$old'";
        $result = mysqli_query($link, $login_sql);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $count = mysqli_num_rows($result);
         
        if($count == 1)
        {
            if($new === $c_new)
            {
                $set = "update admin set admin_pass = '$new'";
                mysqli_query($link, $set);

                echo "Password updated";
            }
            else
            {
                echo "New password and Confirm password must be same";
            }
        }
        else
        {
            echo "Worng current password";
        }
    }
    else
    {
        echo "Server not responding. Try again";
    }
?>