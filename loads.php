<?php
    include('session.php');
    include('layout.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Loads | Truck Wale</title>
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
                    <h1>Loads</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="dashboard">Dashboard</a></div>
                        <div class="breadcrumb-item">Loads</div>
                    </div>
                </div>

                <div class="section-body">

                    <div class="row">
                        <div class="col-sm-12 col-md-5">
                            <div class="custom-switches-stacked mt-2" style="flex-direction: row">
                                <div class="form-group" style="margin-bottom: 0 !important;">
                                    <label class="custom-switch">
                                        <input type="radio" name="option" class="custom-switch-input common_selector nothing" value="1" checked>
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description" style="color: #000; font-weight: bold; font-size: 0.8rem;">All</span>
                                    </label>
                                    <label class="custom-switch">
                                        <input type="radio" name="option" class="custom-switch-input common_selector active" value="2">
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description" style="color: #008080; font-weight: bold; font-size: 0.8rem;">Active</span>
                                    </label>
                                    <label class="custom-switch">
                                        <input type="radio" name="option" class="custom-switch-input common_selector inactive" value="3">
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description" style="color: #F426E7; font-weight: bold; font-size: 0.8rem;">On Hold</span>
                                    </label>
                                    <label class="custom-switch">
                                        <input type="radio" name="option" class="custom-switch-input common_selector going" value="3">
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description" style="color: #4169E1; font-weight: bold; font-size: 0.8rem;">On Going</span>
                                    </label>
                                    <label class="custom-switch">
                                        <input type="radio" name="option" class="custom-switch-input common_selector complete" value="3">
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description" style="color: #4B0082; font-weight: bold; font-size: 0.8rem;">Completed</span>
                                    </label>
                                    <label class="custom-switch">
                                        <input type="radio" name="option" class="custom-switch-input common_selector cancel" value="4">
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description" style="color: #FFA500; font-weight: bold; font-size: 0.8rem;">Cancelled</span>
                                    </label>
                                    <label class="custom-switch">
                                        <input type="radio" name="option" class="custom-switch-input common_selector expired" value="3">
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description" style="color: #FF0000; font-weight: bold; font-size: 0.8rem;">Expired</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="row">
                                <div class="col-12 col-xs-6 col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>Search by Load ID</label>
                                        <input class="form-control common_selector search_id" placeholder="Search by Load ID" name="id"/>
                                    </div>
                                </div>
                                <div class="col-12 col-xs-6 col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>Search by Number</label>
                                        <input class="form-control common_selector search_bar" placeholder="Search by Shipper Phone Number" name="name"/>
                                    </div>
                                </div>
                                <div class="col-12 col-xs-6 col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>Start Date</label>
                                        <input type="date" placeholder="MM/DD/YYYY" class="form-control common_selector s_date" name="start_date"/>
                                    </div>
                                </div>
                                <div class="col-12 col-xs-6 col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>End Date</label>
                                        <input type="date" placeholder="MM/DD/YYYY" class="form-control common_selector e_date" name="end_date"/>
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
                var active = get_filter('active');
                var inactive = get_filter('inactive');
                var nothing = get_filter('nothing');
                var cancel = get_filter('cancel');
                var expired = get_filter('expired');
                var going = get_filter('going');
                var complete = get_filter('complete');
                var id = get_id('search_id');
                var search = get_key('search_bar');
                var start_date = start_datee('s_date');
                var end_date = end_datee('e_date');
                $.ajax({
                    url:"processing/curd_loads.php",
                    method:"POST",
                    data:{action:action, active:active, inactive:inactive, nothing:nothing, cancel:cancel, expired:expired, going:going, complete:complete, id:id, search:search, start_date:start_date, end_date:end_date},
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

            function get_id()
            {
                return $('.search_id').val();
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
                get_id();
                start_datee();
                end_datee();
            });


            $(".loads").addClass("active");
        });
    </script>
</body>
</html>