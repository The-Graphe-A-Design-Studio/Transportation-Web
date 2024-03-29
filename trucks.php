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
                    <h1>Trucks</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="dashboard">Dashboard</a></div>
                        <div class="breadcrumb-item">Trucks</div>
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
                                        <input type="radio" name="option" class="custom-switch-input common_selector active" value="2">
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">Active</span>
                                    </label>
                                    <label class="custom-switch">
                                        <input type="radio" name="option" class="custom-switch-input common_selector inactive" value="3">
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">Inactive</span>
                                    </label>
                                    <label class="custom-switch">
                                        <input type="radio" name="option" class="custom-switch-input common_selector not_verified" value="3">
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">Docs not verified</span>
                                    </label>
                                    <label class="custom-switch">
                                        <input type="radio" name="option" class="custom-switch-input common_selector trip" value="4">
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">On Trip</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="row">
                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                        <label>Search by Truck's Number</label>
                                        <input class="form-control common_selector search_bar" placeholder="Search by Truck's Number" name="name"/>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                        <label>Search by Truck's Driver Mobile</label>
                                        <input class="form-control common_selector search_driver" placeholder="Search by Truck's Driver Mobile" name="name"/>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                        <label>Category</label>
                                        <select class="form-control common_selector city">
                                            <option value="">All</option>
                                            <?php
                                                $branch = "select * from truck_cat";
                                                $get_branch = mysqli_query($link, $branch);
                                                while($row_branch = mysqli_fetch_array($get_branch, MYSQLI_ASSOC))
                                                {
                                            ?>
                                                <option value="<?php echo $row_branch['trk_cat_id']; ?>"><?php echo $row_branch['trk_cat_name']; ?></option>
                                            <?php } ?>
                                        </select>
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
                var not_verified = get_filter('not_verified');
                var trip = get_filter('trip');
                var search = get_key('search_bar');
                var driver = get_driver('search_driver');
                var city = branchw();
                $.ajax({
                    url:"processing/curd_trucks.php",
                    method:"POST",
                    data:{action:action, active:active, inactive:inactive, not_verified:not_verified, nothing:nothing, trip:trip, search:search, driver:driver, city:city},
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

            function get_driver()
            {
                return $('.search_driver').val();
            }

            function branchw()
            {
                return $('.city').find('option:selected').val();
            }
            
            $('#refresh_btn').on('click',function(){
                filter_data();
            });

            $('.common_selector').on('keyup change',function(){
                filter_data();
                get_key();
                branchw();
                get_driver();
            });


            $(".trucks").addClass("active");            
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.panzoom/2.0.6/jquery.panzoom.min.js"></script>
</body>
</html>