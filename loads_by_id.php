<?php
    include('session.php');
    include('layout.php');

    $load = $_GET['load_id'];

    $sql = "select * from cust_order where or_id = '$load'";
    $g_sql = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($g_sql, MYSQLI_ASSOC);

    $truck = "select * from truck_cat where trk_cat_id = '".$row['or_truck_preference']."'";
    $g_truck = mysqli_query($link, $truck);
    $row_truck = mysqli_fetch_array($g_truck, MYSQLI_ASSOC);
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
                            <div class="card">
                                <div class="card-body">
                                    <ul class="nav nav-tabs" id="loadTab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="load-details" data-toggle="tab" href="#load_details" role="tab" aria-controls="details" aria-selected="true">Details</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="load-bidding" data-toggle="tab" href="#load_bidding" role="tab" aria-controls="bidding" aria-selected="false">Bidding</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="myTab3Content">
                                        <div class="tab-pane fade show active" id="load_details" role="tabpanel" aria-labelledby="load-details">
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
                                        </div>
                                        <div class="tab-pane fade" id="load_bidding" role="tabpanel" aria-labelledby="load-bidding">
                                            Sed sed metus vel lacus hendrerit tempus. Sed efficitur velit tortor, ac efficitur est lobortis quis. Nullam lacinia metus erat, sed fermentum justo rutrum ultrices. Proin quis iaculis tellus. Etiam ac vehicula eros, pharetra consectetur dui. Aliquam convallis neque eget tellus efficitur, eget maximus massa imperdiet. Morbi a mattis velit. Donec hendrerit venenatis justo, eget scelerisque tellus pharetra a.
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
        $(document).ready(function()
        {
            filter_data();
        
            function filter_data()
            {
                var action = 'fetch_data';
                var active = get_filter('active');
                var inactive = get_filter('inactive');
                var nothing = get_filter('nothing');
                var search = get_key('search_bar');
                var start_date = start_datee('s_date');
                var end_date = end_datee('e_date');
                $.ajax({
                    url:"processing/curd_loads.php",
                    method:"POST",
                    data:{action:action, active:active, inactive:inactive, nothing:nothing, search:search, start_date:start_date, end_date:end_date},
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


            $(".loads").addClass("active");
        });
    </script>
</body>
</html>