<?php
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'salon');

    // define('DB_SERVER', 'localhost');
    // define('DB_USERNAME', 'thegrhmw_rohit');
    // define('DB_PASSWORD', '.2019@TheGraphe');
    // define('DB_NAME', 'thegrhmw_aatawala');

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
?>