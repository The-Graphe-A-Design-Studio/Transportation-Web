<?php
   session_start();
   include('dbcon.php');

   if(!isset($_SESSION['admin_username']))
   {
	    echo "<script type='text/javascript'>alert('First Login');</script>";
	    echo "<script>location.href='login'</script>";
       die();
   }
   
   $user_check = $_SESSION['admin_username'];
   
   $ses_sql = mysqli_query($link, "select * from admin where admin_username = '$user_check' ");
   
   $rows = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
   
   $admin_username_session = $rows['admin_username'];
   $admin_id_session = $rows['admin_id'];
   $admin_name_session = $rows['admin_name'];

?>