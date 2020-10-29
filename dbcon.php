<?php

    date_default_timezone_set("Asia/Kolkata");

    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'transporter');

    // define('DB_SERVER', 'localhost');
    // define('DB_USERNAME', 'thegrhmw_rohit');
    // define('DB_PASSWORD', '.2019@TheGraphe');
    // define('DB_NAME', 'thegrhmw_transpoter');

    $hostName = DB_SERVER;
    $dbName = DB_NAME;
    $userName = DB_USERNAME;
    $password = DB_PASSWORD;

    $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
    if($link === false){
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }


    class DBController
    {
        private $conn;
        
        function __construct() {
            $this->conn = $this->connectDB();
        }
        
        function connectDB() {
            $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
            return $conn;
        }
        
        function runQuery($query) {
            $result = mysqli_query($this->conn,$query);
            while($row=mysqli_fetch_assoc($result)) {
                $resultset[] = $row;
            }		
            if(!empty($resultset))
                return $resultset;
        }
        
        function numRows($query) {
            $result  = mysqli_query($this->conn,$query);
            $rowcount = mysqli_num_rows($result);
            return $rowcount;	
        }
    }


    // Checking for older loads
    $db_sql = "select * from cust_order where or_status = 1 or or_status = 2";
    $db_run = mysqli_query($link, $db_sql);
    while($db_row = mysqli_fetch_array($db_run, MYSQLI_ASSOC))
    {
        $db_date_now = new DateTime(date('Y-m-d H:i:s'));
        $db_date2    = new DateTime(date_format(date_create($db_row['or_expire_on']), 'Y-m-d H:i:s'));

        if($db_date_now > $db_date2)
        {
            $db_update = "update cust_order set or_status = 0 where or_id = '".$db_row['or_id']."'";
            mysqli_query($link, $db_update);
        }
    }

    // Checking for delivery started or not
    $db_sql1 = "select * from deliveries where del_status = 0";
    $db_run1 = mysqli_query($link, $db_sql1);
    $db_count1 = mysqli_num_rows($db_run1);
    if($db_count1 != 0)
    {
        $db_row1 = mysqli_fetch_array($db_run1, MYSQLI_ASSOC);

        $db_i = 0;
        $db_sql2 = "select * from delivery_trucks where del_id = '".$db_row1['del_id']."'";
        $db_run2 = mysqli_query($link, $db_sql2);
        $db_count2 = mysqli_num_rows($db_run2);
        while($db_row2 = mysqli_fetch_array($db_run2, MYSQLI_ASSOC))
        {
            if($db_row2['status'] == 1)
            {
                $db_i++;
            }
        }

        if($db_count2 == $db_i)
        {
            mysqli_query($link, "update deliveries set del_status = 1 where del_id = '".$db_row1['del_id']."'");
        }
    }

    // Checking for delivery completed or not
    $db_sql11 = "select * from deliveries where del_status = 2";
    $db_run11 = mysqli_query($link, $db_sql11);
    $db_count11 = mysqli_num_rows($db_run11);
    if($db_count11 != 0)
    {
        while($db_row11 = mysqli_fetch_array($db_run11, MYSQLI_ASSOC))
        {

            if($db_row11['del_status'] == 2)
            {
                mysqli_query($link, "update cust_order set or_status = 5 where or_id = '".$db_row11['or_id']."'");
            }
            
            // Checking for old bids if load is set to completed
            $db_sql5 = "select * from cust_order where or_status = 5";
            $db_run5 = mysqli_query($link, $db_sql5);
            while($db_row5 = mysqli_fetch_array($db_run5, MYSQLI_ASSOC))
            {
                mysqli_query($link, "delete from bidding where load_id = '".$db_row5['or_id']."'");
            }
        }
    }

    // Checking shipper trial or subscription ended or not then updating shipper from current account to free account
    $db_shipper_sql = "select * from customers where cu_verified = 1";
    $db_shipper_run = mysqli_query($link, $db_shipper_sql);
    while($db_shipper_row = mysqli_fetch_array($db_shipper_run, MYSQLI_ASSOC))
    {
        if($db_shipper_row['cu_account_on'] == 1)
        {
            $db_date_now = new DateTime(date('Y-m-d H:i:s'));
            $db_date2    = new DateTime(date_format(date_create($db_shipper_row['cu_trial_expire_date']), 'Y-m-d H:i:s'));

            if($db_date_now > $db_date2)
            {
                mysqli_query($link, "update customers set cu_account_on = 3 where cu_id = '".$db_shipper_row['cu_id']."'");
            }
        }

        if($db_shipper_row['cu_account_on'] == 2)
        {
            $db_date_now = new DateTime(date('Y-m-d H:i:s'));
            $db_date2    = new DateTime(date_format(date_create($db_shipper_row['cu_subscription_expire_date']), 'Y-m-d H:i:s'));

            if($db_date_now > $db_date2)
            {
                mysqli_query($link, "update customers set cu_account_on = 3 where cu_id = '".$db_shipper_row['cu_id']."'");
            }
        }        
    }

    // Checking truck owner trial or subscription ended or not then updating truck owner from current account to 0
    $db_owner_sql = "select * from truck_owners where to_verified = 1";
    $db_owner_run = mysqli_query($link, $db_owner_sql);
    while($db_owner_row = mysqli_fetch_array($db_owner_run, MYSQLI_ASSOC))
    {
        if($db_owner_row['to_account_on'] == 1)
        {
            $db_date_now = new DateTime(date('Y-m-d H:i:s'));
            $db_date2    = new DateTime(date_format(date_create($db_owner_row['to_trial_expire_date']), 'Y-m-d H:i:s'));

            if($db_date_now > $db_date2)
            {
                mysqli_query($link, "update truck_owners set to_account_on = 0 where to_id = '".$db_owner_row['to_id']."'");
            }
        }

        if($db_owner_row['to_account_on'] == 2)
        {
            $db_date_now = new DateTime(date('Y-m-d H:i:s'));
            $db_date2    = new DateTime(date_format(date_create($db_owner_row['to_subscription_expire_date']), 'Y-m-d H:i:s'));

            if($db_date_now > $db_date2)
            {
                mysqli_query($link, "update truck_owners set to_account_on = 0 where to_id = '".$db_owner_row['to_id']."'");
            }
        }        
    }

    // Checking for older notifications
    $db_not = "select * from notifications where no_status = 1";
    $db_not_run = mysqli_query($link, $db_not);
    while($db_not_row = mysqli_fetch_array($db_not_run, MYSQLI_ASSOC))
    {
        // Calculating the difference in timestamps 
        $diff = strtotime(date('Y-m-d H:i:s')) - strtotime($db_not_row['no_date_time']); 
                    
        // 1 day = 24 hours 
        // 24 * 60 * 60 = 86400 seconds 
        $t_left = abs(round($diff / 86400));

        if($t_left > 5)
        {
            mysqli_query($link, "delete from notifications where no_id = '".$db_not_row['no_id']."'");
        }
    }
?>