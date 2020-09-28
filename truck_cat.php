<?php
    include('session.php');
    include('layout.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Truck Categories | Truck Wale</title>
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
                    <h1>Truck Categories</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="dashboard">Dashboard</a></div>
                        <div class="breadcrumb-item">Truck Categories</div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row mt-sm-4">
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="section-title">Add New Category</div>
                            <form class="cat_form">
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" name="newcatName" placeholder="Enter Category Name" required>
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit">Add</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="section-title">Existing Categories</div>
                    <div class="row mt-sm-4">
                        <?php
                            $category = "select * from truck_cat order by trk_cat_name asc";
                            $get_category = mysqli_query($link, $category);
                            while($row_category = mysqli_fetch_array($get_category, MYSQLI_ASSOC))
                            {
                                $categories[] = $row_category;
                            }
                            foreach($categories as $cat)
                            {
                        ?>
                            <div class="col-12 col-md-4 col-lg-3">
                                <div class="card profile-widget services-widget">
                                    <div class="profile-widget-description" data-toggle="collapse" data-target="#collapse<?php echo $cat['trk_cat_id']; ?>" 
                                        aria-expanded="true" aria-controls="collapse<?php echo $cat['trk_cat_id']; ?>" style="cursor: pointer">
                                        <div class="profile-widget-name" style="margin-bottom: 0 !important">
                                            <?php echo $cat['trk_cat_name']; ?>
                                            <i class="fas fa-caret-down" style="float: right"></i>
                                        </div>
                                    </div>
                                    <div class="collapse" id="collapse<?php echo $cat['trk_cat_id']; ?>" style="">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card" style="box-shadow: none !important; margin-bottom: 0 !important">
                                                    <div class="card-header">
                                                        <h4>Services</h4>
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <div class="table-responsive my-table-responsive">
                                                            <table class="table">
                                                                <?php
                                                                    $i = 1;
                                                                    $gets = "select * from truck_cat_type where ty_cat = '".$cat['trk_cat_id']."' order by ty_name";
                                                                    $run_gets = mysqli_query($link, $gets);
                                                                    $counts = mysqli_num_rows($run_gets);
                                                                    if($counts >= 1)
                                                                    {
                                                                        while($row_gets = mysqli_fetch_array($run_gets, MYSQLI_ASSOC))
                                                                        {
                                                                ?>
                                                                <tr>
                                                                    <td class="text-center"><?php echo $i; ?></td>
                                                                    <td class="text-left">
                                                                        <?php echo $row_gets['ty_name']; ?>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                                            $i++;
                                                                        }
                                                                    }
                                                                    else
                                                                    {
                                                                ?>
                                                                    <tr>
                                                                        <td class="text-center">
                                                                            No Truck Types Found
                                                                        </td>
                                                                    </tr>
                                                                <?php
                                                                    }
                                                                ?>
                                                                <tr>
                                                                    <td class="text-center" colspan="2">
                                                                        <a href="truck_cat_edit?trk_cat_id=<?php echo $cat['trk_cat_id']; ?>&trk_cat_name=<?php echo $cat['trk_cat_name']; ?>">
                                                                            <div class="btn-group mb-3 btn-group-sm" role="group" aria-label="Basic example">
                                                                                <button type="button" class="btn btn-primary btn-lg" title="Add Service"><i class="fas fa-folder-plus"></i></button>
                                                                                <button type="button" class="btn btn-primary btn-lg" title="Edit Service"><i class="fas fa-edit"></i></button>
                                                                                <button type="button" class="btn btn-primary btn-lg" title="Delete Service"><i class="fas fa-trash"></i></button>
                                                                            </div>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <form class="card-footer cat_form text-center" title="Delete this Category">
                                                        <input type="text" name="cat_delete_id" value="<?php echo $cat['trk_cat_id']; ?>" hidden>
                                                        <button class="btn btn-danger" type="submit">
                                                            Delete this Category
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
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
        $(".cat_form").submit(function(e)
		{
			var form_data = $(this).serialize();
			// alert(form_data);
			var button_content = $(this).find('button[type=submit]');
			button_content.addClass("disabled btn-progress");
            $.ajax({
				url: 'processing/curd_truck_cat.php',
				data: form_data,
				type: 'POST',
				success: function(data)
				{
                    alert(data);
                    button_content.removeClass("disabled btn-progress");
					if(data === "New Category created" || data === "Category Deleted")
					{
						location.href="truck_cat";
					}
				}
			});
			e.preventDefault();
		});

        $(document).ready(function()
        {
            $(".truck_cat").addClass("active");
        });
    </script>
</body>
</html>