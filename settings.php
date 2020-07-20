<?php
    include('session.php');
    include('layout.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Dashboard | diva lounge spa</title>
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
                    <h1>Settings</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="dashboard">Dashboard</a></div>
                        <div class="breadcrumb-item">Settings</div>
                    </div>
                </div>

                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="card">
                                <form class="setting-admin">
                                    <div class="card-header">
                                        <h4>Change Username</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-0">
                                            <label>Name</label>
                                            <input type="text" name="name" class="form-control" value="<?php echo $admin_name_session; ?>" required>
                                        </div>
                                        <br>
                                        <div class="form-group mb-0">
                                            <label>Username</label>
                                            <input type="text" name="username" class="form-control" value="<?php echo $admin_username_session; ?>" required>
                                        </div>
                                    </div>
                                    <div class="card-footer text-right">
                                        <button class="btn btn-primary mr-1" type="submit">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="card">
                                <form class="setting-admin">
                                    <div class="card-header">
                                        <h4>Change Password</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-0">
                                            <label>Old Password</label>
                                            <input type="text" name="old_pass" class="form-control" required>
                                        </div>
                                        <br>
                                        <div class="form-group mb-0">
                                            <label>New Password</label>
                                            <input type="text" name="pass" class="form-control" required>
                                        </div>
                                        <br>
                                        <div class="form-group mb-0">
                                            <label>Confirm Password</label>
                                            <input type="text" name="cnf_pass" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="card-footer text-right">
                                        <button class="btn btn-primary mr-1" type="submit">Submit</button>
                                    </div>
                                </form>
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
        $(".setting-admin").submit(function(e)
		{
			var form_data = $(this).serialize();
			// alert(form_data);
			var button_content = $(this).find('button[type=submit]');
			button_content.addClass("disabled btn-progress");
            $.ajax({
				url: 'processing/curd_setting.php',
				data: form_data,
				type: 'POST',
				success: function(data)
				{
                    alert(data);
                    button_content.removeClass("disabled btn-progress");
					if(data === "Admin details updated" || data === "Password updated")
					{
						location.href="logout";
					}
				}
			});
			e.preventDefault();
		});

    </script>

</body>
</html>