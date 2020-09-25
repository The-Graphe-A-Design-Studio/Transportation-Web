<?php
    include('session.php');
    include('layout.php');
    $owner = $_GET['owner_id'];

    $sql = "select * from truck_owners where to_id = '$owner'";
    $g_sql = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($g_sql, MYSQLI_ASSOC);

    $doc = "select * from truck_owner_docs where to_doc_owner_phone = '".$row['to_phone']."'";
    $doc_run = mysqli_query($link, $doc);
    $doc_row = mysqli_fetch_array($doc_run, MYSQLI_ASSOC);
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
        td i{font-size: 1.2rem !important;}
        .info{padding: 1vh;}
        .truck-eye{font-size: 1em !important;}
        .card .card-body p img{max-height: 150px;}
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
                                            if($row['to_subscription_start_date'] == "0000-00-00 00:00:00")
                                            {
                                                $start = "Not Started";
                                            }
                                            else
                                            {
                                                $start = date_format(date_create($row['to_subscription_start_date']), 'd M, Y h:i A');
                                            }

                                            if($row['to_subscription_expire_date'] == "0000-00-00 00:00:00")
                                            {
                                                $end = "Not Started";
                                            }
                                            else
                                            {
                                                $end = date_format(date_create($row['to_subscription_expire_date']), 'd M, Y h:i A');
                                            }
                                        ?>
                                        <div class="col-12 col-md-6 col-lg-6 info"><b>Bank Account Number : </b><?php echo $row['to_bank']; ?></div>
                                        <div class="col-12 col-md-6 col-lg-6 info"><b>IFSC Code : </b><?php echo $row['to_ifsc']; ?></div>
                                        <div class="col-12 col-md-6 col-lg-6 info"><b>Subscription Start : </b><?php echo $start; ?></div>
                                        <div class="col-12 col-md-6 col-lg-6 info"><b>Subscription End : </b><?php echo $end; ?></div>
                                        <?php
                                            if($row['to_account_on'] == 1)
                                            {
                                                $date_now = new DateTime(date('Y-m-d H:i:s'));
                                                $date2    = new DateTime(date_format(date_create($row['to_subscription_expire_date']), 'Y-m-d H:i:s'));
            
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
                                                $t_left = 0;
                                            }
                                        ?>
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
                            <div class="card card-primary">
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
                                        <button class="btn btn-icon btn-info" data-toggle="modal" title="View" data-target="#pan_card"><i class="fas fa-eye"></i></button>
                                        <!-- Modal -->
                                        <div class="mymodal modal fade" id="pan_card" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background: rgba(0, 0, 0, 0.78) none repeat scroll 0% 0%">
                                            <div class="modal-dialog" role="document">
                                                <img src="<?php echo $doc_row['to_doc_location'];?>" style="max-width: 100%" alt="truck_owner_pan_card_<?php echo $row['to_phone']; ?>">
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
                                    <th>Type</th>
                                    <th>Number</th>
                                    <th>Driver's Name</th>
                                    <th>Driver's Phone</th>
                                    <th>Driver's Pic</th>
                                    <th>Driver's License</th>
                                    <th>RC</th>
                                    <th>Insurance</th>
                                    <th>Road Tax</th>
                                    <th>RTO</th>
                                    <th>Status</th>
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
                                            <td data-column="Category"><?php echo $r_cat['trk_cat_name']; ?></td>
                                            <td data-column="Type"><?php echo $r_typ['ty_name']; ?></td>
                                            <td data-column="Number"><?php echo $r_truck['trk_num']; ?></td>
                                            <td data-column="Driver's Name"><?php echo $r_truck['trk_dr_name']; ?></td>
                                            <td data-column="Driver's Phone"><?php echo "+".$r_truck['trk_dr_phone_code']." ".$r_truck['trk_dr_phone']; ?></td>
                                            <td data-column="Driver's Pic">
                                                <button class="btn btn-icon btn-primary" data-toggle="modal" title="View" data-target="#dp"><i class="fas fa-eye truck-eye"></i></button>
                                                <!-- Modal -->
                                                <div class="mymodal modal fade" id="dp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background: rgba(0, 0, 0, 0.78) none repeat scroll 0% 0%">
                                                    <div class="modal-dialog" role="document">
                                                        <img src="<?php echo $r_truck['trk_dr_pic'];?>" style="max-width: 100%" alt="truck_driver_pic_<?php echo $r_truck['trk_dr_phone']; ?>">
                                                    </div>
                                                </div>
                                            </td>
                                            <td data-column="Driver's License">
                                                <button class="btn btn-icon btn-primary" data-toggle="modal" title="View" data-target="#license"><i class="fas fa-eye truck-eye"></i></button>
                                                <!-- Modal -->
                                                <div class="mymodal modal fade" id="license" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background: rgba(0, 0, 0, 0.78) none repeat scroll 0% 0%">
                                                    <div class="modal-dialog" role="document">
                                                        <img src="<?php echo $r_truck['trk_dr_license'];?>" style="max-width: 100%" alt="truck_driver_license_<?php echo $r_truck['trk_dr_phone']; ?>">
                                                    </div>
                                                </div>
                                            </td>
                                            <td data-column="RC">
                                                <button class="btn btn-icon btn-primary" data-toggle="modal" title="View" data-target="#rc"><i class="fas fa-eye truck-eye"></i></button>
                                                <!-- Modal -->
                                                <div class="mymodal modal fade" id="rc" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background: rgba(0, 0, 0, 0.78) none repeat scroll 0% 0%">
                                                    <div class="modal-dialog" role="document">
                                                        <img src="<?php echo $r_truck['trk_rc'];?>" style="max-width: 100%" alt="truck_rc_<?php echo $r_truck['trk_num']; ?>">
                                                    </div>
                                                </div>
                                            </td>
                                            <td data-column="Insurance">
                                                <button class="btn btn-icon btn-primary" data-toggle="modal" title="View" data-target="#insurance"><i class="fas fa-eye truck-eye"></i></button>
                                                <!-- Modal -->
                                                <div class="mymodal modal fade" id="insurance" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background: rgba(0, 0, 0, 0.78) none repeat scroll 0% 0%">
                                                    <div class="modal-dialog" role="document">
                                                        <img src="<?php echo $r_truck['trk_insurance'];?>" style="max-width: 100%" alt="truck_insurance_<?php echo $r_truck['trk_num']; ?>">
                                                    </div>
                                                </div>
                                            </td>
                                            <td data-column="Road Tax">
                                                <button class="btn btn-icon btn-primary" data-toggle="modal" title="View" data-target="#road_tax"><i class="fas fa-eye truck-eye"></i></button>
                                                <!-- Modal -->
                                                <div class="mymodal modal fade" id="road_tax" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background: rgba(0, 0, 0, 0.78) none repeat scroll 0% 0%">
                                                    <div class="modal-dialog" role="document">
                                                        <img src="<?php echo $r_truck['trk_road_tax'];?>" style="max-width: 100%" alt="truck_road_tax_<?php echo $r_truck['trk_num']; ?>">
                                                    </div>
                                                </div>
                                            </td>
                                            <td data-column="RTO">
                                                <button class="btn btn-icon btn-primary" data-toggle="modal" title="View" data-target="#rto"><i class="fas fa-eye truck-eye"></i></button>
                                                <!-- Modal -->
                                                <div class="mymodal modal fade" id="rto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background: rgba(0, 0, 0, 0.78) none repeat scroll 0% 0%">
                                                    <div class="modal-dialog" role="document">
                                                        <img src="<?php echo $r_truck['trk_rto'];?>" style="max-width: 100%" alt="truck_rto_<?php echo $r_truck['trk_num']; ?>">
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
                                            <td data-column="View">
                                                <a class="btn btn-icon btn-info" href="truck_profile?truck_id=<?php echo $r_truck['trk_id']; ?>"><i class="fas fa-eye" title="View Details"></i></a>
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
        });
    
        $(".to_status").submit(function(e)
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
                    if(data === "Truck Owner Accepted" || data === "Truck Owner Rejected")
                    {
                        $( "#refresh_btn" ).trigger( "click" );
                    }
                }
            });
            e.preventDefault();
        });
    </script>
</body>
</html>