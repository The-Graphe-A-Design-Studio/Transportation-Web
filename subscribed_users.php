<?php
    include('session.php');
    include('layout.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Subscribed Users | Truk Wale</title>
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
                    <h1>Subscribed Users</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="dashboard">Dashboard</a></div>
                        <div class="breadcrumb-item">Subscribed Users</div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="custom-switches-stacked mt-2" style="flex-direction: row">
                                <div class="form-group" style="margin-bottom: 0 !important;">
                                    <label class="custom-switch">
                                        <input type="radio" name="option" class="custom-switch-input common_selector nothing" value="1" checked>
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">All</span>
                                    </label>
                                    <label class="custom-switch">
                                        <input type="radio" name="option" class="custom-switch-input common_selector active" value="2">
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">Shipper</span>
                                    </label>
                                    <label class="custom-switch">
                                        <input type="radio" name="option" class="custom-switch-input common_selector inactive" value="3">
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">Truck Owners</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-4">
                            <div class="form-group" style="margin-bottom: 0 !important;">
                                <label>Search by Order ID</label>
                                <input class="form-control common_selector search_bar" placeholder="Search by Order ID" name="name"/>
                            </div>
                        </div>
                        
                    </div>
                    
                    <div class="row mt-sm-4 filter_data" style="padding: 3vh 0 2vh 0">
                        
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
                var branch = branchw();
                var active = get_filter('active');
                var inactive = get_filter('inactive');
                var nothing = get_filter('nothing');
                $.ajax({
                    url:"processing/curd_subscribed_users.php",
                    method:"POST",
                    data:{action:action, branch:branch, active:active, inactive:inactive, nothing:nothing},
                    success:function(data){
                        $('.filter_data').html(data);
                    }
                });
            }

            function branchw()
            {
                return $('.branch').find('option:selected').val();
            }
            
            function get_filter(class_name)
            {
                var filter = [];
                $('.'+class_name+':checked').each(function(){
                    filter.push($(this).val());
                });
                return filter;
            }

            $('#refresh_btn').on('click',function(){
                filter_data();
            });

            $('.common_selector').on('keyup change',function(){
                filter_data();
                branchw();
            });

            $(".subscribed").addClass("active");
        });

        $(".plan_form").submit(function(e)
		{
			var form_data = $(this).serialize();
			// alert(form_data);
			var button_content = $(this).find('button[type=submit]');
			button_content.addClass("disabled btn-progress");
            $.ajax({
				url: 'processing/curd_subscribed_users.php',
				data: form_data,
				type: 'POST',
				success: function(data)
				{
                    alert(data);
                    button_content.removeClass("disabled btn-progress");
					if(data === "New Plan Created")
					{
						location.href="subscription_plans";
					}
				}
			});
			e.preventDefault();
		});
    </script>
</body>
</html>