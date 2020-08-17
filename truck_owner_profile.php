<?php
    include('session.php');
    include('layout.php');
    $owner = $_GET['owner_id'];

    $sql = "select * from truck_owners where to_id = '$owner'";
    $g_sql = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($g_sql, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Truck Owner Profile | Truck Wale</title>
    <link rel="stylesheet" href="assets/css/table.css">
    <?php echo $head_tags; ?>
    <style>
        td i
        {
            font-size: 1.2rem !important;
        }
    </style>
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
                    <h1>Truck Owner Profile</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="dashboard">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="truck_owners">Truck Owners</a></div>
                        <div class="breadcrumb-item">Truck Owner Profile</div>
                    </div>
                </div>

                <div class="section-body">
                    <div class="row mt-sm-4">
                        <div class="col-12 col-md-12 col-lg-6">
                            <div class="card profile-widget">
                                <div class="profile-widget-header">
                                <img alt="image" src="assets/img/avatar/avatar-2.png" class="rounded-circle profile-widget-picture">
                                <div class="profile-widget-items">
                                    <div class="profile-widget-item">
                                        <div class="profile-widget-item-label">Email</div>
                                        <div class="profile-widget-item-value"><a href="mailto:<?php echo $row['to_email']; ?>"><?php echo $row['to_email']; ?></a></div>
                                    </div>
                                    <div class="profile-widget-item">
                                        <div class="profile-widget-item-label">Phone</div>
                                        <div class="profile-widget-item-value">+<?php echo $row['to_phone_code']." ".$row['to_phone']; ?></div>
                                    </div>
                                    <div class="profile-widget-item">
                                        <div class="profile-widget-item-label">City</div>
                                        <div class="profile-widget-item-value"><?php echo $row['to_city']; ?></div>
                                    </div>
                                </div>
                                </div>
                                <div class="profile-widget-description">
                                <div class="profile-widget-name">
                                    <h5><?php echo $row['to_name']; ?></h5>
                                </div>
                                    <b>Address : </b><?php echo $row['to_address']; ?>
                                    <br>
                                    <b>Operating Routes : </b><?php echo $row['to_routes']; ?>
                                    <br>
                                    <b>State Permits : </b><?php echo $row['to_permits']; ?>
                                    <br>
                                    <b>Bank Account Number : </b><?php echo $row['to_bank']; ?>
                                    &nbsp;&nbsp;&nbsp;
                                    <b>IFSC Code : </b><?php echo $row['to_ifsc']; ?>
                                    <br>
                                    <b>PAN Number : </b><?php echo $row['to_pan']; ?>
                                </div>
                                <div class="card-footer text-center">
                                    <div class="font-weight-bold mb-2">
                                        <b>Registered On :</b> <?php $date = date_create($row['to_registered']);
                                            $date = date_format($date, "d M, Y");
                                            echo $date; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-6">
                            <div style="display: inline-flex">
                                <form class="to_status">
                                    <input type="text" name="to_id" value="'.$row['to_id'].'" hidden>
                                    <input type="text" name="to_reg" value="1" hidden>
                                    <button type="submit" class="btn btn-success btn-lg" title="Accept"><i class="fas fa-user-check"></i></button>
                                </form>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <form class="to_status">
                                    <input type="text" name="to_id" value="'.$row['to_id'].'" hidden>
                                    <input type="text" name="to_reg" value="2" hidden>
                                    <button type="submit" class="btn btn-warning btn-lg" title="Reject"><i class="fas fa-user-minus"></i></button>
                                </form>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <form class="to_status">
                                    <input type="text" name="to_id" value="'.$row['to_id'].'" hidden>
                                    <input type="text" name="to_reg" value="2" hidden>
                                    <button type="submit" class="btn btn-danger btn-lg" title="Delete User"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <h2 class="section-title">Truck Owns</h2>
                    <div class="row mt-sm-4">
                        <div class="col-12 col-md-12">
                            <table>
                                <thead>
                                    <th>Truck</th>
                                    <th>Number</th>
                                    <th>Load (in Tons)</th>
                                    <th>Driver's Name</th>
                                    <th>Driver's Phone</th>
                                    <th>Driver's License</th>
                                    <th>RC</th>
                                    <th>Insurance</th>
                                    <th>Road Tax</th>
                                    <th>RTO</th>
                                    <th>Status</th>
                                </thead>
                                <tbody>
                                    <?php
                                        $truck = "select * from trucks where trk_owner = '$owner'";
                                        $g_truck = mysqli_query($link, $truck);
                                        while($r_truck = mysqli_fetch_array($g_truck, MYSQLI_ASSOC))
                                        {
                                    ?>
                                    <tr>
                                        <td data-column="Truck">
                                            <?php
                                                $cat = "select * from truck_cat where trk_cat_id = '".$r_truck['trk_cat']."'";
                                                $g_cat = mysqli_query($link, $cat);
                                                $r_cat = mysqli_fetch_array($g_cat, MYSQLI_ASSOC);

                                                echo $r_cat['trk_cat_name'];
                                            ?>
                                        </td>
                                        <td data-column="Number"><?php echo $r_truck['trk_num']; ?></td>
                                        <td data-column="Load (in Tons)"><?php echo $r_truck['trk_load']; ?></td>
                                        <td data-column="Driver's Name"><?php echo $r_truck['trk_dr_name']; ?></td>
                                        <td data-column="Driver's Phone"><?php echo "+".$r_truck['trk_dr_phone_code']." ".$r_truck['trk_dr_phone']; ?></td>
                                        <td data-column="Driver's License">
                                            <a href="<?php echo $r_truck['trk_dr_license']; ?>" target="_blank" title="View File"><i class="fas fa-file"></i></a>
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <a href="<?php echo $r_truck['trk_dr_license']; ?>" title="Download File" download="<?php echo $r_truck['trk_num']; ?>_license"><i class="fas fa-file-download"></i></a>
                                        </td>
                                        <td data-column="RC">
                                            <a href="<?php echo $r_truck['trk_rc']; ?>" target="_blank" title="View File"><i class="fas fa-file"></i></a>
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <a href="<?php echo $r_truck['trk_rc']; ?>" title="Download File" download="<?php echo $r_truck['trk_num']; ?>_rc"><i class="fas fa-file-download"></i></a>
                                        </td>
                                        <td data-column="Insurance">
                                            <a href="<?php echo $r_truck['trk_insurance']; ?>" target="_blank" title="View File"><i class="fas fa-file"></i></a>
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <a href="<?php echo $r_truck['trk_insurance']; ?>" title="Download File" download="<?php echo $r_truck['trk_num']; ?>_insurance"><i class="fas fa-file-download"></i></a>
                                        </td>
                                        <td data-column="Road Tax">
                                            <a href="<?php echo $r_truck['trk_road_tax']; ?>" target="_blank" title="View File"><i class="fas fa-file"></i></a>
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <a href="<?php echo $r_truck['trk_road_tax']; ?>" title="Download File" download="<?php echo $r_truck['trk_num']; ?>_road_tax"><i class="fas fa-file-download"></i></a>
                                        </td>
                                        <td data-column="RTO">
                                            <a href="<?php echo $r_truck['trk_rto']; ?>" target="_blank" title="View File"><i class="fas fa-file"></i></a>
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <a href="<?php echo $r_truck['trk_rto']; ?>" title="Download File" download="<?php echo $r_truck['trk_num']; ?>_rto"><i class="fas fa-file-download"></i></a>
                                        </td>
                                        <td data-column="Status">
                                            <?php
                                                if($r_truck['trk_active'] == 1)
                                                {
                                            ?>
                                                <span class="btn btn-sm btn-success">Active</span>
                                            <?php
                                                }
                                                else
                                                {
                                            ?>
                                                <span class="btn btn-sm btn-danger">Inactive</span>
                                            <?php
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                    ?>
                                </tbody>
                            </table>
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
            $(".truck_owners").addClass("active");
        });
    
        $(".to_status").submit(function(e)
        {
            var form_data = $(this).serialize();
            // alert(form_data);
            var button_content = $(this).find("button[type=submit]");
            $.ajax({
                url: "processing/curd_truck_owners.php",
                data: form_data,
                type: "POST",
                success: function(data)
                {
                    alert(data);
                    if(data === "Truck Owner Accepted" || data === "Truck Owner Rejected")
                    {
                        $( "#refresh_btn" ).trigger( "click" );
                    }
                }
            });
            e.preventDefault();
        });
    </script>
</body>
</html>