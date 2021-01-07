<?php
    include('../dbcon.php');

    $connect = new PDO("mysql:host=$hostName; dbname=$dbName", $userName, $password);

    if(isset($_POST["bidding_data"]))
    {
        $query = "select * from trucks where trk_id = '".$_POST['dr']."'";
        $run = mysqli_query($link, $query);
        $row = mysqli_fetch_array($run, MYSQLI_ASSOC);

        $output =
            '
                <table>
                    <tr>
                        <th style="border: 2px solid black; padding: 10px">Lat</th>
                        <th style="border: 2px solid black; padding: 10px">Lng</th>
                        <th style="border: 2px solid black; padding: 10px">Time</th>
                    </tr>
                    <tr>
                        <td style="border: 1px solid black; padding: 10px">'.$row['trk_lat'].'</td>
                        <td style="border: 1px solid black; padding: 10px">'.$row['trk_lng'].'</td>
                        <td style="border: 1px solid black; padding: 10px">'.$row['trk_last_updated_at'].'</td>
                    </tr>
                </table>
            ';
        
        echo $output;

    }
?>