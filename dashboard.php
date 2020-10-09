<?php
    include('session.php');
    include('layout.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Dashboard | Truck Wale</title>
    <?php echo $head_tags; ?>
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
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="card card-statistic-2">
                            <div class="card-stats" style="margin-bottom: 0 !important">
                                <div class="card-stats-title">
                                    Load Statistics -
                                    <div class="dropdown d-inline">
                                        <select class="common_selector month" style="border: none; text-align: center;">
                                            <option value="">Month</option>
                                            <option value="01">January</option>
                                            <option value="02">February</option>
                                            <option value="03">March</option>
                                            <option value="04">April</option>
                                            <option value="05">May</option>
                                            <option value="06">June</option>
                                            <option value="07">July</option>
                                            <option value="08">August</option>
                                            <option value="09">September</option>
                                            <option value="10">October</option>
                                            <option value="11">November</option>
                                            <option value="12">December</option>
                                        </select>
                                    </div>
                                    <div class="dropdown d-inline">
                                        <select class="common_selector year" style="border: none; text-align: center;">
                                            <option value="">Year</option>                                            
                                            <option value="2020">2020</option>
                                            <option value="2021">2021</option>
                                            <option value="2022">2022</option>
                                            <option value="2023">2023</option>
                                            <option value="2024">2024</option>
                                            <option value="2025">2025</option>
                                            <option value="2026">2026</option>
                                            <option value="2027">2027</option>
                                            <option value="2028">2028</option>
                                            <option value="2029">2029</option>
                                            <option value="2030">2030</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="filter_data">
                                
                                </div>
                            </div>
                            <div class="card-icon shadow-primary bg-primary">
                                <i class="fas fa-newspaper"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Loads</h4>
                                </div>
                                <div class="card-body total">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="card card-statistic-2">
                            <div class="card-stats" style="margin-bottom: 0 !important">
                                <div class="card-stats-title">
                                    Users
                                    <div class="dropdown d-inline"></div>
                                    <div class="dropdown d-inline"></div>
                                </div>
                                <?php
                                    $shipper = "select * from customers";
                                    $shipper_run = mysqli_query($link, $shipper);
                                    $count_shipper = mysqli_num_rows($shipper_run);

                                    $owner = "select * from truck_owners";
                                    $owner_run = mysqli_query($link, $owner);
                                    $count_owner = mysqli_num_rows($owner_run);

                                    $driver = "select * from trucks";
                                    $driver_run = mysqli_query($link, $driver);
                                    $count_driver = mysqli_num_rows($driver_run);
                                ?>
                                <div class="card-stats-items">
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count" id="shippers"><?php echo $count_shipper; ?></div>
                                        <div class="card-stats-item-label">Shippers</div>
                                    </div>
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count" id="owners"><?php echo $count_owner; ?></div>
                                        <div class="card-stats-item-label">Truck Owners</div>
                                    </div>
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count" id="drivers"><?php echo $count_driver; ?></div>
                                        <div class="card-stats-item-label">Drivers</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-icon shadow-primary bg-primary">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Users</h4>
                                </div>
                                <div class="card-body users">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="card card-statistic-2">
                            <div class="card-stats" style="margin-bottom: 0 !important">
                                <div class="card-stats-title">
                                    Subscribed Users
                                    <div class="dropdown d-inline"></div>
                                    <div class="dropdown d-inline"></div>
                                </div>
                                <?php
                                    $sub_shipper = "select * from customers where cu_account_on = 2";
                                    $sub_shipper_run = mysqli_query($link, $sub_shipper);
                                    $count_sub_shipper = mysqli_num_rows($sub_shipper_run);

                                    $sub_owner = "select * from truck_owners where to_account_on = 1";
                                    $sub_owner_run = mysqli_query($link, $sub_owner);
                                    $count_sub_owner = mysqli_num_rows($sub_owner_run);
                                ?>
                                <div class="card-stats-items">
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count" id="sub_shippers"><?php echo $count_sub_shipper; ?></div>
                                        <div class="card-stats-item-label">Shippers</div>
                                    </div>
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count" id="sub_owners"><?php echo $count_sub_owner; ?></div>
                                        <div class="card-stats-item-label">Truck Owners</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-icon shadow-primary bg-primary">
                                <i class="fas fa-hands-helping"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Subscribed Users</h4>
                                </div>
                                <div class="card-body sub_users">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="card card-statistic-2">
                            <div class="card-stats" style="margin-bottom: 0 !important">
                                <div class="card-stats-title">
                                    Trucks
                                    <div class="dropdown d-inline"></div>
                                    <div class="dropdown d-inline"></div>
                                </div>
                                <?php
                                    $active = "select * from trucks where trk_active = 1";
                                    $active_run = mysqli_query($link, $active);
                                    $count_active = mysqli_num_rows($active_run);

                                    $inactive = "select * from trucks where trk_active = 0";
                                    $inactive_run = mysqli_query($link, $inactive);
                                    $count_inactive = mysqli_num_rows($inactive_run);

                                    $trip = "select * from trucks where trk_on_trip = 1 or trk_on_trip = 2";
                                    $trip_run = mysqli_query($link, $trip);
                                    $count_trip = mysqli_num_rows($trip_run);
                                ?>
                                <div class="card-stats-items">
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count" id="active"><?php echo $count_active; ?></div>
                                        <div class="card-stats-item-label">Active</div>
                                    </div>
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count" id="inactive"><?php echo $count_inactive; ?></div>
                                        <div class="card-stats-item-label">Inactive</div>
                                    </div>
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count" id="trip"><?php echo $count_trip; ?></div>
                                        <div class="card-stats-item-label">On Trip</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-icon shadow-primary bg-primary">
                                <i class="fas fa-truck-monster"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Trucks</h4>
                                </div>
                                <div class="card-body total_trucks">
                                    
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
        $(document).ready(function()
        {
            filter_data();
        
            function filter_data()
            {
                var action = 'fetch_data';
                var month = months();
                var year = years();
                $.ajax({
                    url:"processing/curd_dashboard.php",
                    method:"POST",
                    data:{action:action, year:year, month:month},
                    success:function(data){
                        $('.filter_data').html(data);
                        var active = $("#active").text();
                        var hold = $("#hold").text();
                        var going = $("#going").text();
                        var complete = $("#complete").text();
                        var expire = $("#expire").text();
                        var cancel = $("#cancel").text();
                        var total = parseInt(hold) + parseInt(going) + parseInt(complete) + parseInt(active) + parseInt(expire) + parseInt(cancel);
                        $('.total').html(total);
                    }
                });
            }

            function months()
            {
                return $('.month').find('option:selected').val();
            }

            function years()
            {
                return $('.year').find('option:selected').val();
            }
            
            $('.common_selector').on('keyup change',function(){
                filter_data();
                months();
                years()
            });
            
            $(".dashboard").addClass("active");

            var shippers = $("#shippers").text();
            var owners = $("#owners").text();
            var drivers = $("#drivers").text();
            var total_users = parseInt(shippers) + parseInt(owners) + parseInt(drivers);
            $('.users').html(total_users);

            var sub_shippers = $("#sub_shippers").text();
            var sub_owners = $("#sub_owners").text();
            var total_sub_users = parseInt(sub_shippers) + parseInt(sub_owners);
            $('.sub_users').html(total_sub_users);

            var active = $("#active").text();
            var inactive = $("#inactive").text();
            var total_trucks = parseInt(active) + parseInt(inactive);
            $('.total_trucks').html(total_trucks);
        });
    </script>
</body>
</html>