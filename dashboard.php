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
                                    Shippers
                                    <div class="dropdown d-inline"></div>
                                    <div class="dropdown d-inline"></div>
                                </div>
                                <?php
                                    $shipper1 = "select * from customers where cu_account_on = 0";
                                    $shipper_run1 = mysqli_query($link, $shipper1);
                                    $count_shipper_nothing = mysqli_num_rows($shipper_run1);

                                    $shipper2 = "select * from customers where cu_account_on = 1";
                                    $shipper_run2 = mysqli_query($link, $shipper2);
                                    $count_shipper_trial = mysqli_num_rows($shipper_run2);

                                    $shipper3 = "select * from customers where cu_account_on = 2";
                                    $shipper_run3 = mysqli_query($link, $shipper3);
                                    $count_shipper_subs = mysqli_num_rows($shipper_run3);

                                    $shipper4 = "select * from customers where cu_account_on = 3";
                                    $shipper_run4 = mysqli_query($link, $shipper4);
                                    $count_shipper_free = mysqli_num_rows($shipper_run4);
                                ?>
                                <div class="card-stats-items" style="margin-bottom: 1vh;">
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count" id="shippers_nothing"><?php echo $count_shipper_nothing; ?></div>
                                        <div class="card-stats-item-label">Not Verified</div>
                                    </div>
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count" id="shippers_free"><?php echo $count_shipper_free; ?></div>
                                        <div class="card-stats-item-label">On Free Plan</div>
                                    </div>
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count" id="shippers_trial"><?php echo $count_shipper_trial; ?></div>
                                        <div class="card-stats-item-label">On Trial</div>
                                    </div>
                                </div>
                                <div class="card-stats-items">                                    
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count" id="shippers_subs"><?php echo $count_shipper_subs; ?></div>
                                        <div class="card-stats-item-label">On Subscription</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-icon shadow-primary bg-primary">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Shippers</h4>
                                </div>
                                <div class="card-body total_shippers">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="card card-statistic-2">
                            <div class="card-stats" style="margin-bottom: 0 !important">
                                <div class="card-stats-title">
                                    Truck Owners
                                    <div class="dropdown d-inline"></div>
                                    <div class="dropdown d-inline"></div>
                                </div>
                                <?php
                                    $owner1 = "select * from truck_owners where to_account_on = 0";
                                    $owner_run1 = mysqli_query($link, $owner1);
                                    $count_owner_nothing = mysqli_num_rows($owner_run1);

                                    $owner11 = "select * from truck_owners where to_account_on = 1";
                                    $owner_run11 = mysqli_query($link, $owner11);
                                    $count_owner_trial = mysqli_num_rows($owner_run11);

                                    $owner111 = "select * from truck_owners where to_account_on = 2";
                                    $owner_run111 = mysqli_query($link, $owner111);
                                    $count_owner_subs = mysqli_num_rows($owner_run111);
                                ?>
                                <div class="card-stats-items" style="margin-bottom: 1vh;">
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count" id="owner_nothing"><?php echo $count_owner_nothing; ?></div>
                                        <div class="card-stats-item-label">Not Verified</div>
                                    </div>
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count" id="owner_trial"><?php echo $count_owner_trial; ?></div>
                                        <div class="card-stats-item-label">On Trial</div>
                                    </div>
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count" id="owner_subs"><?php echo $count_owner_subs; ?></div>
                                        <div class="card-stats-item-label">On Subscription</div>
                                    </div>
                                </div>
                                <div class="card-stats-items">
                                    
                                </div>
                            </div>
                            <div class="card-icon shadow-primary bg-primary">
                                <i class="fas fa-crown"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Truck Owners</h4>
                                </div>
                                <div class="card-body total_owners">
                                    
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
                                <div class="card-stats-items" style="margin-bottom: 1vh;">
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
                                <div class="card-stats-items"></div>
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

            var shippers_nothing = $("#shippers_nothing").text();
            var shippers_free = $("#shippers_free").text();
            var shippers_trial = $("#shippers_trial").text();
            var shippers_subs = $("#shippers_subs").text();
            var shippers = parseInt(shippers_nothing) + parseInt(shippers_free) + parseInt(shippers_trial) + parseInt(shippers_subs);
            $('.total_shippers').html(shippers);

            var owner_nothing = $("#owner_nothing").text();
            var owner_trial = $("#owner_trial").text();
            var owner_subs = $("#owner_subs").text();
            var total_owner = parseInt(owner_nothing) + parseInt(owner_trial) + parseInt(owner_subs);
            $('.total_owners').html(total_owner);

            var active = $("#active").text();
            var inactive = $("#inactive").text();
            var total_trucks = parseInt(active) + parseInt(inactive);
            $('.total_trucks').html(total_trucks);
        });
    </script>
</body>
</html>