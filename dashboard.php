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
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="card card-statistic-2">
                            <div class="card-stats" style="margin-bottom: 0 !important">
                                <div class="card-stats-title">
                                    Order Statistics -
                                    <div class="dropdown d-inline">
                                        <select name="" id="" class="common_selector month" style="border: none; text-align: center;">
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
                                        <select name="" id="" class="common_selector year" style="border: none; text-align: center;">
                                            <option value="<?php echo date('Y'); ?>"><?php echo date('Y'); ?></option>
                                            <option value="<?php echo date('Y') - 4; ?>"><?php echo date('Y') - 4; ?></option>
                                            <option value="<?php echo date('Y') - 3; ?>"><?php echo date('Y') - 3; ?></option>
                                            <option value="<?php echo date('Y') - 2; ?>"><?php echo date('Y') - 2; ?></option>
                                            <option value="<?php echo date('Y') - 1; ?>"><?php echo date('Y') - 1; ?></option>
                                            <option value="<?php echo date('Y') + 1; ?>"><?php echo date('Y') + 1; ?></option>
                                            <option value="<?php echo date('Y') + 2; ?>"><?php echo date('Y') + 2; ?></option>
                                            <option value="<?php echo date('Y') + 3; ?>"><?php echo date('Y') + 3; ?></option>
                                            <option value="<?php echo date('Y') + 4; ?>"><?php echo date('Y') + 4; ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="card-stats-items filter_data">
                                    
                                </div>
                            </div>
                            <div class="card-icon shadow-primary bg-primary">
                                <i class="fas fa-archive"></i>
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
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="card card-statistic-2">
                            <div class="card-chart">
                                <canvas id="balance-chart" height="80"></canvas>
                            </div>
                            <div class="card-icon shadow-primary bg-primary">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Balance</h4>
                                </div>
                                <div class="card-body">
                                    $187,13
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="card card-statistic-2">
                            <div class="card-chart">
                                <canvas id="sales-chart" height="80"></canvas>
                            </div>
                            <div class="card-icon shadow-primary bg-primary">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Sales</h4>
                                </div>
                                <div class="card-body">
                                    4,732
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
                        var hold = $("#hold").text();
                        var going = $("#going").text();
                        var complete = $("#complete").text();
                        var total = parseInt(hold) + parseInt(going) + parseInt(complete);
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

            
        });
    </script>
</body>
</html>