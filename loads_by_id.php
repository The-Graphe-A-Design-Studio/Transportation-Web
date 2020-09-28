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
                                <div class="card-body text-right">
                                    <div class="buttons">
                                        <?php
                                            if($row['or_status'] == 2 && $row['or_shipper_on'] == 1)
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
                                            elseif($row['or_status'] == 1 && $row['or_shipper_on'] == 1)
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
                                <div class="card-body">
                                    <ul class="nav nav-tabs" id="loadTab" role="tablist" style="margin-bottom: 2vh">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="load-details" data-toggle="tab" href="#load_details" role="tab" aria-controls="details" aria-selected="true">Load Details</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="load-bidding" data-toggle="tab" href="#load_bidding" role="tab" aria-controls="bidding" aria-selected="false">Bidding</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="myTab3Content">
                                        <div class="tab-pane fade show active" id="load_details" role="tabpanel" aria-labelledby="load-details">
                                            <div class="row">
                                                <div class="col-12 col-lg-4">
                                                    <div class="card profile-widget services-widget">
                                                        <div class="profile-widget-description">
                                                            <div class="profile-widget-name text-center" style="margin-bottom: 0 !important">
                                                                <b>Active on - </b><?php echo date_format(date_create($row['or_active_on']), 'd M, Y h:i A'); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-4">
                                                    <div class="card profile-widget services-widget">
                                                        <div class="profile-widget-description">
                                                            <div class="profile-widget-name text-center" style="margin-bottom: 0 !important">
                                                                <b>Expire on - </b><?php echo date_format(date_create($row['or_expire_on']), 'd M, Y h:i A'); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-4">
                                                    <div class="card profile-widget services-widget">
                                                        <div class="profile-widget-description">
                                                            <div class="profile-widget-name text-center" style="margin-bottom: 0 !important">
                                                                <b>Contact Person - </b><?php echo $row['or_contact_person_name'].", ".$row['or_contact_person_phone']; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <?php
                                                    if($row['or_shipper_on'] == 1)
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
                                                                    }
                                                                    elseif($row['or_payment_mode'] == 2)
                                                                    {
                                                                        echo "Advance Pay ( ".$row['or_advance_pay']."% )";
                                                                    }
                                                                    else
                                                                    {
                                                                        echo "Full pay after unloading";
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
                                                                <b>Expected Price - </b>
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
                                                            <div class="custom-switches-stacked mt-2" style="flex-direction: row">
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
                                                            </div>
                                                            <div class="filter_bid_data"></div>
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