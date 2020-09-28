<?php

    $sql = "select * from admin where admin_id = 1";
    $run = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($run, MYSQLI_ASSOC);

    if($row['admin_toggle'] == 1)
    {
        $body = 'class="sidebar-mini"';
    }
    else
    {
        $body = '';
    }

    $head_tags =
    '
        <link rel="icon" href="assets/img/truck-logo-sm.png" type="image/gif" sizes="32x32"> 
        <!-- General CSS Files -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

        <!-- Template CSS -->
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="assets/css/components.css">
    ';

    $nav_bar =
    '
        <div class="navbar-bg"></div>
        <nav class="navbar navbar-expand-lg main-navbar">
            <form class="form-inline mr-auto">
                <ul class="navbar-nav mr-3">
                    <li><a href="#" data-toggle="sidebar" id="sidebar-switch" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
                </ul>
            </form>
            <ul class="navbar-nav navbar-right">
                <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img alt="image" src="assets/img/avatar/avatar-2.png" class="rounded-circle mr-1">
                <div class="d-sm-none d-lg-inline-block">Hi, '.$admin_name_session.'</div></a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="settings" class="dropdown-item has-icon">
                    <i class="fas fa-cog"></i> Settings
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="logout" class="dropdown-item has-icon text-danger">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
                </li>
            </ul>
        </nav>

    ';

    $side_bar =
    '
        <div class="main-sidebar">
            <aside id="sidebar-wrapper" style="margin-top: 39px">
                <div class="sidebar-brand">
                    <a href="admin">
                        <img src="assets/img/truck-logo-sm.png" alt="diva lounge spa logo" height="85">
                    </a>
                </div>
                <div class="sidebar-brand sidebar-brand-sm">
                    <a href="./">
                        <img src="assets/img/truck-logo-sm.png" alt="diva lounge spa logo" width="50">
                    </a>
                </div>
                <ul class="sidebar-menu">
                    <li class="menu-header" style="margin-bottom: 50px"></li>
                    <li class="dashboard"><a class="nav-link" href="dashboard"><i class="fas fa-fire"></i><span>Dashboard</span></a></li>
                    <li class="loads"><a class="nav-link" href="loads"><i class="fas fa-newspaper"></i><span>Loads</span></a></li>
                    <li class="shippers"><a class="nav-link" href="shippers"><i class="fas fa-users"></i><span>Shippers</span></a></li>
                    <li class="truck_owners"><a class="nav-link" href="truck_owners"><i class="fas fa-crown"></i><span>Truck Owners</span></a></li>
                    <li class="trucks"><a class="nav-link" href="trucks"><i class="fas fa-truck-monster"></i><span>Trucks</span></a></li>
                    <li class="truck_cat"><a class="nav-link" href="truck_cat"><i class="fas fa-list"></i><span>Truck Categories</span></a></li>
                    <li class="material_types"><a class="nav-link" href="material_types"><i class="fas fa-truck-loading"></i><span>Material Types</span></a></li>
                    <li class="plans"><a class="nav-link" href="subscription_plans"><i class="fas fa-list"></i><span>Subscription Plans</span></a></li>
                    <li class="subscribed"><a class="nav-link" href="subscribed_users"><i class="fas fa-hands-helping"></i><span>Subscribed Users</span></a></li>
                </ul>
            </aside>
            
        </div>
    ';

    $footer =
    '
        <footer class="main-footer">
            <div class="footer-left">
                Copyright &copy; <script>document.write(new Date().getFullYear());</script> <a href="https://truckwale.co.in/" target="_blank">Truck Wale</a> | Developed by <a href="https://thegraphe.com" target="_blank">The Graphē - A Design Studio</a>
            </div>
        </footer>
    ';

    $en = "en";
    $script_tags =
    '
        <!-- General JS Scripts -->
        <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>

        
        
        <!-- Template JS File -->
        <script src="assets/js/scripts.js"></script>
        <script src="assets/js/custom.js"></script>

        <script>
            $(document).ready(function()
            {
                var set = "set";
                $("#sidebar-switch").on("click",function(){
                    $.ajax({
                        url:"processing/curd_sidebar.php",
                        method:"POST",
                        data:{set:set},
                        success:function(data){
                        
                        }
                    });
                });
            });
        </script>

    ';

?>