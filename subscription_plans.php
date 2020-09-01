<?php
    include('session.php');
    include('layout.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Branch Locations | diva lounge spa</title>
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
                    <h1>Branch Locations</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="dashboard">Dashboard</a></div>
                        <div class="breadcrumb-item">Branch Locations</div>
                    </div>
                </div>
                <div class="section-body text-right">
                    <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#exampleModal">Add new Branch</button>
                    
                    <!-- Modal -->
                    <div class="mymodal modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Add new Branch</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form class="branch_form text-left">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="branchName">Branch Name</label>
                                            <input type="text" class="form-control" name="newbranchName" placeholder="Enter Branch Name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="branchAddress">Branch Address</label>
                                            <textarea class="form-control" style="height: 100px;" name="newbranchAddress" placeholder="Enter Branch Address" required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="branchPhone">Branch Phone</label>
                                            <input type="text" class="form-control" name="newbranchPhone" placeholder="Enter Branch Phone number" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="branchPassword">Branch Password</label>
                                            <input type="text" class="form-control" name="newbranchPassword" placeholder="Enter Branch Password" required>
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
                <div class="section-body">
                    <div class="row mt-sm-4">
                        <?php
                            $branch = "select * from locations";
                            $get_branch = mysqli_query($link, $branch);
                            while($row_branch = mysqli_fetch_array($get_branch, MYSQLI_ASSOC))
                            {
                                $locations[] = $row_branch;
                            }
                            foreach($locations as $place)
                            {
                        ?>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="card profile-widget">
                                    <div class="profile-widget-description">
                                        <div class="profile-widget-name">
                                            <?php echo $place['l_name']; ?>
                                        </div>
                                        Branch Id - <?php echo $place['l_id']; ?>
                                        <br>
                                        <?php echo $place['l_address']; ?>
                                        <br>
                                        <?php echo $place['l_phone']; ?>
                                    </div>
                                    <div class="card-footer text-center">
                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <form class="branch_form">
                                                    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapse<?php echo $place['l_id']; ?>" aria-expanded="true" aria-controls="collapse<?php echo $place['l_id']; ?>">
                                                        Edit
                                                    </button>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                                    <input type="text" name="deletebranchId" value="<?php echo $place['l_id']; ?>" hidden>
                                                    <button type="submit" class="btn btn-icon btn-danger" title="Delete this Branch"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="collapse" id="collapse<?php echo $place['l_id']; ?>" style="">
                                            <p>
                                                <form class="branch_form">
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label for="branchName">Branch Name</label>
                                                            <input type="text" class="form-control" name="branchName" id="branchName" value="<?php echo $place['l_name']; ?>">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="branchAddress">Branch Address</label>
                                                            <textarea class="form-control" style="height: 100px;" name="branchAddress" id="branchAddress"><?php echo $place['l_address']; ?></textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="branchPhone">Branch Phone</label>
                                                            <input type="text" class="form-control" id="branchPhone" name="branchPhone" value="<?php echo $place['l_phone']; ?>">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="branchPassword">Branch Password</label>
                                                            <input type="text" class="form-control" id="branchPassword" name="branchPassword" value="<?php echo $place['l_pass']; ?>" >
                                                        </div>
                                                    </div>
                                                    <input type="text" name="branchId" value="<?php echo $place['l_id']; ?>" hidden>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapse<?php echo $place['l_id']; ?>" aria-expanded="true" aria-controls="collapse<?php echo $place['l_id']; ?>">
                                                        Cancel
                                                    </button>
                                                </form>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </section>
        </div>

        <?php echo $footer; ?>
        </div>
    </div>

    <?php echo $script_tags; ?>

    <script type="text/javascript">
        $(".branch_form").submit(function(e)
		{
			var form_data = $(this).serialize();
			// alert(form_data);
			var button_content = $(this).find('button[type=submit]');
			button_content.addClass("disabled btn-progress");
            $.ajax({
				url: 'processing/curd_location.php',
				data: form_data,
				type: 'POST',
				success: function(data)
				{
                    alert(data);
                    button_content.removeClass("disabled btn-progress");
					if(data === "Branch details updated" || data === "New branch added" || data === "Branch removed")
					{
						location.href="locations";
					}
				}
			});
			e.preventDefault();
		});

        $(document).ready(function()
        {
            $(".plans").addClass("active");
        });
    </script>
</body>
</html>