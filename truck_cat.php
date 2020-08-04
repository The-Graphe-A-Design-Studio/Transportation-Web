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
                    <h1>Truck Categories</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="dashboard">Dashboard</a></div>
                        <div class="breadcrumb-item">Truck Categories</div>
                    </div>
                </div>

                <div class="section-body">
                    <div class="row mt-sm-4">

                        <!-- Add -->
                        <div class="col-12 col-md-4 col-lg-4">
                            <div class="card profile-widget services-widget">
                                <div class="profile-widget-description" data-toggle="collapse" data-target="#collapse_add" 
                                    aria-expanded="true" aria-controls="collapse_add" style="cursor: pointer">
                                    <div class="profile-widget-name" style="margin-bottom: 0 !important">
                                        Add New Staff
                                        <i class="fas fa-caret-down" style="float: right"></i>
                                    </div>
                                </div>
                                <div class="collapse" id="collapse_add" style="">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card" style="box-shadow: none !important; margin-bottom: 0 !important">
                                                <div class="card-body">
                                                    <form class="edit_staff">
                                                        <div class="table-responsive my-table-responsive">
                                                            <table class="table" id="mytable">
                                                                <tbody>
                                                                    <tr>
                                                                        <td>
                                                                            <input type="text" class="form-control" name="new_page_staffs[]">
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="text-center">
                                                            <span id="insert-more" class="btn btn-primary btn-icon btn-lg" title="Add new Row" style="cursor: pointer"><i class="fas fa-plus"></i></span>
                                                            <button type="submit" class="btn btn-primary btn-lg">Submit</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit -->
                        <div class="col-12 col-md-4 col-lg-4">
                            <div class="card profile-widget services-widget">
                                <div class="profile-widget-description" data-toggle="collapse" data-target="#collapse_edit" 
                                    aria-expanded="true" aria-controls="collapse_edit" style="cursor: pointer">
                                    <div class="profile-widget-name" style="margin-bottom: 0 !important">
                                        Edit Staff Details
                                        <i class="fas fa-caret-down" style="float: right"></i>
                                    </div>
                                </div>
                                <div class="collapse" id="collapse_edit" style="">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card" style="box-shadow: none !important; margin-bottom: 0 !important">
                                                <div class="card-body">
                                                    <form class="edit_staff">
                                                        <div class="table-responsive my-table-responsive">
                                                            <table class="table" id="mytable">
                                                                <tbody>
                                                                    <?php
                                                                        $i = 1;
                                                                        $get = "select * from staffs order by st_name";
                                                                        $run_get = mysqli_query($link, $get);
                                                                        $count = mysqli_num_rows($run_get);
                                                                        if($count >= 1)
                                                                        {
                                                                            while($row_get = mysqli_fetch_array($run_get, MYSQLI_ASSOC))
                                                                            {
                                                                    ?>
                                                                    <tr>
                                                                        <td>
                                                                            <input type="text" class="form-control" name="edit_staff_<?php echo $i; ?>" value="<?php echo $row_get['st_name']; ?>">
                                                                            <input type="text" name="edit_staff_id_<?php echo $i; ?>" value="<?php echo $row_get['st_id']; ?>" hidden>
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
                                                                            No Staffs found
                                                                        </td>
                                                                    </tr>
                                                                    <?php } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="text-center">
                                                            <?php
                                                                if($count >= 1)
                                                                {
                                                            ?>
                                                                <input type="text" name="edit_staff_total" value="<?php echo $i - 1; ?>" hidden>
                                                                <button type="submit" class="btn btn-primary btn-lg">Update</button>
                                                            <?php
                                                                }
                                                            ?>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Delete -->
                        <div class="col-12 col-md-4 col-lg-4">
                            <div class="card profile-widget services-widget">
                                <div class="profile-widget-description" data-toggle="collapse" data-target="#collapse_delete" 
                                    aria-expanded="true" aria-controls="collapse_delete" style="cursor: pointer">
                                    <div class="profile-widget-name" style="margin-bottom: 0 !important">
                                        Remove Staffs
                                        <i class="fas fa-caret-down" style="float: right"></i>
                                    </div>
                                </div>
                                <div class="collapse" id="collapse_delete" style="">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card" style="box-shadow: none !important; margin-bottom: 0 !important">
                                                <div class="card-body">
                                                    <div class="table-responsive my-table-responsive">
                                                        <table class="table" id="mytable">
                                                            <tbody>
                                                                <?php
                                                                    $gets = "select * from staffs order by st_name";
                                                                    $run_gets = mysqli_query($link, $gets);
                                                                    $counts = mysqli_num_rows($run_gets);
                                                                    if($counts >= 1)
                                                                    {
                                                                        while($row_gets = mysqli_fetch_array($run_gets, MYSQLI_ASSOC))
                                                                        {
                                                                ?>
                                                                <tr>
                                                                    <td>
                                                                        <form class="edit_staff">
                                                                            <?php echo $row_gets['st_name']; ?>
                                                                            <input type="text" name="delete_staff_id" value="<?php echo $row_gets['st_id']; ?>" hidden>
                                                                            <button type="submit" style="float: right" class="btn btn-danger btn-icon btn-md"><i class="fas fa-trash"></i></button>
                                                                        </form>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                                        }
                                                                    }
                                                                    else
                                                                    {
                                                                ?>
                                                                    <tr>
                                                                        <td class="text-center">
                                                                            No Staff found
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
        $(".edit_staff").submit(function(e)
		{
			var form_data = $(this).serialize();
			// alert(form_data);
			var button_content = $(this).find('button[type=submit]');
			button_content.addClass("disabled btn-progress");
            $.ajax({
				url: 'processing/curd_staff.php',
				data: form_data,
				type: 'POST',
				success: function(data)
				{
                    alert(data);
                    button_content.removeClass("disabled btn-progress");
					if(data === "New staffs updated" || data === "Staffs details updated" || data === "Staff removed")
					{
						location.href="staffs";
					}
				}
			});
			e.preventDefault();
		});

        $(document).ready(function()
        {
            $(".staffs").addClass("active");
        });
    </script>

    <script>
         $("#insert-more").click(function () {
            $("#mytable").each(function () {
                var tds = '<tr>';
                jQuery.each($('tr:last td', this), function () {
                    tds += '<td>' + $(this).html() + '</td>';
                });
                tds += '</tr>';
                if ($('tbody', this).length > 0) {
                    $('tbody', this).append(tds);
                } else {
                    $(this).append(tds);
                }
            });
        });
    </script>
    
    <script type="text/javascript">
        $(document).ready(function(){
            $(".truck_cat").addClass("active");
        });
    </script>
</body>
</html>