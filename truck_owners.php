<?php
    include('session.php');
    include('layout.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Truck Owners | Truck Wale</title>
    <link rel="stylesheet" href="assets/css/table.css">
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
                <div class="section-header">
                    <h1>Truck Owners</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="dashboard">Dashboard</a></div>
                        <div class="breadcrumb-item">Truck Owners</div>
                    </div>
                </div>

                <div class="section-body">

                    <div class="row">
                        <div class="col-sm-12 col-md-5">
                            <div class="custom-switches-stacked mt-2" style="flex-direction: row">
                                <div class="form-group">
                                    <label class="custom-switch">
                                        <input type="radio" name="option" class="custom-switch-input common_selector nothing" value="1" checked>
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">All</span>
                                    </label>
                                    <label class="custom-switch">
                                        <input type="radio" name="option" class="custom-switch-input common_selector trial" value="2">
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">On Trial</span>
                                    </label>
                                    <label class="custom-switch">
                                        <input type="radio" name="option" class="custom-switch-input common_selector subscription" value="2">
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">On Subscribtion</span>
                                    </label>
                                    <label class="custom-switch">
                                        <input type="radio" name="option" class="custom-switch-input common_selector not_verified" value="3">
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">Doc not verified</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="row">
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>Start Date</label>
                                        <input type="date" placeholder="MM/DD/YYYY" class="form-control common_selector s_date" name="start_date"/>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>End Date</label>
                                        <input type="date" placeholder="MM/DD/YYYY" class="form-control common_selector e_date" name="end_date"/>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                        <label>Search by Phone</label>
                                        <input class="form-control common_selector search_bar" placeholder="Search by Phone" name="name"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="button" id="refresh_btn" value="Refresh" hidden>
                    <div class="row mt-sm-4 filter_data">

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
                var trial = get_filter('trial');
                var subscription = get_filter('subscription');
                var not_verified = get_filter('not_verified');
                var nothing = get_filter('nothing');
                var search = get_key('search_bar');
                var start_date = start_datee('s_date');
                var end_date = end_datee('e_date');
                $.ajax({
                    url:"processing/curd_truck_owners.php",
                    method:"POST",
                    data:{action:action, trial:trial, subscription:subscription, not_verified:not_verified, nothing:nothing, search:search, start_date:start_date, end_date:end_date},
                    success:function(data){
                        $('.filter_data').html(data);
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

            function get_key()
            {
                return $('.search_bar').val();
            }

            function start_datee()
            {
                return $('.s_date').val();
            }

            function end_datee()
            {
                return $('.e_date').val();
            }

            $('#refresh_btn').on('click',function(){
                filter_data();
            });

            $('.common_selector').on('keyup change',function(){
                filter_data();
                get_key();
                start_datee();
                end_datee();
            });


            $(".truck_owners").addClass("active");
        });
    </script>
</body>
</html>