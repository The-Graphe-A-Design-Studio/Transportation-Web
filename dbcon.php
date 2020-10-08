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
    $sql = "select * from cust_order where or_status <> 4 and or_status <> 5";
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

    // Checking for delivery started or not
    $sql1 = "select * from deliveries where del_status = 0";
    $run1 = mysqli_query($link, $sql1);
    $count1 = mysqli_num_rows($run1);
    if($count1 != 0)
    {
        $row1 = mysqli_fetch_array($run1, MYSQLI_ASSOC);

        $i = 0;
        $sql2 = "select * from delivery_trucks where del_id = '".$row1['del_id']."'";
        $run2 = mysqli_query($link, $sql2);
        $count2 = mysqli_num_rows($run2);
        while($row2 = mysqli_fetch_array($run2, MYSQLI_ASSOC))
        {
            if($row2['status'] == 1)
            {
                $i++;
            }
        }

        if($count2 == $i)
        {
            mysqli_query($link, "update deliveries set del_status = 1 where del_id = '".$row1['del_id']."'");
        }
    }

    // Checking for delivery completed or not
    $sql11 = "select * from deliveries where del_status = 2";
    $run11 = mysqli_query($link, $sql11);
    $count11 = mysqli_num_rows($run11);
    if($count11 != 0)
    {
        if($row11['del_status'] == 2)
        {
            mysqli_query($link, "update cust_order set or_status = 5 where or_id = '".$row11['or_id']."'");
        }


        // Checking for old bids if load is set to completed
        $sql5 = "select * from cust_order where or_status = 5";
        $run5 = mysqli_query($link, $sql5);
        while($row5 = mysqli_fetch_array($run5, MYSQLI_ASSOC))
        {
            mysqli_query($link, "delete from bidding where load_id = '".$row5['or_id']."'");
        }   
    }
?>