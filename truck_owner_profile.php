<?php
    include('session.php');
    include('layout.php');
    include('FCM/notification.php');

    $owner = $_GET['owner_id'];

    $sql = "select * from truck_owners where to_id = '$owner'";
    $g_sql = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($g_sql, MYSQLI_ASSOC);

    $doc = "select * from truck_owner_docs where to_doc_owner_phone = '".$row['to_phone']."'";
    $doc_run = mysqli_query($link, $doc);
    $doc_row = mysqli_fetch_array($doc_run, MYSQLI_ASSOC);

    if($row['to_verified'] == 0 && $row['to_account_on'] == 0)
    {
        if($doc_row['to_doc_verified'] == 1)
        {
            $date = date('Y-m-d H:i:s');

            $trial_date = date('Y-m-d H:i:s', strtotime($date. ' + 15 days'));

            $update = "update truck_owners set to_verified = 1, to_account_on = 1,to_trial_start_date = '$date', to_trial_expire_date = '$trial_date' where to_id = '$owner'";
            $done = mysqli_query($link, $update);
            
            if($done)
            {
                $device_id = $row['to_token'];
                $title = "Document Verification";
                $message = "Your all documents are verified and 15 days trial period started from now";

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
    <title>Truck Owner Profile | Truck Wale</title>
    <link rel="stylesheet" href="assets/css/table.css">
    <?php echo $head_tags; ?>
    <style>
        td i{font-size: 1rem !important;}
        .info{padding: 1vh;}
        .truck-eye{font-size: 1em !important;}
        .card .card-body p img{max-height: 150px;}
    </style>
</head>
<body <?php echo $body; ?>>
    <div id="app">
        <div class="main-wrapper">
        <?php
            echo $nav_bar;
            echo $side_bar;
        ?>

        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>Truck Owner Profile <?php echo $owner; ?></h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="dashboard">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="truck_owners">Truck Owners</a></div>
                        <div class="breadcrumb-item">Truck Owner Profile</div>
                    </div>
                </div>

                <div class="section-body">
                    <div class="row mt-sm-4">
                        <div class="col-12 col-md-12 col-lg-7">
                            <div class="card profile-widget">
                                <div class="profile-widget-header">
                                <img alt="image" src="assets/img/avatar/avatar-2.png" class="rounded-circle profile-widget-picture">
                                <div class="profile-widget-items">
                                    <div class="profile-widget-item">
                                        <div class="profile-widget-item-label">Name</div>
                                        <div class="profile-widget-item-value"><?php echo $row['to_name']; ?></div>
                                    </div>
                                    <div class="profile-widget-item">
                                        <div class="profile-widget-item-label">Phone</div>
                                        <div class="profile-widget-item-value">+<?php echo $row['to_phone_code']." ".$row['to_phone']; ?></div>
                                    </div>
                                </div>
                                </div>
                                <div class="profile-widget-description">
                                    <div class="row">
                                        <?php
                                            if($row['to_account_on'] == 1)
                                            {
                                                $date_now = new DateTime(date('Y-m-d H:i:s'));
                                                $date2    = new DateTime(date_format(date_create($row['to_trial_expire_date']), 'Y-m-d H:i:s'));

                                                $start = date_format(date_create($row['to_trial_start_date']), 'd M, Y h:i A');
            
                                                if($date_now > $date2)
                                                {
                                                    $expire = date_format(date_create($row['to_trial_expire_date']), 'd M, Y h:i A');
                                                    $status = "Trial Period Expired";
                                                    $plan = "Trial";
                                                    $t_left = "0";
                                                }
                                                else
                                                {
                                                    $expire = date_format(date_create($row['to_trial_expire_date']), 'd M, Y h:i A');
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
            
                                                    $t_left = dateDiffInDays(date('Y-m-d H:i:s'), $row['to_trial_expire_date']);
                                                }
                                            }
                                            elseif($row['to_account_on'] == 2)
                                            {
                                                $date_now = new DateTime(date('Y-m-d H:i:s'));
                                                $date2    = new DateTime(date_format(date_create($row['to_subscription_expire_date']), 'Y-m-d H:i:s'));

                                                $start = date_format(date_create($row['to_subscription_start_date']), 'd M, Y h:i A');
            
                                                if($date_now > $date2)
                                                {
                                                    $expire = date_format(date_create($row['to_subscription_expire_date']), 'd M, Y h:i A');
                                                    $status = "Subscription Period Expired";
                                                    $plan = "Subscription";
                                                    $t_left = "0";
                                                }
                                                else
                                                {
                                                    $expire = date_format(date_create($row['to_subscription_expire_date']), 'd M, Y h:i A');
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
            
                                                    $t_left = dateDiffInDays(date('Y-m-d H:i:s'), $row['to_subscription_expire_date']);
                                                }
                                            }
                                            else
                                            {
                                                $plan = $status = "No Plan";
                                                $start = "Not Started";
                                                $expire = "--";
                                                $t_left = "--";
                                            }
                                        ?>
                                        <div class="col-12 col-md-6 col-lg-6 info"><b>Bank Account Number : </b><?php echo $row['to_bank']; ?></div>
                                        <div class="col-12 col-md-6 col-lg-6 info"><b>IFSC Code : </b><?php echo $row['to_ifsc']; ?></div>
                                        <div class="col-12 col-md-6 col-lg-6 info"><b>Plan Type : </b><?php echo $plan; ?></div>
                                        <div class="col-12 col-md-6 col-lg-6 info"><b>Status : </b><?php echo $status; ?></div>
                                        <div class="col-12 col-md-6 col-lg-6 info"><b>Start Date : </b><?php echo $start; ?></div>
                                        <div class="col-12 col-md-6 col-lg-6 info"><b>End Date : </b><?php echo $expire; ?></div>
                                        <div class="col-12 col-md-6 col-lg-6 info"><b>Days Left : </b><?php echo $t_left; ?></div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-12 col-lg-6 text-center">
                                            <div class="font-weight-bold mb-2">
                                                <b>Registered On :</b> <?php $date = date_create($row['to_registered']);
                                                    $date = date_format($date, "d M, Y h:i A");
                                                    echo $date; ?>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-6 text-center">
                                            <button class="btn btn-primary btn-md" data-toggle="modal" data-target="#history">Subscription History</button>
                                
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
                                                                $sub = "select * from subscribed_users where subs_user_id = '".$row['to_id']."' and subs_user_type = 2 order by subs_id desc";
                                                                $run_sub = mysqli_query($link, $sub);
                                                                $counte = mysqli_num_rows($run_sub);
                                                                if($counte > 0)
                                                                {
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
                                                                }
                                                                else
                                                                {
                                                            ?>
                                                                        <tr>
                                                                            <td colspan="7" style="text-align: center">No subscription found</td>
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
                        <div class="col-12 col-md-12 col-lg-5">
                            <?php
                                if($doc_row['to_doc_verified'] == 0)
                                {
                                    $card = "card-warning";
                                    $button = '<button type="submit" class="btn btn-icon btn-success" title="Accept"><i class="fas fa-check-double"></i></button>';
                                }
                                else
                                {
                                    $card = "card-success";
                                    $button = '<button type="submit" class="btn btn-icon btn-danger" title="Reject"><i class="far fa-times-circle"></i></i></button>';
                                }
                            ?>
                            <div class="card <?php echo $card; ?>">
                                <div class="card-header">
                                    <h4>PAN Card</h4>
                                </div>
                                <div class="card-body text-center">
                                    <p>
                                        <img alt="truck_owner_pan_card_<?php echo $row['to_phone']; ?>" src="<?php echo $doc_row['to_doc_location'];?>">
                                    </p>
                                </div>
                                <div class="card-footer text-center">
                                    <div class="buttons" style="display: inline-flex">
                                        <form class="accept-reject">
                                            <input type="text" name="to_doc_id" value="<?php echo $doc_row['to_doc_id']; ?>" hidden>
                                            <input type="text" name="to_doc_status" value="<?php echo $doc_row['to_doc_verified']; ?>" hidden>
                                            <?php echo $button; ?>
                                        </form>
                                        <button class="btn btn-icon btn-info" data-toggle="modal" title="View" data-target="#pan_card"><i class="fas fa-eye"></i></button>
                                        <!-- Modal -->
                                        <div class="mymodal modal fade" id="pan_card" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background: rgba(0, 0, 0, 0.78) none repeat scroll 0% 0%">
                                            <div class="modal-dialog" role="document" style="pointer-events: unset; max-width: unset;">
                                                <section>
                                                    <div class="panzoom" style="text-align: center">
                                                        <img src="<?php echo $doc_row['to_doc_location'];?>" style="max-width: 100%" alt="truck_owner_pan_card_<?php echo $row['to_phone']; ?>">
                                                    </div>
                                                </section>
                                                <section class="buttons" style="margin-top: 2vh;">
                                                    <button class="zoom-in btn btn-icon btn-info" title="Zoom In"><i class="fas fa-search-plus" style="line-height: unset !important"></i></button>
                                                    <button class="zoom-out btn btn-icon btn-info" title="Zoom Out"><i class="fas fa-search-minus" style="line-height: unset !important"></i></button>
                                                    <!-- <input type="range" class="zoom-range"> -->
                                                    <button class="reset btn btn-icon btn-info" title="Reset"><i class="fas fa-redo" style="line-height: unset !important"></i></button>
                                                </section>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h2 class="section-title">Owner's Trucks</h2>
                    <div class="row mt-sm-4">
                        <div class="col-12 col-md-12">
                            <table>
                                <thead>
                                    <th>ID</th>
                                    <th>Category</th>
                                    <th>Number</th>
                                    <th>Driver's Name</th>
                                    <th>Driver's Phone</th>
                                    <th>Driver's Pic</th>
                                    <th>Driver's License</th>
                                    <th>Status</th>
                                    <th>Verified</th>
                                    <th>View</th>
                                </thead>
                                <tbody>
                                <?php
                                    $truck = "select * from trucks where trk_owner = '$owner'";
                                    $g_truck = mysqli_query($link, $truck);
                                    $count = mysqli_num_rows($g_truck);
                                    if($count == 0)
                                    {
                                ?>
                                    <tr>
                                        <td colspan="14">No Trucks found</td>
                                    </tr>
                                <?php
                                    }
                                    else
                                    {
                                        while($r_truck = mysqli_fetch_array($g_truck, MYSQLI_ASSOC))
                                        {
                                            $cat = "select * from truck_cat where trk_cat_id = '".$r_truck['trk_cat']."'";
                                            $g_cat = mysqli_query($link, $cat);
                                            $r_cat = mysqli_fetch_array($g_cat, MYSQLI_ASSOC);

                                            $typ = "select * from truck_cat_type where ty_id = '".$r_truck['trk_cat_type']."'";
                                            $g_typ = mysqli_query($link, $typ);
                                            $r_typ = mysqli_fetch_array($g_typ, MYSQLI_ASSOC);
                                ?>
                                        <tr>
                                            <td data-column="ID"><?php echo $r_truck['trk_id']; ?></td>
                                            <td data-column="Category"><?php echo $r_cat['trk_cat_name'].' ('.$r_typ['ty_name'].')'; ?></td>
                                            <td data-column="Number"><?php echo $r_truck['trk_num']; ?></td>
                                            <td data-column="Driver's Name"><?php echo $r_truck['trk_dr_name']; ?></td>
                                            <td data-column="Driver's Phone"><?php echo "+".$r_truck['trk_dr_phone_code']." ".$r_truck['trk_dr_phone']; ?></td>
                                            <td data-column="Driver's Pic">
                                                <button class="btn btn-icon btn-primary" data-toggle="modal" title="View" data-target="#dp<?php echo $r_truck['trk_id']; ?>"><i class="fas fa-eye truck-eye"></i></button>
                                                <!-- Modal -->
                                                <div class="mymodal modal fade" id="dp<?php echo $r_truck['trk_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background: rgba(0, 0, 0, 0.78) none repeat scroll 0% 0%">
                                                    <div class="modal-dialog" role="document" style="pointer-events: unset; max-width: unset;">
                                                        <section>
                                                            <div class="panzoom" style="text-align: center">
                                                                <img src="<?php echo $r_truck['trk_dr_pic'];?>" style="max-width: 100%" alt="truck_driver_pic_<?php echo $r_truck['trk_dr_phone']; ?>">
                                                            </div>
                                                        </section>
                                                        <section class="buttons" style="margin-top: 2vh;">
                                                            <button class="zoom-in btn btn-icon btn-info" title="Zoom In"><i class="fas fa-search-plus" style="line-height: unset !important"></i></button>
                                                            <button class="zoom-out btn btn-icon btn-info" title="Zoom Out"><i class="fas fa-search-minus" style="line-height: unset !important"></i></button>
                                                            <!-- <input type="range" class="zoom-range"> -->
                                                            <button class="reset btn btn-icon btn-info" title="Reset"><i class="fas fa-redo" style="line-height: unset !important"></i></button>
                                                        </section>
                                                    </div>
                                                </div>
                                            </td>
                                            <td data-column="Driver's License">
                                                <button class="btn btn-icon btn-primary" data-toggle="modal" title="View" data-target="#license<?php echo $r_truck['trk_id']; ?>"><i class="fas fa-eye truck-eye"></i></button>
                                                <!-- Modal -->
                                                <div class="mymodal modal fade" id="license<?php echo $r_truck['trk_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background: rgba(0, 0, 0, 0.78) none repeat scroll 0% 0%">
                                                    <div class="modal-dialog" role="document" style="pointer-events: unset; max-width: unset;">
                                                        <section>
                                                            <div class="panzoom" style="text-align: center">
                                                                <img src="<?php echo $r_truck['trk_dr_license'];?>" style="max-width: 100%" alt="truck_driver_license_<?php echo $r_truck['trk_dr_phone']; ?>">
                                                            </div>
                                                        </section>
                                                        <section class="buttons" style="margin-top: 2vh;">
                                                            <button class="zoom-in btn btn-icon btn-info" title="Zoom In"><i class="fas fa-search-plus" style="line-height: unset !important"></i></button>
                                                            <button class="zoom-out btn btn-icon btn-info" title="Zoom Out"><i class="fas fa-search-minus" style="line-height: unset !important"></i></button>
                                                            <!-- <input type="range" class="zoom-range"> -->
                                                            <button class="reset btn btn-icon btn-info" title="Reset"><i class="fas fa-redo" style="line-height: unset !important"></i></button>
                                                        </section>
                                                    </div>
                                                </div>
                                            </td>
                                            <td data-column="Status">
                                                <?php
                                                    if($r_truck['trk_active'] == 1)
                                                    {
                                                ?>
                                                    <span class="btn btn-sm btn-success">Active</span>
                                                <?php
                                                    }
                                                    else
                                                    {
                                                ?>
                                                    <span class="btn btn-sm btn-danger">Inactive</span>
                                                <?php
                                                    }
                                                ?>
                                            </td>
                                            <td data-column="Verified">
                                                <?php
                                                    if($r_truck['trk_verified'] == 1)
                                                    {
                                                ?>
                                                    <span class="btn btn-sm btn-success">Yes</span>
                                                <?php
                                                    }
                                                    else
                                                    {
                                                ?>
                                                    <span class="btn btn-sm btn-danger">No</span>
                                                <?php
                                                    }
                                                ?>
                                            </td>
                                            <td data-column="View">
                                                <a class="btn btn-icon btn-info" href="truck_profile?truck_id=<?php echo $r_truck['trk_id']; ?>">
                                                    <i class="fas fa-eye" title="View Details"></i>
                                                </a>
                                            </td>
                                        </tr>
                                <?php
                                        }
                                    }
                                ?>
                                </tbody>
                            </table>
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
            $(".truck_owners").addClass("active");

            $(".panzoom").panzoom({
                $zoomIn: $(".zoom-in"),
                $zoomOut: $(".zoom-out"),
                $zoomRange: $(".zoom-range"),
                $reset: $(".reset"),
                
                contain: 'invert',
            });
        });
    
        $(".accept-reject").submit(function(e)
        {
            var form_data = $(this).serialize();
            // alert(form_data);
            var button_content = $(this).find("button[type=submit]");
            $.ajax({
                url: "processing/curd_truck_owners.php",
                data: form_data,
                type: "POST",
                success: function(data)
                {
                    alert(data);
                    if(data === "Document verified" || data === "Set to not verified")
                    {
                        location.href = "truck_owner_profile?owner_id=<?php echo $owner; ?>";
                    }
                }
            });
            e.preventDefault();
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.panzoom/2.0.6/jquery.panzoom.min.js"></script>
</body>
</html>