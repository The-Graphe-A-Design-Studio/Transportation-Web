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

   // Checking for older posts
   $sql = "select * from cust_order";
   $run = mysqli_query($link, $sql);
   while($row = mysqli_fetch_array($run, MYSQLI_ASSOC))
   {
      $date_now = new DateTime(date('Y-m-d H:i:s'));
      $date2    = new DateTime(date_format(date_create($row['or_expire_on']), 'Y-m-d H:i:s'));

      if($date_now > $date2)
      {
         $update = "update cust_order set or_status = 0 where or_id = '".$row['or_id']."'";
         mysqli_query($link, $update);
      }
   }

?>