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
    <style>
        .canvasjs-chart-credit{display: none;}
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
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="card card-statistic-2">
                            <div class="card-stats" style="margin-bottom: 0 !important">
                                <div class="card-stats-title">
                                    Loads -
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
                                    $not_ver = "select * from trucks where trk_verified = 0";
                                    $not_ver_run = mysqli_query($link, $not_ver);
                                    $count_not_ver = mysqli_num_rows($not_ver_run);

                                    $ver = "select * from trucks where trk_verified = 1";
                                    $ver_run = mysqli_query($link, $ver);
                                    $count_ver = mysqli_num_rows($ver_run);
                                ?>
                                <div class="card-stats-items" style="margin-bottom: 1vh;">
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count" id="not_verified"><?php echo $count_not_ver; ?></div>
                                        <div class="card-stats-item-label">Not Verified</div>
                                    </div>
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count" id="verified"><?php echo $count_ver; ?></div>
                                        <div class="card-stats-item-label">Verified</div>
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
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="card gradient-bottom">
                            <div class="card-header" style="line-height: 0 !important">
                                <h4>Notifications</h4>
                                <div class="custom-switches-stacked mt-2" style="flex-direction: row">
                                    <div class="form-group" style="margin-bottom: 0 !important">
                                        <label class="custom-switch">
                                            <input type="radio" name="option" class="custom-switch-input common_selectorss all_notify" checked>
                                            <span class="custom-switch-indicator"></span>
                                            <span class="custom-switch-description">All</span>
                                        </label>
                                        <label class="custom-switch">
                                            <input type="radio" name="option" class="custom-switch-input common_selectorss not_seen">
                                            <span class="custom-switch-indicator"></span>
                                            <span class="custom-switch-description">Not Seen</span>
                                        </label>
                                        <label class="custom-switch">
                                            <input type="radio" name="option" class="custom-switch-input common_selectorss seen">
                                            <span class="custom-switch-indicator"></span>
                                            <span class="custom-switch-description">Seen</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body" id="top-5-scroll" style="overflow: hidden; outline: currentcolor none medium;" tabindex="2">                                
                                <ul class="list-unstyled list-unstyled-border notification_fetch_data">
                                    
                                </ul>
                            </div>
                            <div class="card-footer pt-3 d-flex justify-content-center">
                                Older notifications will be automatically and continuously deleted after 5 days.
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="card card-statistic-2">
                            <div class="card-stats" style="margin-bottom: 0 !important">
                                <div class="card-stats-title">
                                    Load Statistics -
                                    <div class="dropdown d-inline">
                                        <select class="common_selectors chart_year" style="border: none; text-align: center;">
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
                                <div class="filter_chart_data"></div>
                                <div id="chartContainer" style="height: 370px; width: 100%;"></div>                                
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

            filter_chart_data();
        
            function filter_chart_data()
            {
                var chart_action = 'fetch_chart_data';
                var chart_year = chart_years();
                $.ajax({
                    url:"processing/curd_dashboard.php",
                    method:"POST",
                    dataType : "json",
                    data:{chart_action:chart_action, chart_year:chart_year},
                    success:function(result){
                        var chart = new CanvasJS.Chart("chartContainer", {
                            animationEnabled: true,
                            theme: "light2",
                            title:{
                                text: ""
                            },
                            axisX: {
                                valueFormatString: "MMM"
                            },
                            axisY: {
                                title: "Number of Loads",
                            },
                            data: [{
                                type: "splineArea",
                                color: "#000",
                                xValueType: "dateTime",
		                        xValueFormatString: "MMMM",
                                yValueFormatString: "#,##0",
                                dataPoints: result
                            }]
                        });                        
                        chart.render();
                    }
                });
            }

            function chart_years()
            {
                return $('.chart_year').find('option:selected').val();
            }
            
            $('.common_selectors').on('keyup change',function(){
                filter_chart_data();
                chart_years()
            });

            filter_notification_data();

            function filter_notification_data()
            {
                var notify_action = 'notification_fetch_data';
                var all_notify = get_filter('all_notify');
                var seen = get_filter('seen');
                var not_seen = get_filter('not_seen');
                $.ajax({
                    url:"processing/curd_dashboard.php",
                    method:"POST",
                    data:{notify_action:notify_action, all_notify:all_notify, seen:seen, not_seen:not_seen},
                    success:function(data){
                        $('.notification_fetch_data').html(data);
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

            $('.common_selectorss').on('keyup change',function(){
                filter_notification_data();
            });

            setInterval(function()
            {
                filter_notification_data(); 
            }, 10000);

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

            var not_verified = $("#not_verified").text();
            var verified = $("#verified").text();
            var total_trucks = parseInt(not_verified) + parseInt(verified);
            $('.total_trucks').html(total_trucks);
        });
    </script>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>