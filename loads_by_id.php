<?php
    include('session.php');
    include('layout.php');

    $load = $_GET['load_id'];

    $sql = "select * from cust_order where or_id = '$load'";
    $g_sql = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($g_sql, MYSQLI_ASSOC);

    $customer = "select * from customers where cu_id = '".$row['or_cust_id']."'";
    $get_cust = mysqli_query($link, $customer);
    $row_cust = mysqli_fetch_array($get_cust, MYSQLI_ASSOC);

    $truck = "select * from truck_cat where trk_cat_id = '".$row['or_truck_preference']."'";
    $g_truck = mysqli_query($link, $truck);
    $row_truck = mysqli_fetch_array($g_truck, MYSQLI_ASSOC);

    if($row['or_price_unit'] == 1)
    {
        $show_unit = "Ton";
    }
    else
    {
        $show_unit = "Truck";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Loads | Truck Wale</title>
    <link rel="stylesheet" href="assets/css/table.css">
    <?php echo $head_tags; ?>
    <style>
        .dates{font-size: 1em; font-weight: 600;}
        .profile-widget{margin-top: 0 !important;}
        .activity-icon i{line-height: unset !important; font-size: 1em;}
        #map{height: 100%}
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
                    <h1>Load ID <?php echo $load; ?></h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="dashboard">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="loads">Loads</a></div>
                        <div class="breadcrumb-item">Load by ID</div>
                    </div>
                </div>

                <div class="section-body">

                    <div class="row">
                        <div class="col-12">
                            <div class="card" style="margin-bottom: 0 !important">
                                <div class="card-body">
                                    <ul class="nav nav-tabs" id="loadTab" role="tablist" style="margin-bottom: 2vh">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="load-details" data-toggle="tab" href="#load_details" role="tab" aria-controls="details" aria-selected="true">Load Details</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="load-bidding" data-toggle="tab" href="#load_bidding" role="tab" aria-controls="bidding" aria-selected="false">Bidding</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="delivery-trucks" data-toggle="tab" href="#delivery_trucks" role="tab" aria-controls="delivery trucks" aria-selected="false">Delivery Trucks</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="payment-details" data-toggle="tab" href="#payment_details" role="tab" aria-controls="payment" aria-selected="false">Payment</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="myTab3Content">
                                        <div class="tab-pane fade show active" id="load_details" role="tabpanel" aria-labelledby="load-details">
                                            <div class="row">
                                                
                                            </div>
                                            <div class="row">
                                                <div class="col-12 col-lg-3">
                                                    <div class="card profile-widget services-widget">
                                                        <div class="profile-widget-description">
                                                            <div class="profile-widget-name text-center" style="margin-bottom: 0 !important">
                                                                <b>Shipper - </b><a href="shipper_profile?shipper_id=<?php echo $row_cust['cu_id']; ?>">+<?php echo $row_cust['cu_phone_code'].' '.$row_cust['cu_phone']; ?></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-3">
                                                    <div class="card profile-widget services-widget">
                                                        <div class="profile-widget-description">
                                                            <div class="profile-widget-name text-center" style="margin-bottom: 0 !important">
                                                                <b>Active on - </b><?php echo date_format(date_create($row['or_active_on']), 'd M, Y h:i A'); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-3">
                                                    <div class="card profile-widget services-widget">
                                                        <div class="profile-widget-description">
                                                            <div class="profile-widget-name text-center" style="margin-bottom: 0 !important">
                                                                <b>Expire on - </b><?php echo date_format(date_create($row['or_expire_on']), 'd M, Y h:i A'); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-3">
                                                    <div class="card profile-widget services-widget">
                                                        <div class="profile-widget-description">
                                                            <div class="profile-widget-name text-center" style="margin-bottom: 0 !important">
                                                                <b>Contact Person - </b><?php echo $row['or_contact_person_name'].", ".$row['or_contact_person_phone']; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <?php
                                                    if($row['or_shipper_on'] == 3 && $row['or_status'] == 1 || $row['or_status'] == 2)
                                                    {
                                                        if($row['or_admin_expected_price'] == 0)
                                                        {
                                                            $admin_expected = 
                                                            '
                                                            <div class="col-12 col-lg-4">
                                                                <div class="card profile-widget services-widget">
                                                                    <div class="profile-widget-description">
                                                                        <div class="profile-widget-name" style="margin-bottom: 0 !important">
                                                                            <form class="expected">
                                                                                <div class="form-group">
                                                                                    <label>Admin Total Commission (in %)</label>
                                                                                    <div class="input-group mb-3">
                                                                                        <input type="text" class="form-control" name="admin_expected_price" value="'.$row['or_admin_expected_price'].'">
                                                                                        <input type="text" name="load_id" value="'.$row['or_id'].'" hidden>
                                                                                        <div class="input-group-append">
                                                                                            <button class="btn btn-primary" type="submit">Set</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            ';
                                                        }
                                                        else
                                                        {
                                                            $admin_expected = 
                                                            '
                                                            <div class="col-12 col-lg-4">
                                                                <div class="card profile-widget services-widget">
                                                                    <div class="profile-widget-description">
                                                                        <div class="profile-widget-name" style="margin-bottom: 0 !important">
                                                                            <form class="expected">
                                                                                <div class="form-group">
                                                                                    <label>Admin Total Commission (in %)</label>
                                                                                    <div class="input-group mb-3">
                                                                                        <input type="text" class="form-control" name="admin_expected_price" value="'.$row['or_admin_expected_price'].'">
                                                                                        <input type="text" name="load_id" value="'.$row['or_id'].'" hidden>
                                                                                        <div class="input-group-append">
                                                                                            <button class="btn btn-primary" type="submit">Update</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            ';
                                                        }
                                                    }
                                                    else
                                                    {
                                                        $admin_expected = '';
                                                    }
                                                    
                                                    echo $admin_expected;
                                                ?>
                                                <div class="col-12 col-lg-4">
                                                    <div class="card profile-widget services-widget">
                                                        <div class="profile-widget-description">
                                                            <div class="profile-widget-name" style="margin-bottom: 0 !important">
                                                                <div class="buttons text-center">
                                                                    <?php
                                                                        if($row['or_status'] == 2 && $row['or_shipper_on'] == 3)
                                                                        {
                                                                            $load_staus = 
                                                                            '
                                                                                <form class="expected">
                                                                                    <input type="text" name="load_status" value="1" hidden>
                                                                                    <input type="text" name="load_id_to_set" value="'.$row['or_id'].'" hidden>
                                                                                    <button type="submit" class="btn btn-lg btn-success">Activate Load</button>
                                                                                </form>
                                                                            ';
                                                                        }
                                                                        elseif($row['or_status'] == 1 && $row['or_shipper_on'] == 3)
                                                                        {
                                                                            $load_staus = 
                                                                            '
                                                                                <form class="expected">
                                                                                    <input type="text" name="load_status" value="2" hidden>
                                                                                    <input type="text" name="load_id_to_set" value="'.$row['or_id'].'" hidden>
                                                                                    <button type="submit" class="btn btn-lg btn-danger">Deactivate Load</button>
                                                                                </form>
                                                                            ';
                                                                        }
                                                                        else
                                                                        {
                                                                            $load_staus = '';
                                                                        }
                                                                        echo $load_staus;
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 col-lg-4">
                                                    <div class="section-body">
                                                        <h2 class="section-title">Source</h2>
                                                        <div class="activities">
                                                            <?php
                                                                $source = "select * from cust_order_source where or_uni_code = '".$row['or_uni_code']."'";
                                                                $get_source = mysqli_query($link, $source);
                                                                while($row_source = mysqli_fetch_array($get_source, MYSQLI_ASSOC))
                                                                {
                                                            ?>
                                                            <div class="activity">
                                                                <div class="activity-icon bg-success text-white shadow-primary">
                                                                    <i class="fas fa-map-marker-alt"></i>
                                                                </div>
                                                                <div class="activity-detail">
                                                                    <p><?php echo $row_source['or_source']; ?></p>
                                                                </div>
                                                            </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-4">
                                                    <div class="section-body">
                                                        <h2 class="section-title">Destination</h2>
                                                        <div class="activities">
                                                            <?php
                                                                $source = "select * from cust_order_destination where or_uni_code = '".$row['or_uni_code']."'";
                                                                $get_source = mysqli_query($link, $source);
                                                                while($row_source = mysqli_fetch_array($get_source, MYSQLI_ASSOC))
                                                                {
                                                            ?>
                                                            <div class="activity">
                                                                <div class="activity-icon bg-danger text-white shadow-primary">
                                                                    <i class="fas fa-map-marker-alt"></i>
                                                                </div>
                                                                <div class="activity-detail">
                                                                    <p><?php echo $row_source['or_destination']; ?></p>
                                                                </div>
                                                            </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-4">
                                                    <div class="section-body">
                                                        <h2 class="section-title">Truck Required (<?php echo $row_truck['trk_cat_name']; ?> )</h2>
                                                        <ul class="list-group">
                                                        <?php
                                                            $truck_type = "select * from cust_order_truck_pref where or_uni_code = '".$row['or_uni_code']."'";
                                                            $g_type = mysqli_query($link, $truck_type);
                                                            while($row_type = mysqli_fetch_array($g_type, MYSQLI_ASSOC))
                                                            {
                                                                $type = "select * from truck_cat_type where ty_id = '".$row_type['or_truck_pref_type']."'";
                                                                $r_type = mysqli_query($link, $type);
                                                                $types = mysqli_fetch_array($r_type, MYSQLI_ASSOC);
                                                        ?>
                                                            <li class="list-group-item"><?php echo $types['ty_name']; ?></li>
                                                        <?php } ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 col-sm-6 col-md-3 col-lg-3">
                                                    <div class="card profile-widget services-widget">
                                                        <div class="profile-widget-description">
                                                            <div class="profile-widget-name" style="margin-bottom: 0 !important">
                                                                <b>Product - </b><?php echo $row['or_product']; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-3 col-lg-3">
                                                    <div class="card profile-widget services-widget">
                                                        <div class="profile-widget-description">
                                                            <div class="profile-widget-name" style="margin-bottom: 0 !important">
                                                                <b>Price Unit - </b>
                                                                <?php
                                                                    if($row['or_price_unit'] == 1)
                                                                    {
                                                                        echo "Tonnage";
                                                                    }
                                                                    else
                                                                    {
                                                                        echo "Number of Trucks";
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-3 col-lg-3">
                                                    <div class="card profile-widget services-widget">
                                                        <div class="profile-widget-description">
                                                            <div class="profile-widget-name" style="margin-bottom: 0 !important">
                                                                <b>Quantity - </b>
                                                                <?php
                                                                    if($row['or_price_unit'] == 1)
                                                                    {
                                                                        echo $row['or_quantity']." Ton";
                                                                    }
                                                                    else
                                                                    {
                                                                        echo $row['or_quantity']." Trucks";
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-3 col-lg-3">
                                                    <div class="card profile-widget services-widget">
                                                        <div class="profile-widget-description">
                                                            <div class="profile-widget-name" style="margin-bottom: 0 !important">
                                                                <b>Payment Mode - </b>
                                                                <?php
                                                                    if($row['or_payment_mode'] == 1)
                                                                    {
                                                                        echo "Negotiable";
                                                                        $deal_pay_mode = "Negotiable";
                                                                    }
                                                                    elseif($row['or_payment_mode'] == 2)
                                                                    {
                                                                        echo "Advance Pay ( ".$row['or_advance_pay']."% )";
                                                                        $deal_pay_mode = "Advance Pay ( ".$row['or_advance_pay']."% )";
                                                                    }
                                                                    else
                                                                    {
                                                                        echo "Full pay after unloading";
                                                                        $deal_pay_mode = "Full pay after unloading";
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-3 col-lg-3">
                                                    <div class="card profile-widget services-widget">
                                                        <div class="profile-widget-description">
                                                            <div class="profile-widget-name" style="margin-bottom: 0 !important">
                                                                <b>Expected Price - </b>Rs. 
                                                                <?php
                                                                    if($row['or_price_unit'] == 1)
                                                                    {
                                                                        echo $row['or_expected_price']." / Ton";
                                                                        $show_unit = "Ton";
                                                                    }
                                                                    else
                                                                    {
                                                                        echo $row['or_expected_price']." / Truck";
                                                                        $show_unit = "Truck";
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="load_bidding" role="tabpanel" aria-labelledby="load-bidding">
                                            <div class="row">
                                                <div class="col-12 col-lg-5">
                                                    <div class="card card-info">
                                                        <div class="card-header">
                                                            <h4>Truck Owners</h4>
                                                        </div>
                                                        <div class="card-body" style="padding: 1vh !important;">
                                                            <div class="form-row">
                                                                <div class="form-group col-md-6">
                                                                    <input type="text" class="form-control common_selector search_id" placeholder="Search by ID">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <input type="text" class="form-control common_selector search_num" placeholder="Search by Phone Number">
                                                                </div>
                                                            </div>
                                                            <div class="filter_truck_owners"></div>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="card card-info">
                                                        <div class="card-header">
                                                            <h4>Drivers</h4>
                                                        </div>
                                                        <div class="card-body" style="padding: 1vh !important;">
                                                            <div class="form-row">
                                                                <div class="form-group col-md-6">
                                                                    <input type="text" class="form-control common_selector search_driver_id" placeholder="Search by ID">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <input type="text" class="form-control common_selector search_driver_num" placeholder="Search by Phone Number">
                                                                </div>
                                                            </div>
                                                            <div class="filter_drivers"></div>
                                                        </div>
                                                    </div> -->
                                                </div>
                                                <div class="col-12 col-lg-7">
                                                    <div class="card card-success">
                                                        <div class="card-header">
                                                            <h4>All Bids</h4>
                                                            <input type="button" id="refresh_btn" value="Refresh" hidden>
                                                        </div>
                                                        <div class="card-body" style="padding: 1vh !important;">
                                                            <!-- <div class="custom-switches-stacked mt-2" style="flex-direction: row">
                                                                <div class="form-group">
                                                                    <label class="custom-switch">
                                                                        <input type="radio" name="option" class="custom-switch-input common_selector nothing" value="1" checked>
                                                                        <span class="custom-switch-indicator"></span>
                                                                        <span class="custom-switch-description">All</span>
                                                                    </label>
                                                                    <label class="custom-switch">
                                                                        <input type="radio" name="option" class="custom-switch-input common_selector owners" value="2">
                                                                        <span class="custom-switch-indicator"></span>
                                                                        <span class="custom-switch-description">Truck Owners</span>
                                                                    </label>
                                                                    <label class="custom-switch">
                                                                        <input type="radio" name="option" class="custom-switch-input common_selector drivers" value="3">
                                                                        <span class="custom-switch-indicator"></span>
                                                                        <span class="custom-switch-description">Drivers</span>
                                                                    </label>
                                                                </div>
                                                            </div> -->
                                                            <div class="filter_bid_data"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="delivery_trucks" role="tabpanel" aria-labelledby="delivery-trucks">
                                            <div class="row">
                                                <div class="col-12">
                                                    <?php
                                                        $del = "select * from deliveries where or_id = '".$row['or_id']."' and cu_id = '".$row_cust['cu_id']."'";
                                                        $run_del = mysqli_query($link, $del);
                                                        $row_del = mysqli_fetch_array($run_del, MYSQLI_ASSOC);

                                                        $owner = "select * from truck_owners where to_id = '".$row_del['to_id']."'";
                                                        $run_owner = mysqli_query($link, $owner);
                                                        $row_owner = mysqli_fetch_array($run_owner, MYSQLI_ASSOC);
                                                    ?>
                                                    <div class="section-body">
                                                        <h2 class="section-title" style="margin: 10px 0 10px 0 !important">Truck Provider/Owner ID <?php echo $row_owner['to_id']; ?></h2>
                                                        <div class="row">
                                                            <div class="col-12 col-md-3">
                                                                <div class="card">
                                                                    <div class="card-body" style="padding: 1vh !important;">
                                                                        <b>Name : </b><a href="truck_owner_profile?owner_id=<?php echo $row_owner['to_id']; ?>"><?php echo $row_owner['to_name']; ?></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-md-3">
                                                                <div class="card">
                                                                    <div class="card-body" style="padding: 1vh !important;">
                                                                        <b>Phone : </b>+<?php echo $row_owner['to_phone_code'].' '.$row_owner['to_phone']; ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="section-body">
                                                        <h2 class="section-title" style="margin: 10px 0 10px 0 !important">Trucks</h2>
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-12 col-sm-12 col-md-2">
                                                                        <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
                                                                            <?php
                                                                                $del_trucks = "select * from delivery_trucks where del_id = '".$row_del['del_id']."'";
                                                                                $run_del_trucks = mysqli_query($link, $del_trucks);
                                                                                $count_del_trucks = mysqli_num_rows($run_del_trucks);
                                                                                if($count_del_trucks == 0)
                                                                                {
                                                                            ?>
                                                                                <li class="nav-item active">
                                                                                    No Trucks Found
                                                                                </li>
                                                                            <?php
                                                                                }
                                                                                else
                                                                                {
                                                                                    while($row_del_trucks = mysqli_fetch_array($run_del_trucks, MYSQLI_ASSOC))
                                                                                    {
                                                                                        $trucks = "select * from trucks where trk_id = '".$row_del_trucks['trk_id']."'";
                                                                                        $run_trucks = mysqli_query($link, $trucks);
                                                                                        $row_trucks = mysqli_fetch_array($run_trucks, MYSQLI_ASSOC);
                                                                            ?>
                                                                                        <li class="nav-item">
                                                                                            <a class="nav-link" id="truck_num<?php echo $row_trucks['trk_num']; ?>" data-toggle="tab" href="#truck_id<?php echo $row_trucks['trk_id']; ?>" role="tab" aria-controls="<?php echo $row_trucks['trk_num']; ?>" aria-selected="true"><?php echo $row_trucks['trk_num']; ?></a>
                                                                                        </li>
                                                                            <?php
                                                                                    }
                                                                                }
                                                                            ?>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-12 col-sm-12 col-md-10">
                                                                        <div class="tab-content no-padding" id="myTab2Content">
                                                                            <?php
                                                                                $del_trucks = "select * from delivery_trucks where del_id = '".$row_del['del_id']."'";
                                                                                $run_del_trucks = mysqli_query($link, $del_trucks);
                                                                                $count_del_trucks = mysqli_num_rows($run_del_trucks);
                                                                                if($count_del_trucks == 0)
                                                                                {
                                                                            ?>
                                                                                <div class="tab-pane fade active show" id="contact4" role="tabpanel" aria-labelledby="contact-tab4">
                                                                                    No Trucks assigned by owner
                                                                                </div>
                                                                            <?php
                                                                                }
                                                                                else
                                                                                {
                                                                                    while($row_del_trucks = mysqli_fetch_array($run_del_trucks, MYSQLI_ASSOC))
                                                                                    {
                                                                                        $trucks = "select * from trucks where trk_id = '".$row_del_trucks['trk_id']."'";
                                                                                        $run_trucks = mysqli_query($link, $trucks);
                                                                                        $row_trucks = mysqli_fetch_array($run_trucks, MYSQLI_ASSOC);
                                                                            ?>
                                                                            <div class="tab-pane fade" id="truck_id<?php echo $row_trucks['trk_id']; ?>" role="tabpanel" aria-labelledby="truck_num<?php echo $row_trucks['trk_num']; ?>">
                                                                                <div class="row">
                                                                                    <div class="col-12 col-md-3">
                                                                                        <div class="card card-info">
                                                                                            <div class="card-header">
                                                                                                <h4>Truck's Info</h4>
                                                                                            </div>
                                                                                            <div class="card-body text-center">
                                                                                                <img src="<?php echo $row_trucks['trk_dr_pic']; ?>" height="100" alt="driver_image_<?php echo $row_trucks['trk_num']; ?>">
                                                                                                <br><br>
                                                                                                <p class="text-left">
                                                                                                    <b>Driver Name : </b><?php echo $row_trucks['trk_dr_name']; ?>
                                                                                                    <br>
                                                                                                    <b>Driver Phone : </b>+<?php echo $row_trucks['trk_dr_phone_code'].' '.$row_trucks['trk_dr_phone']; ?>
                                                                                                    <br>
                                                                                                    <b>Truck Number : </b><a href="truck_profile?truck_id=<?php echo $row_trucks['trk_id']; ?>"><?php echo $row_trucks['trk_num']; ?></a>
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-12 col-md-6" style="padding: 0.1% !important">
                                                                                        <div class="card">
                                                                                            <div id="map<?php echo $row_trucks['trk_id']; ?>" style="height: 340px; width: 100%"></div>
                                                                                            <script>
                                                                                                // Initialize and add the map
                                                                                                function initMap() {
                                                                                                    const map = new google.maps.Map(document.getElementById("map<?php echo $row_trucks['trk_id']; ?>"), {
                                                                                                    zoom: 14,
                                                                                                    center: {
                                                                                                        lat: <?php echo $row_del_trucks['lat']; ?>,
                                                                                                        lng: <?php echo $row_del_trucks['lng']; ?>,
                                                                                                    },
                                                                                                    });
                                                                                                    const image =
                                                                                                    "assets/img/delivery_truck.png";
                                                                                                    const beachMarker = new google.maps.Marker({
                                                                                                    position: {
                                                                                                        lat: <?php echo $row_del_trucks['lat']; ?>,
                                                                                                        lng: <?php echo $row_del_trucks['lng']; ?>,
                                                                                                    },
                                                                                                    map,
                                                                                                    icon: image,
                                                                                                    });
                                                                                                }
                                                                                            </script>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-12 col-md-3">
                                                                                        <div class="card card-info">
                                                                                            <div class="card-header">
                                                                                                <h4>Delivery Status</h4>
                                                                                            </div>
                                                                                            <div class="card-body text-center">
                                                                                                <?php
                                                                                                    if($row_del_trucks['otp_verified'] == 1)
                                                                                                    {
                                                                                                        $data = file_get_contents("https://maps.google.com/maps/api/geocode/json?latlng=".$row_del_trucks['lat'].",".$row_del_trucks['lng']."&key=AIzaSyDH8iEIWiHLIRcSsJWm8Fh1qbgwt0JRAc0");
                                                                                                        $data = json_decode($data);
                                                                                                        $add_array  = $data->results;
                                                                                                        $add_array = $add_array[0];
                                                                                                        $add_array = $add_array->address_components;
                                                                                                        $state = "Not found"; 
                                                                                                        $city = "Not found";
                                                                                                        foreach ($add_array as $key)
                                                                                                        {
                                                                                                            if($key->types[0] == 'locality')
                                                                                                            {
                                                                                                                $city = $key->long_name;
                                                                                                            }
                                                                                                            if($key->types[0] == 'administrative_area_level_1')
                                                                                                            {
                                                                                                                $state = $key->long_name;
                                                                                                            }
                                                                                                        }

                                                                                                        $otp = "Verified by shipper";

                                                                                                        if($row_del_trucks['status'] == 1)
                                                                                                        {
                                                                                                            $deli_status = "Started";
                                                                                                        }
                                                                                                        elseif($row_del_trucks['status'] == 2)
                                                                                                        {
                                                                                                            $deli_status = "Completed";
                                                                                                        }
                                                                                                        else
                                                                                                        {
                                                                                                            $deli_status = "Not Started";
                                                                                                        }
                                                                                                    }
                                                                                                    else
                                                                                                    {
                                                                                                        $otp = "Not verified by shipper";
                                                                                                        $city = "-";
                                                                                                        $state = "-";
                                                                                                        $deli_status = "Not Started";
                                                                                                    }
                                                                                                ?>
                                                                                                <p class="text-left">
                                                                                                    <b>OTP : </b><?php echo $otp; ?>
                                                                                                    <br>
                                                                                                    <b>Last Location : </b><?php echo $city.', '.$state; ?>
                                                                                                    <br>
                                                                                                    <b>Status : </b><?php echo $deli_status; ?>
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <?php
                                                                                    }
                                                                                }
                                                                            ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="payment_details" role="tabpanel" aria-labelledby="payment-details">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="section-body">
                                                        <h2 class="section-title" style="margin: 10px 0 10px 0 !important">Payment Details</h2>
                                                        <div class="row">
                                                            <?php
                                                                $del_data = "select cust_order.*, deliveries.* from cust_order, deliveries where cust_order.or_id = '$load' and cust_order.or_id = deliveries.or_id";
                                                                $run_del_data = mysqli_query($link, $del_data);
                                                                $row_del_data = mysqli_fetch_array($run_del_data, MYSQLI_ASSOC);
                                                            ?>
                                                            <div class="col-12 col-md-3">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <b>Quantity : </b><?php echo $row_del_data['or_quantity']; ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-md-3">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <b>Deal Price : </b><?php echo $row_del_data['deal_price'].' / '.$show_unit; ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-md-3">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <?php $total_price = round(($row_del_data['deal_price'] * $row_del_data['or_quantity']), 2); ?>
                                                                        <b>Total Price : </b><?php echo "Rs. ".$total_price; ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-md-3">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <?php $commi = round((($total_price) * ($row_del_data['or_admin_expected_price']/100)), 2); ?>
                                                                        <b>Commission : </b>Rs. <?php echo $commi." (".$row_del_data['or_admin_expected_price']."%)"; ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-md-3">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <?php $grand_total = round(($total_price + $commi), 2); ?>
                                                                        <b>Grand Total : </b><?php echo "Rs. ".$grand_total; ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-md-3">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <b>Pay Mode : </b><?php echo $deal_pay_mode; ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="section-body">
                                                        <h2 class="section-title" style="margin: 10px 0 10px 0 !important">Payment Status</h2>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <table>
                                                                    <thead>
                                                                        <th>ID</th>
                                                                        <th>Order ID</th>
                                                                        <th>Payment ID</th>
                                                                        <th>Amount</th>
                                                                        <th>Date</th>
                                                                        <th>Mode</th>
                                                                        <th>Method</th>
                                                                        <th>Status</th>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                            $pay_data = "select * from load_payments where delivery_id = '".$row_del_data['del_id']."'";
                                                                            $run_pay_data = mysqli_query($link, $pay_data);
                                                                            while($row_pay_data = mysqli_fetch_array($run_pay_data, MYSQLI_ASSOC))
                                                                            {
                                                                                if($row_pay_data['pay_mode'] == 1)
                                                                                {
                                                                                    $mode = "Advance + Commission";
                                                                                }
                                                                                elseif($row_pay_data['pay_mode'] == 2)
                                                                                {
                                                                                    $mode = "Remaining";
                                                                                }
                                                                                else
                                                                                {
                                                                                    $mode = "Full after unloading";
                                                                                }

                                                                                if($row_pay_data['pay_method'] == 1)
                                                                                {
                                                                                    $method = "Online";
                                                                                }
                                                                                elseif($row_pay_data['pay_method'] == 2)
                                                                                {
                                                                                    $method = "Cash";
                                                                                }
                                                                                else
                                                                                {
                                                                                    $method = "-";
                                                                                }

                                                                                if($row_pay_data['pay_status'] == 1)
                                                                                {
                                                                                    $status = "<span class='btn btn-md btn-success'>Paid</span>";
                                                                                }
                                                                                else
                                                                                {
                                                                                    $status = "<span class='btn btn-md btn-warning'>Pending</span>";
                                                                                }

                                                                                if($row_pay_data['payment_date'] === "0000-00-00 00:00:00")
                                                                                {
                                                                                    $pay_date = "-";
                                                                                }
                                                                                else
                                                                                {
                                                                                    $pay_date = date_format(date_create($row_pay_data['payment_date']), 'd M, Y h:i A');
                                                                                }
                                                                        ?>
                                                                        <tr>
                                                                            <td data-column="ID"><?php echo $row_pay_data['pay_id']; ?></td>
                                                                            <td data-column="Order ID"><?php if($row_pay_data['razorpay_order_id'] === ""){echo "-";}
                                                                                                                else{echo $row_pay_data['razorpay_order_id'];} ?></td>
                                                                            <td data-column="Payment ID"><?php if($row_pay_data['razorpay_payment_id'] === ""){echo "-";}
                                                                                                                else{echo $row_pay_data['razorpay_payment_id'];} ?></td>
                                                                            <td data-column="Amount"><?php echo $row_pay_data['amount']; ?></td>
                                                                            <td data-column="Date"><?php echo $pay_date; ?></td>
                                                                            <td data-column="Mode"><?php echo $mode; ?></td>
                                                                            <td data-column="Method"><?php echo $method; ?></td>
                                                                            <td data-column="Status"><?php echo $status; ?></td>
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
    <script defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDH8iEIWiHLIRcSsJWm8Fh1qbgwt0JRAc0&callback=initMap"></script>
    <script type="text/javascript">
        $(".expected").submit(function(e)
		{
			var form_data = $(this).serialize();
			// alert(form_data);
			var button_content = $(this).find('button[type=submit]');
			button_content.addClass("disabled btn-progress");
            $.ajax({
				url: 'processing/curd_loads.php',
				data: form_data,
				type: 'POST',
				success: function(data)
				{
                    alert(data);
                    button_content.removeClass("disabled btn-progress");
					if(data === "Load reset, activate again & Admin Expected Price Updated" || data === "Load Status Updated")
					{
						location.href="loads_by_id?load_id=<?php echo $load; ?>";
					}
				}
			});
			e.preventDefault();
        });
        
        $(document).ready(function()
        {
            filter_data();

            function filter_data()
            {
                var truck_owner_data = 'fetch_data';
                var search_id = get_key('search_id');
                var search_num = get_num('search_num');
                var load_id = "<?php echo $load; ?>";
                // alert(load_id);
                $.ajax({
                    url:"processing/curd_loads.php",
                    method:"POST",
                    data:{truck_owner_data:truck_owner_data, search_id: search_id, search_num: search_num, load_id: load_id},
                    success:function(data){
                        $('.filter_truck_owners').html(data);
                    }
                });
            }

            function get_key()
            {
                return $('.search_id').val();
            }

            function get_num()
            {
                return $('.search_num').val();
                
            }

            $('.common_selector').on('keyup change',function(){
                filter_data();
                get_key();
                get_num();
            });





            filter_driver_data();

            function filter_driver_data()
            {
                var driver_data = 'fetch_data';
                var search_driver_id = get_driver_key('search_driver_id');
                var search_driver_num = get_driver_num('search_driver_num');
                var load_id = "<?php echo $load; ?>";
                // alert(load_id);
                $.ajax({
                    url:"processing/curd_loads.php",
                    method:"POST",
                    data:{driver_data:driver_data, search_driver_id: search_driver_id, search_driver_num: search_driver_num, load_id: load_id},
                    success:function(data){
                        $('.filter_drivers').html(data);
                    }
                });
            }

            function get_driver_key()
            {
                return $('.search_driver_id').val();
            }

            function get_driver_num()
            {
                return $('.search_driver_num').val();
                
            }

            $('.common_selector').on('keyup change',function(){
                filter_driver_data();
                get_driver_key();
                get_driver_num();
            });








            filter_bid_data();
        
            function filter_bid_data()
            {
                var bidding_data = 'fetch_data';
                var owners = get_filter('owners');
                var drivers = get_filter('drivers');
                var nothing = get_filter('nothing');
                var load_id = "<?php echo $load; ?>";
                $.ajax({
                    url:"processing/curd_loads.php",
                    method:"POST",
                    data:{bidding_data:bidding_data, owners:owners, drivers:drivers, nothing:nothing, load_id: load_id},
                    success:function(data){
                        $('.filter_bid_data').html(data);
                    }
                });
            }
            
            function get_filter(class_name)
            {
                var filter = [];
                $('.'+class_name+':checked').each(function(){
                    filter.push($(this).val());
                });
                return filter;
            }

            $('#refresh_btn').on('click',function(){
                filter_bid_data();
            });

            $('.common_selector').on('keyup change',function(){
                filter_bid_data();
            });

            
            setInterval(function()
            {
                filter_bid_data(); 
            }, 5000);
            
            $(".loads").addClass("active");
        });
    </script>
</body>
</html>