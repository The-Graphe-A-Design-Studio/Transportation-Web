<?php
    include('session.php');
    include('layout.php');
    include('FCM/notification.php');
    
    $truck = $_GET['truck_id'];

    $sql = "select * from trucks where trk_id = '$truck'";
    $g_sql = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($g_sql, MYSQLI_ASSOC);

    $owner = "select * from truck_owners where to_id = '".$row['trk_owner']."'";
    $g_owner = mysqli_query($link, $owner);
    $row_owner = mysqli_fetch_array($g_owner, MYSQLI_ASSOC);

    $count = "select count(*) from trucks where trk_owner = '".$row['trk_owner']."'";
    $count_get = mysqli_query($link, $count);
    $count_truck = mysqli_fetch_array($count_get, MYSQLI_ASSOC);

    if($count_truck['count(*)'] == 0)
    {
        $truck_count = "Not added";
    }
    else
    {
        $truck_count = $count_truck['count(*)'];
    }

    $cat = "select * from truck_cat where trk_cat_id = '".$row['trk_cat']."'";
    $g_cat = mysqli_query($link, $cat);
    $row_cat = mysqli_fetch_array($g_cat, MYSQLI_ASSOC);

    $type = "select * from truck_cat_type where ty_id = '".$row['trk_cat_type']."'";
    $g_type = mysqli_query($link, $type);
    $row_type = mysqli_fetch_array($g_type, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Truck Profile | Truck Wale</title>
    <link rel="stylesheet" href="assets/css/table.css">
    <?php echo $head_tags; ?>
    <style>
        td i
        {
            font-size: 1.2rem !important;
        }

        .card .card-body .truck-info
        {
            text-align: left !important;
            padding-left: 1vh;
        }

        .card .card-body p img
        {
            max-height: 80px;
        }

        .card-header, .card-footer
        {
            min-height: 0 !important;
            padding: 1vh 2vh !important;
        }

        .card-body
        {
            padding: 1vh 0 0 0 !important;
        }
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
                    <h1>Truck Profile ID <?php echo $row['trk_id']; ?></h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="dashboard">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="trucks">Trucks</a></div>
                        <div class="breadcrumb-item">Truck Profile</div>
                    </div>
                </div>

                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="card card-success">
                                <div class="card-header">
                                    <h4>Truck Owner</h4>
                                </div>
                                <div class="card-body">
                                    <p class="truck-info">
                                        <b>Name : </b><a href="truck_owner_profile?owner_id=<?php echo $row_owner['to_id']; ?>"><?php echo $row_owner['to_name']; ?></a>
                                        <br>
                                        <b>Phone : </b><?php echo '+'.$row_owner['to_phone_code'].' '.$row_owner['to_phone']; ?>
                                        <br>
                                        <b>Total Trucks : </b><?php echo $truck_count; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="card card-success">
                                <div class="card-header">
                                    <h4>Truck Details</h4>
                                </div>
                                <div class="card-body">
                                    <p class="truck-info">
                                        <b>Number : </b><?php echo $row['trk_num']; ?>
                                        <br>
                                        <b>Category & Type: </b><?php echo $row_cat['trk_cat_name'].', '.$row_type['ty_name']; ?>
                                        <br>
                                        <b>Driver's Name : </b><?php echo $row['trk_dr_name']; ?>
                                        <br>
                                        <b>Driver's Phone : </b><?php echo '+'.$row['trk_dr_phone_code'].' '.$row['trk_dr_phone']; ?>
                                    </p>
                                </div>
                            </div>
                        </div>                   
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="card card-success">
                                <div class="card-header">
                                    <h4>Driver's Selfie</h4>
                                </div>
                                <div class="card-body text-center">
                                    <p>
                                        <img alt="driver_selfie_<?php echo $row['trk_dr_phone']; ?>" src="<?php echo $row['trk_dr_pic']; ?>">
                                    </p>
                                </div>
                                <div class="card-footer text-center">
                                    <div class="buttons" style="display: inline-flex">
                                        <button class="btn btn-icon btn-info" data-toggle="modal" title="View" data-target="#selfie<?php echo $row['trk_id']; ?>"><i class="fas fa-eye"></i></button>
                                        <!-- Modal -->
                                        <div class="mymodal modal fade" id="selfie<?php echo $row['trk_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background: rgba(0, 0, 0, 0.78) none repeat scroll 0% 0%">
                                            <div class="modal-dialog" role="document" style="pointer-events: unset; max-width: unset;">
                                                <section>
                                                    <div class="panzoom" style="text-align: center">
                                                    <img src="<?php echo $row['trk_dr_pic']; ?>" style="max-width: 100%" alt="driver_selfie_<?php echo $row['trk_dr_phone']; ?>">
                                                    </div>
                                                </section>
                                                <section class="buttons" style="margin-top: 2vh;">
                                                    <button class="zoom-in btn btn-icon btn-info" title="Zoom In"><i class="fas fa-search-plus" style="line-height: unset !important"></i></button>
                                                    <button class="zoom-out btn btn-icon btn-info" title="Zoom Out"><i class="fas fa-search-minus" style="line-height: unset !important"></i></button>
                                                    <!-- <input type="range" class="zoom-range"> -->
                                                    <button class="reset btn btn-icon btn-info" title="Reset"><i class="fas fa-redo" style="line-height: unset !important"></i></button>
                                                </section>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="card card-success">
                                <div class="card-header">
                                    <h4>Driver's License</h4>
                                </div>
                                <div class="card-body text-center">
                                    <p>
                                        <img alt="driver_license_<?php echo $row['trk_dr_license']; ?>" src="<?php echo $row['trk_dr_license']; ?>">
                                    </p>
                                </div>
                                <div class="card-footer text-center">
                                    <div class="buttons" style="display: inline-flex">
                                        <button class="btn btn-icon btn-info" data-toggle="modal" title="View" data-target="#pan<?php echo $row['trk_id']; ?>"><i class="fas fa-eye"></i></button>
                                        <!-- Modal -->
                                        <div class="mymodal modal fade" id="pan<?php echo $row['trk_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background: rgba(0, 0, 0, 0.78) none repeat scroll 0% 0%">
                                            <div class="modal-dialog" role="document" style="pointer-events: unset; max-width: unset;">
                                                <section>
                                                    <div class="panzoom" style="text-align: center">
                                                    <img src="<?php echo $row['trk_dr_license']; ?>" style="max-width: 100%" alt="driver_license_<?php echo $row['trk_dr_phone']; ?>">
                                                    </div>
                                                </section>
                                                <section class="buttons" style="margin-top: 2vh;">
                                                    <button class="zoom-in btn btn-icon btn-info" title="Zoom In"><i class="fas fa-search-plus" style="line-height: unset !important"></i></button>
                                                    <button class="zoom-out btn btn-icon btn-info" title="Zoom Out"><i class="fas fa-search-minus" style="line-height: unset !important"></i></button>
                                                    <!-- <input type="range" class="zoom-range"> -->
                                                    <button class="reset btn btn-icon btn-info" title="Reset"><i class="fas fa-redo" style="line-height: unset !important"></i></button>
                                                </section>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="card card-success">
                                <div class="card-header">
                                    <h4>Truck RC</h4>
                                </div>
                                <div class="card-body text-center">
                                    <p>
                                        <img alt="truck_rc_<?php echo $row['trk_num']; ?>" src="<?php echo $row['trk_rc']; ?>">
                                    </p>
                                </div>
                                <div class="card-footer text-center">
                                    <div class="buttons" style="display: inline-flex">
                                        <button class="btn btn-icon btn-info" data-toggle="modal" title="View" data-target="#rc<?php echo $row['trk_id']; ?>"><i class="fas fa-eye"></i></button>
                                        <!-- Modal -->
                                        <div class="mymodal modal fade" id="rc<?php echo $row['trk_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background: rgba(0, 0, 0, 0.78) none repeat scroll 0% 0%">
                                            <div class="modal-dialog" role="document" style="pointer-events: unset; max-width: unset;">
                                                <section>
                                                    <div class="panzoom" style="text-align: center">
                                                    <img src="<?php echo $row['trk_rc']; ?>" style="max-width: 100%" alt="truck_rc_<?php echo $row['trk_num']; ?>">
                                                    </div>
                                                </section>
                                                <section class="buttons" style="margin-top: 2vh;">
                                                    <button class="zoom-in btn btn-icon btn-info" title="Zoom In"><i class="fas fa-search-plus" style="line-height: unset !important"></i></button>
                                                    <button class="zoom-out btn btn-icon btn-info" title="Zoom Out"><i class="fas fa-search-minus" style="line-height: unset !important"></i></button>
                                                    <!-- <input type="range" class="zoom-range"> -->
                                                    <button class="reset btn btn-icon btn-info" title="Reset"><i class="fas fa-redo" style="line-height: unset !important"></i></button>
                                                </section>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="card card-success">
                                <div class="card-header">
                                    <h4>Truck Road Tax Certificate</h4>
                                </div>
                                <div class="card-body text-center">
                                    <p>
                                        <img alt="truck_road_tax_<?php echo $row['trk_num']; ?>" src="<?php echo $row['trk_road_tax']; ?>">
                                    </p>
                                </div>
                                <div class="card-footer text-center">
                                    <div class="buttons" style="display: inline-flex">
                                        <button class="btn btn-icon btn-info" data-toggle="modal" title="View" data-target="#road_tax<?php echo $row['trk_id']; ?>"><i class="fas fa-eye"></i></button>
                                        <!-- Modal -->
                                        <div class="mymodal modal fade" id="road_tax<?php echo $row['trk_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background: rgba(0, 0, 0, 0.78) none repeat scroll 0% 0%">
                                            <div class="modal-dialog" role="document" style="pointer-events: unset; max-width: unset;">
                                                <section>
                                                    <div class="panzoom" style="text-align: center">
                                                    <img src="<?php echo $row['trk_road_tax']; ?>" style="max-width: 100%" alt="truck_road_tax_<?php echo $row['trk_num']; ?>">
                                                    </div>
                                                </section>
                                                <section class="buttons" style="margin-top: 2vh;">
                                                    <button class="zoom-in btn btn-icon btn-info" title="Zoom In"><i class="fas fa-search-plus" style="line-height: unset !important"></i></button>
                                                    <button class="zoom-out btn btn-icon btn-info" title="Zoom Out"><i class="fas fa-search-minus" style="line-height: unset !important"></i></button>
                                                    <!-- <input type="range" class="zoom-range"> -->
                                                    <button class="reset btn btn-icon btn-info" title="Reset"><i class="fas fa-redo" style="line-height: unset !important"></i></button>
                                                </section>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="card card-success">
                                <div class="card-header">
                                    <h4>Truck Insurance</h4>
                                </div>
                                <div class="card-body text-center">
                                    <p>
                                        <img alt="truck_insurance_<?php echo $row['trk_num']; ?>" src="<?php echo $row['trk_insurance']; ?>">
                                    </p>
                                </div>
                                <div class="card-footer text-center">
                                    <div class="buttons" style="display: inline-flex">
                                        <button class="btn btn-icon btn-info" data-toggle="modal" title="View" data-target="#insurance<?php echo $row['trk_id']; ?>"><i class="fas fa-eye"></i></button>
                                        <!-- Modal -->
                                        <div class="mymodal modal fade" id="insurance<?php echo $row['trk_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background: rgba(0, 0, 0, 0.78) none repeat scroll 0% 0%">
                                            <div class="modal-dialog" role="document" style="pointer-events: unset; max-width: unset;">
                                                <section>
                                                    <div class="panzoom" style="text-align: center">
                                                    <img src="<?php echo $row['trk_insurance']; ?>" style="max-width: 100%" alt="truck_insurance_<?php echo $row['trk_num']; ?>">
                                                    </div>
                                                </section>
                                                <section class="buttons" style="margin-top: 2vh;">
                                                    <button class="zoom-in btn btn-icon btn-info" title="Zoom In"><i class="fas fa-search-plus" style="line-height: unset !important"></i></button>
                                                    <button class="zoom-out btn btn-icon btn-info" title="Zoom Out"><i class="fas fa-search-minus" style="line-height: unset !important"></i></button>
                                                    <!-- <input type="range" class="zoom-range"> -->
                                                    <button class="reset btn btn-icon btn-info" title="Reset"><i class="fas fa-redo" style="line-height: unset !important"></i></button>
                                                </section>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="card card-success">
                                <div class="card-header">
                                    <h4>Truck RTO Passing</h4>
                                </div>
                                <div class="card-body text-center">
                                    <p>
                                        <img alt="truck_rto_<?php echo $row['trk_num']; ?>" src="<?php echo $row['trk_rto']; ?>">
                                    </p>
                                </div>
                                <div class="card-footer text-center">
                                    <div class="buttons" style="display: inline-flex">
                                        <button class="btn btn-icon btn-info" data-toggle="modal" title="View" data-target="#rto<?php echo $row['trk_id']; ?>"><i class="fas fa-eye"></i></button>
                                        <!-- Modal -->
                                        <div class="mymodal modal fade" id="rto<?php echo $row['trk_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background: rgba(0, 0, 0, 0.78) none repeat scroll 0% 0%">
                                            <div class="modal-dialog" role="document" style="pointer-events: unset; max-width: unset;">
                                                <section>
                                                    <div class="panzoom" style="text-align: center">
                                                    <img src="<?php echo $row['trk_rto']; ?>" style="max-width: 100%" alt="truck_rto_<?php echo $row['trk_num']; ?>">
                                                    </div>
                                                </section>
                                                <section class="buttons" style="margin-top: 2vh;">
                                                    <button class="zoom-in btn btn-icon btn-info" title="Zoom In"><i class="fas fa-search-plus" style="line-height: unset !important"></i></button>
                                                    <button class="zoom-out btn btn-icon btn-info" title="Zoom Out"><i class="fas fa-search-minus" style="line-height: unset !important"></i></button>
                                                    <!-- <input type="range" class="zoom-range"> -->
                                                    <button class="reset btn btn-icon btn-info" title="Reset"><i class="fas fa-redo" style="line-height: unset !important"></i></button>
                                                </section>
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
        $(document).ready(function(){
            $(".trucks").addClass("active");

            $(".panzoom").panzoom({
                $zoomIn: $(".zoom-in"),
                $zoomOut: $(".zoom-out"),
                $zoomRange: $(".zoom-range"),
                $reset: $(".reset"),
                
                contain: 'invert',
            });
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.panzoom/2.0.6/jquery.panzoom.min.js"></script>
</body>
</html>