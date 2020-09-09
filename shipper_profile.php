<?php
    include('session.php');
    include('layout.php');
    include('FCM/notification.php');
    
    $shipper = $_GET['shipper_id'];

    $sql = "select * from customers where cu_id = '$shipper'";
    $g_sql = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($g_sql, MYSQLI_ASSOC);

    $doc = "select * from customer_docs where doc_owner_phone = '".$row['cu_phone']."' and doc_sr_num = 1";
    $r_doc = mysqli_query($link, $doc);
    $pan_card = mysqli_fetch_array($r_doc, MYSQLI_ASSOC);

    $doc1 = "select * from customer_docs where doc_owner_phone = '".$row['cu_phone']."' and doc_sr_num = 2";
    $r_doc1 = mysqli_query($link, $doc1);
    $address_f = mysqli_fetch_array($r_doc1, MYSQLI_ASSOC);

    $doc2 = "select * from customer_docs where doc_owner_phone = '".$row['cu_phone']."' and doc_sr_num = 3";
    $r_doc2 = mysqli_query($link, $doc2);
    $address_b = mysqli_fetch_array($r_doc2, MYSQLI_ASSOC);

    $doc3 = "select * from customer_docs where doc_owner_phone = '".$row['cu_phone']."' and doc_sr_num = 4";
    $r_doc3 = mysqli_query($link, $doc3);
    $selfie = mysqli_fetch_array($r_doc3, MYSQLI_ASSOC);

    $doc4 = "select * from customer_docs where doc_owner_phone = '".$row['cu_phone']."' and doc_sr_num = 5";
    $r_doc4 = mysqli_query($link, $doc4);
    $com_name = mysqli_fetch_array($r_doc4, MYSQLI_ASSOC);

    $doc5 = "select * from customer_docs where doc_owner_phone = '".$row['cu_phone']."' and doc_sr_num = 6";
    $r_doc5 = mysqli_query($link, $doc5);
    $office_address = mysqli_fetch_array($r_doc5, MYSQLI_ASSOC);

    if($row['cu_verified'] == 0 && $row['cu_account_on'] == 0)
    {
        if($pan_card['doc_verified'] == 1 && $address_f['doc_verified'] == 1 && $address_b['doc_verified'] == 1 && $selfie['doc_verified'] == 1 && $com_name['doc_verified'] == 1 && 
        $office_address['doc_verified'] == 1)
        {
            date_default_timezone_set("Asia/Kolkata");
            $date = date('Y-m-d H:i:s');

            $trial_date = date('Y-m-d H:i:s', strtotime($date. ' + 7 days'));

            $update = "update customers set cu_verified = 1, cu_account_on = 1, cu_trial_expire_date = '$trial_date' where cu_id = '$shipper'";
            $done = mysqli_query($link, $update);
            
            if($done)
            {
                $device_id = $row['cu_token'];
                $title = "Document Verification";
                $message = "Your all documents are verified and 7 days trial period started from now";

                $sent = push_notification_android($device_id, $title, $message);
            }
        }
        else
        {
            $device_id = $row['cu_token'];
            $title = "Document Verification";
            $message = "Admin is verifying your documents";

            $sent = push_notification_android($device_id, $title, $message);
        }
    }

    if($row['cu_verified'] == 1)
    {
        if($pan_card['doc_verified'] != 1 || $address_f['doc_verified'] != 1 || $address_b['doc_verified'] != 1 || $selfie['doc_verified'] != 1 || $com_name['doc_verified'] != 1 
            || $office_address['doc_verified'] != 1)
        {
            $update = "update customers set cu_verified = 0 where cu_id = '$shipper'";
            $done = mysqli_query($link, $update);
            
            if($done)
            {
                $device_id = $row['cu_token'];
                $title = "Document Verification";
                $message = "Your some documents are rejected by admin";

                $sent = push_notification_android($device_id, $title, $message);
            }
        }
    }

    if($row['cu_verified'] == 0)
    {
        if($pan_card['doc_verified'] == 1 && $address_f['doc_verified'] == 1 && $address_b['doc_verified'] == 1 && $selfie['doc_verified'] == 1 && $com_name['doc_verified'] == 1 && 
        $office_address['doc_verified'] == 1)
        {
            $update = "update customers set cu_verified = 1 where cu_id = '$shipper'";
            $done = mysqli_query($link, $update);
            
            if($done)
            {
                $device_id = $row['cu_token'];
                $title = "Document Verification";
                $message = "Your all documents are verifeid";

                $sent = push_notification_android($device_id, $title, $message);
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Shipper Profile | Truck Wale</title>
    <link rel="stylesheet" href="assets/css/table.css">
    <?php echo $head_tags; ?>
    <style>
        td i
        {
            font-size: 1.2rem !important;
        }

        .card .card-body p
        {
            text-align: center !important;
        }

        .card .card-body p img
        {
            max-height: 80px;
        }

        .card-header, .card-footer
        {
            min-height: 0 !important;
            padding: 1vh 2vh !important;
        }

        .card-body
        {
            padding: 1vh 0 0 0 !important;
        }
    </style>
</head>
<body>
    <div id="app">
        <div class="main-wrapper">
        <?php
            echo $nav_bar;
            echo $side_bar;
        ?>

        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>Shipper Profile ID <?php echo $row['cu_id']; ?></h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="dashboard">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="shippers">Shippers</a></div>
                        <div class="breadcrumb-item">Shipper Profile</div>
                    </div>
                </div>

                <div class="section-body">
                    <div class="row">
                        <div class="col-12">
                            <?php
                                if($row['cu_account_on'] == 1)
                                {
                                    $date_now = new DateTime(date('Y-m-d H:i:s'));
                                    $date2    = new DateTime(date_format(date_create($row['cu_trial_expire_date']), 'Y-m-d H:i:s'));

                                    if($date_now > $date2)
                                    {
                                        $expire = date_format(date_create($row['cu_trial_expire_date']), 'd M, Y h:i A');
                                        $status = "Trial Period Expired";
                                        $plan = "Trial";
                                        $t_left = "0";
                                    }
                                    else
                                    {
                                        $expire = date_format(date_create($row['cu_trial_expire_date']), 'd M, Y h:i A');
                                        $status = "On Trial Period";
                                        $plan = "Trial";

                                        function dateDiffInDays($date11, $date22)  
                                        { 
                                            // Calculating the difference in timestamps 
                                            $diff = strtotime($date22) - strtotime($date11); 
                                            
                                            // 1 day = 24 hours 
                                            // 24 * 60 * 60 = 86400 seconds 
                                            return abs(round($diff / 86400)); 
                                        }

                                        $t_left = dateDiffInDays(date('Y-m-d H:i:s'), $row['cu_trial_expire_date']);
                                    }
                                    $table =
                                    '
                                        <table>
                                            <thead>
                                                <th>Plan</th>
                                                <th>Registered On</th>
                                                <th>Expire Date</th>
                                                <th>Days Left</th>
                                                <th>Status</th>
                                            </thead>
                                            <tbody>
                                                <td data-column="Plan">'.$plan.'</td>
                                                <td data-column="Registered On">'.date_format(date_create($row['cu_registered']), 'd M, Y h:i A').'</td>
                                                <td data-column="Expire Date">'.$expire.'</td>
                                                <td data-column="Days Left">'.$t_left.'</td>
                                                <td data-column="Status">'.$status.'</td>
                                            </tbody>
                                        </table>
                                    ';
                                }
                                elseif($row['cu_account_on'] == 2)
                                {
                                    $date_now = new DateTime(date('Y-m-d H:i:s'));
                                    $date2    = new DateTime(date_format(date_create($row['cu_subscription_expire_date']), 'Y-m-d H:i:s'));

                                    if($date_now > $date2)
                                    {
                                        $expire = date_format(date_create($row['cu_subscription_expire_date']), 'd M, Y h:i A');
                                        $status = "Subscription Period Expired";
                                        $plan = "Subscription";
                                        $t_left = "0";
                                    }
                                    else
                                    {
                                        $expire = date_format(date_create($row['cu_subscription_expire_date']), 'd M, Y h:i A');
                                        $status = "On Subscription Period";
                                        $plan = "Subscription";

                                        function dateDiffInDays($date11, $date22)  
                                        { 
                                            // Calculating the difference in timestamps 
                                            $diff = strtotime($date22) - strtotime($date11); 
                                            
                                            // 1 day = 24 hours 
                                            // 24 * 60 * 60 = 86400 seconds 
                                            return abs(round($diff / 86400)); 
                                        }

                                        $t_left = dateDiffInDays(date('Y-m-d H:i:s'), $row['cu_subscription_expire_date']);
                                    }
                                    $table =
                                    '
                                        <table>
                                            <thead>
                                                <th>Plan</th>
                                                <th>Registered On</th>
                                                <th>Order ID</th>
                                                <th>Start Date</th>
                                                <th>Expire Date</th>
                                                <th>Days Left</th>
                                                <th>Status</th>
                                            </thead>
                                            <tbody>
                                                <td data-column="Plan">'.$plan.'</td>
                                                <td data-column="Registered On">'.date_format(date_create($row['cu_registered']), 'd M, Y h:i A').'</td>
                                                <td data-column="Order ID">'.$row['cu_subscription_order_id'].'</td>
                                                <td data-column="Start Date">'.date_format(date_create($row['cu_subscription_start_date']), 'd M, Y h:i A').'</td>
                                                <td data-column="Expire Date">'.$expire.'</td>
                                                <td data-column="Days Left">'.$t_left.'</td>
                                                <td data-column="Status">'.$status.'</td>
                                            </tbody>
                                        </table>
                                    ';
                                }
                                else
                                {
                                    $expire = "Nil";
                                    $t_left = "Nil";
                                    $status = "Nil";
                                }
                            ?>
                            <div class="card">
                                <div class="card-body" style="padding: 1vh !important">
                                    <?php echo $table; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-8 col-lg-8" style="display: flex; flex-direction: column">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="buttons text-center">
                                                <a href="#" class="btn btn-primary btn-lg">Loads</a>
                                                <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#history">Subscription History</button>
                        
                                                <!-- Modal -->
                                                <div class="mymodal modal fade" id="history" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel" aria-hidden="true" style="background: rgba(0, 0, 0, 0.78) none repeat scroll 0% 0%">
                                                    <div class="modal-dialog" role="document" style="max-width: 850px; margin-top: 10vh">
                                                        <div class="section">
                                                            <div class="section-header">
                                                                <h1>Subscription History</h1>
                                                            </div>
                                                        </div>
                                                        <table style="background: #fff">
                                                            <thead>
                                                                <th>ID</th>
                                                                <th>Order ID</th>
                                                                <th>Payment ID</th>
                                                                <th>Amount</th>
                                                                <th>Duration</th>
                                                                <th>Start On</th>
                                                                <th>Expire On</th>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                    $sub = "select * from subscribed_users where subs_user_id = '".$row['cu_id']."' order by subs_id desc";
                                                                    $run_sub = mysqli_query($link, $sub);
                                                                    while($row_sub = mysqli_fetch_array($run_sub, MYSQLI_ASSOC))
                                                                    {
                                                                ?>
                                                                    <tr>
                                                                        <td data-column="ID"><?php echo $row_sub['subs_id']; ?></td>
                                                                        <td data-column="Order ID"><?php echo $row_sub['razorpay_order_id']; ?></td>
                                                                        <td data-column="Payment ID"><?php echo $row_sub['razorpay_payment_id']; ?></td>
                                                                        <td data-column="Amount"><?php echo $row_sub['subs_amount']; ?></td>
                                                                        <td data-column="Duration"><?php echo $row_sub['subs_duration']; ?> Months</td>
                                                                        <td data-column="Start On"><?php echo date_format(date_create($row_sub['payment_datetime']), 'd M, Y h:i A'); ?></td>
                                                                        <td data-column="Expire On"><?php echo date_format(date_create($row_sub['expire_datetime']), 'd M, Y h:i A'); ?></td>
                                                                    </tr>
                                                                <?php
                                                                    }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6 col-lg-6">
                                    <div class="card card-success">
                                        <div class="card-header">
                                            <h4>Phone Number</h4>
                                        </div>
                                        <div class="card-body text-center">
                                            <p><h5><?php echo '+'.$row['cu_phone_code'].' '.$row['cu_phone']; ?></h5></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-6">
                                    <div class="card card-success">
                                        <div class="card-header">
                                            <h4>Company Name</h4>
                                        </div>
                                        <div class="card-body text-center">
                                            <p><h5><?php echo $com_name['doc_location']; ?></h5></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                   
                        <div class="col-12 col-md-4 col-lg-4">
                            <?php
                                if($selfie['doc_verified'] == 0)
                                {
                                    $s_selfie = "card-warning";
                                    $b_selfie = '<button type="submit" class="btn btn-icon btn-success" title="Accept"><i class="fas fa-check-double"></i></button>';
                                }
                                else
                                {
                                    $s_selfie = "card-success";
                                    $b_selfie = '<button type="submit" class="btn btn-icon btn-danger" title="Reject"><i class="far fa-times-circle"></i></i></button>';
                                }
                            ?>
                            <div class="card <?php echo $s_selfie; ?>">
                                <div class="card-header">
                                    <h4>Selfie</h4>
                                </div>
                                <div class="card-body">
                                    <p>
                                        <img alt="shipper_selfie_<?php echo $row['cu_phone']; ?>" src="<?php echo $selfie['doc_location']; ?>">
                                    </p>
                                </div>
                                <div class="card-footer text-center">
                                    <div class="buttons" style="display: inline-flex">
                                        <form class="accept-reject">
                                            <input type="text" name="doc_id" value="<?php echo $selfie['doc_id']; ?>" hidden>
                                            <input type="text" name="doc_status" value="<?php echo $selfie['doc_verified']; ?>" hidden>
                                            <?php echo $b_selfie; ?>
                                        </form>
                                        <button class="btn btn-icon btn-info" data-toggle="modal" title="View" data-target="#selfie"><i class="fas fa-eye"></i></button>
                                        <!-- Modal -->
                                        <div class="mymodal modal fade" id="selfie" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background: rgba(0, 0, 0, 0.78) none repeat scroll 0% 0%">
                                            <div class="modal-dialog" role="document">
                                                <img src="<?php echo $selfie['doc_location']; ?>" style="max-width: 100%" alt="shipper_selfie_<?php echo $row['cu_phone']; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <?php
                                if($pan_card['doc_verified'] == 0)
                                {
                                    $s_pan_card = "card-warning";
                                    $b_pan_card = '<button type="submit" class="btn btn-icon btn-success" title="Accept"><i class="fas fa-check-double"></i></button>';
                                }
                                else
                                {
                                    $s_pan_card = "card-success";
                                    $b_pan_card = '<button type="submit" class="btn btn-icon btn-danger" title="Reject"><i class="far fa-times-circle"></i></i></button>';
                                }
                            ?>
                            <div class="card <?php echo $s_pan_card; ?>">
                                <div class="card-header">
                                    <h4>PAN Card</h4>
                                </div>
                                <div class="card-body">
                                    <p>
                                        <img alt="shipper_pan_card_<?php echo $row['cu_phone']; ?>" src="<?php echo $pan_card['doc_location']; ?>">
                                    </p>
                                </div>
                                <div class="card-footer text-center">
                                    <div class="buttons" style="display: inline-flex">
                                        <form class="accept-reject">
                                            <input type="text" name="doc_id" value="<?php echo $pan_card['doc_id']; ?>" hidden>
                                            <input type="text" name="doc_status" value="<?php echo $pan_card['doc_verified']; ?>" hidden>
                                            <?php echo $b_pan_card; ?>
                                        </form>
                                        <button class="btn btn-icon btn-info" data-toggle="modal" title="View" data-target="#pan"><i class="fas fa-eye"></i></button>
                                        <!-- Modal -->
                                        <div class="mymodal modal fade" id="pan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background: rgba(0, 0, 0, 0.78) none repeat scroll 0% 0%">
                                            <div class="modal-dialog" role="document">
                                                <img src="<?php echo $pan_card['doc_location']; ?>" style="max-width: 100%" alt="shipper_pan_card_<?php echo $row['cu_phone']; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <?php
                                if($address_f['doc_verified'] == 0)
                                {
                                    $s_address_f = "card-warning";
                                    $b_address_f = '<button type="submit" class="btn btn-icon btn-success" title="Accept"><i class="fas fa-check-double"></i></button>';
                                }
                                else
                                {
                                    $s_address_f = "card-success";
                                    $b_address_f = '<button type="submit" class="btn btn-icon btn-danger" title="Reject"><i class="far fa-times-circle"></i></i></button>';
                                }
                            ?>
                            <div class="card <?php echo $s_address_f; ?>">
                                <div class="card-header">
                                    <h4>Address Front</h4>
                                </div>
                                <div class="card-body">
                                    <p>
                                        <img alt="shipper_address_front_<?php echo $row['cu_phone']; ?>" src="<?php echo $address_f['doc_location']; ?>">
                                    </p>
                                </div>
                                <div class="card-footer text-center">
                                    <div class="buttons" style="display: inline-flex">
                                        <form class="accept-reject">
                                            <input type="text" name="doc_id" value="<?php echo $address_f['doc_id']; ?>" hidden>
                                            <input type="text" name="doc_status" value="<?php echo $address_f['doc_verified']; ?>" hidden>
                                            <?php echo $b_address_f; ?>
                                        </form>
                                        <button class="btn btn-icon btn-info" data-toggle="modal" title="View" data-target="#address_front"><i class="fas fa-eye"></i></button>
                                        <!-- Modal -->
                                        <div class="mymodal modal fade" id="address_front" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background: rgba(0, 0, 0, 0.78) none repeat scroll 0% 0%">
                                            <div class="modal-dialog" role="document">
                                                <img src="<?php echo $address_f['doc_location']; ?>" style="max-width: 100%" alt="shipper_address_front_<?php echo $row['cu_phone']; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <?php
                                if($address_b['doc_verified'] == 0)
                                {
                                    $s_address_b = "card-warning";
                                    $b_address_b = '<button type="submit" class="btn btn-icon btn-success" title="Accept"><i class="fas fa-check-double"></i></button>';
                                }
                                else
                                {
                                    $s_address_b = "card-success";
                                    $b_address_b = '<button type="submit" class="btn btn-icon btn-danger" title="Reject"><i class="far fa-times-circle"></i></i></button>';
                                }
                            ?>
                            <div class="card <?php echo $s_address_b; ?>">
                                <div class="card-header">
                                    <h4>Address Back</h4>
                                </div>
                                <div class="card-body">
                                    <p>
                                        <img alt="shipper_address_back_<?php echo $row['cu_phone']; ?>" src="<?php echo $address_b['doc_location']; ?>">
                                    </p>
                                </div>
                                <div class="card-footer text-center">
                                    <div class="buttons" style="display: inline-flex">
                                        <form class="accept-reject">
                                            <input type="text" name="doc_id" value="<?php echo $address_b['doc_id']; ?>" hidden>
                                            <input type="text" name="doc_status" value="<?php echo $address_b['doc_verified']; ?>" hidden>
                                            <?php echo $b_address_b; ?>
                                        </form>
                                        <button class="btn btn-icon btn-info" data-toggle="modal" title="View" data-target="#address_back"><i class="fas fa-eye"></i></button>
                                        <!-- Modal -->
                                        <div class="mymodal modal fade" id="address_back" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background: rgba(0, 0, 0, 0.78) none repeat scroll 0% 0%">
                                            <div class="modal-dialog" role="document">
                                                <img src="<?php echo $address_b['doc_location']; ?>" style="max-width: 100%" alt="shipper_address_back_<?php echo $row['cu_phone']; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <?php
                                if($office_address['doc_verified'] == 0)
                                {
                                    $s_office_address = "card-warning";
                                    $b_office_address = '<button type="submit" class="btn btn-icon btn-success" title="Accept"><i class="fas fa-check-double"></i></button>';
                                }
                                else
                                {
                                    $s_office_address = "card-success";
                                    $b_office_address = '<button type="submit" class="btn btn-icon btn-danger" title="Reject"><i class="far fa-times-circle"></i></i></button>';
                                }
                            ?>
                            <div class="card <?php echo $s_office_address; ?>">
                                <div class="card-header">
                                    <h4>Office Address</h4>
                                </div>
                                <div class="card-body">
                                    <p>
                                        <img alt="shipper_office_address_<?php echo $row['cu_phone']; ?>" src="<?php echo $office_address['doc_location']; ?>">
                                    </p>
                                </div>
                                <div class="card-footer text-center">
                                    <div class="buttons" style="display: inline-flex">
                                        <form class="accept-reject">
                                            <input type="text" name="doc_id" value="<?php echo $office_address['doc_id']; ?>" hidden>
                                            <input type="text" name="doc_status" value="<?php echo $office_address['doc_verified']; ?>" hidden>
                                            <?php echo $b_office_address; ?>
                                        </form>
                                        <button class="btn btn-icon btn-info" data-toggle="modal" title="View" data-target="#office_address"><i class="fas fa-eye"></i></button>
                                        <!-- Modal -->
                                        <div class="mymodal modal fade" id="office_address" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background: rgba(0, 0, 0, 0.78) none repeat scroll 0% 0%">
                                            <div class="modal-dialog" role="document">
                                                <img src="<?php echo $office_address['doc_location']; ?>" style="max-width: 100%" alt="shipper_office_address_<?php echo $row['cu_phone']; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
        </div>

        <?php echo $footer; ?>
        </div>
    </div>

    <?php echo $script_tags; ?>

    <script type="text/javascript">
        $(document).ready(function(){
            $(".shippers").addClass("active");
        });
    
        $(".accept-reject").submit(function(e)
        {
            var form_data = $(this).serialize();
            // alert(form_data);
            var button_content = $(this).find("button[type=submit]");
            $.ajax({
                url: "processing/curd_shippers.php",
                data: form_data,
                type: "POST",
                success: function(data)
                {
                    alert(data);
                    if(data === "Document verified" || data === "Set to not verified")
                    {
                        location.href = "shipper_profile?shipper_id=<?php echo $shipper; ?>";
                    }
                }
            });
            e.preventDefault();
        });
    </script>
</body>
</html>