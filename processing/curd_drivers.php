<?php

    include('../session.php');

    $connect = new PDO("mysql:host=$hostName; dbname=$dbName", $userName, $password);

    if(isset($_POST["action"]))
    {
        $query = "select * from customers where c_status = '1'";

        if(isset($_POST["branch"]))
        {
            if(!empty($_POST["branch"]))
            {
                $query .= " and branch_id = '".$_POST["branch"]."'";
            }
        }

        if(!empty($_POST["start_date"]) && empty($_POST["end_date"]))
        {
            $s_date = date_create($_POST["start_date"]);
            $s_date = date_format($s_date, "Y-m-d");
            
            $e_date = date_create($_POST["end_date"]);
            $e_date = date_format($e_date, "Y-m-d");

            $query .= " and c_date >= '".$s_date."'";
        }
        elseif(empty($_POST["start_date"]) && !empty($_POST["end_date"]))
        {
            $s_date = date_create($_POST["start_date"]);
            $s_date = date_format($s_date, "Y-m-d");
            
            $e_date = date_create($_POST["end_date"]);
            $e_date = date_format($e_date, "Y-m-d");

            $query .= " and c_date <= '".$e_date."'";
        }
        elseif(!empty($_POST["start_date"]) && !empty($_POST["end_date"]))
        {
            $s_date = date_create($_POST["start_date"]);
            $s_date = date_format($s_date, "Y-m-d");
            
            $e_date = date_create($_POST["end_date"]);
            $e_date = date_format($e_date, "Y-m-d");

            $query .= " and c_date >= '".$s_date."' and c_date <= '".$e_date."'";
        }
        else
        {
            $query .= "";
        }

        $query .= " order by c_id desc";

        $statement = $connect->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        $total_row = $statement->rowCount();

        $output = '';
        
        if($total_row > 0)
        {
            $output .=
            '
                <div>
                    <h2 class="section-title">Forms Submitted</h2>
                </div>
                <table>
                    <thead>
                        <th>Date</th>
                        <th>Branch</th>
                        <th>Guest Name</th>
                        <th>Ticket Number</th>
                        <th>Phone</th>
                        <th>Services</th>
                        <th>Visit Again</th>
                        <th>Comment</th>
                        <th>View</th>
                    </thead>
                    <tbody>
            ';

            foreach($result as $row)
            {
                $date = date_create($row['c_date']);
                $date = date_format($date, "d M, Y");

                $output .=
                '
                    <tr>
                        <td data-column="Date">'.$date.'</td>
                ';

                $branch = "select * from locations where l_id = '".$row['branch_id']."'";
                $g_b = mysqli_query($link, $branch);
                $r_b = mysqli_fetch_array($g_b, MYSQLI_ASSOC);

                if($row['c_return'] == 1)
                {
                    $visit = "Definitely";
                }
                elseif($row['c_return'] == 2)
                {
                    $visit = "May be";
                }
                else
                {
                    $visit = "Definitely not";
                }
                
                $output .=
                '       <td data-column="Branch">'.$r_b['l_name'].'</td>
                        <td data-column="Guest Name">'.$row['c_name'].'</td>
                        <td data-column="Ticket Number">'.$row['c_ticket'].'</td>
                        <td data-column="Phone">'.$row['c_phone'].'</td>
                        <td data-column="Services" style="text-align: left">
                ';

                $r = 1;
                $re = "select * from review_form where c_code = '".$row['c_code']."'";
                $g_re = mysqli_query($link, $re);
                while($r_re = mysqli_fetch_array($g_re, MYSQLI_ASSOC))
                {
                    $ser = "select * from services where se_id = '".$r_re['se_id']."'";
                    $g_ser = mysqli_query($link, $ser);
                    $r_ser = mysqli_fetch_array($g_ser, MYSQLI_ASSOC);

                    $st = "select * from staffs where st_id = '".$r_re['st_id']."'";
                    $g_st = mysqli_query($link, $st);
                    $r_st = mysqli_fetch_array($g_st, MYSQLI_ASSOC);

                    $output .=
                    '
                        <b>'.$r.'.</b>&nbsp;&nbsp;'.$r_ser['se_name'].'<br>
                    ';

                    $r++;
                }

                $output .=
                '
                        </td>
                        <td data-column="Visit Again">'.$visit.'</td>
                        <td data-column="Comment">'.$row['c_comment'].'</td>
                        <td data-column="View">
                            <a href="feedback?cust='.$row['c_code'].'&id='.$row['c_id'].'" target="_blank"><i class="fas fa-eye"></i></a>
                        </td>
                    <tr>
                ';
            
            }

            $output .=
            '
                    </tbody>
                </table>
            ';
        }
        else
        {
            $output = 
            '
                No Data Found
            ';
        }
        
        echo $output;

    }

?>