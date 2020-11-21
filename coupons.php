<?php
    include('session.php');
    include('layout.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Valid Coupons | Truk Wale</title>
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
                    <h1>Valid Coupons</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="dashboard">Dashboard</a></div>
                        <div class="breadcrumb-item">Valid Coupons</div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-md-4">
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
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label>Coupon Type</label>
                                <select class="form-control common_selector branch">
                                    <option value="">All</option>
                                    <option value="1">Shippers Plans</option>
                                    <option value="2">Truck Owner Plans</option>
                                    <option value="3">Add on Truck Plans</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-4 text-center">
                            <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#exampleModal">Add new Coupon</button>
                        
                            <!-- Modal -->
                            <div class="mymodal modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background: rgba(0, 0, 0, 0.78) none repeat scroll 0% 0%">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content" style="margin-top: 10vh">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Add new Coupon</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form class="coupon_form text-left">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="couponName">Coupon Name/Code</label>
                                                    <input type="text" class="form-control" name="newcouponName" placeholder="Enter Coupon Name (Ex - TRUCK30)" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="couponType">Coupon Type</label>
                                                    <select class="form-control" name="newcouponType">
                                                        <option value="1">Shipper Plans</option>
                                                        <option value="2">Truck Owner Plans</option>
                                                        <option value="3">Add on Truck Plans</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="couponDiscount">Coupon Discount</label>
                                                    <input type="text" class="form-control" name="newcouponDiscount" placeholder="Enter Discount upto (Ex - 20%, 40%, etc...)" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="couponExpireDate">Coupon Expire Date</label>
                                                    <input type="date" class="form-control" name="newcouponExpire" placeholder="Enter Coupon Expire Date" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="button" id="refresh_btn" value="Refresh" hidden>
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
                    url:"processing/curd_coupons.php",
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

            $(".coupons").addClass("active");
        });

        $(".coupon_form").submit(function(e)
		{
			var form_data = $(this).serialize();
			// alert(form_data);
			var button_content = $(this).find('button[type=submit]');
			button_content.addClass("disabled btn-progress");
            $.ajax({
				url: 'processing/curd_coupons.php',
				data: form_data,
				type: 'POST',
				success: function(data)
				{
                    alert(data);
                    button_content.removeClass("disabled btn-progress");
					if(data === "New Coupon Created")
					{
						location.href="coupons";
					}
				}
			});
			e.preventDefault();
		});
    </script>
</body>
</html>