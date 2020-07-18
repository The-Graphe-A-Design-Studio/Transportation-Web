<?php
   include('layout.php');
   // Initialize the session
   session_start();
   
   // Check if the user is already logged in, if yes then redirect him to welcome page
   if(isset($_SESSION["admin_loggedin"]) && $_SESSION["admin_loggedin"] === true)
   {
      header("location: ./");
      exit;
   }
   
   // Include config file
   require_once "dbcon.php";
   
   // Define variables and initialize with empty values
   $username = $password = $err_msg = "";
   $username_err = $password_err = "";
 
    // Processing form data when form is submitted
   if($_SERVER["REQUEST_METHOD"] == "POST")
   {
   
      // Check if email is empty
      if(empty(trim($_POST["username"])))
      {
         $username_err = "Please enter username.";
      }
      else
      {
         $username = trim($_POST["username"]);
      }
      
      // Check if password is empty
      if(empty($_POST["password"]))
      {
         $password_err = "Please enter password.";
      }
      else
      {
         $password = $_POST["password"];
      }
      
      // Validate credentials
      if(empty($username_err) && empty($password_err))
      {
         $enpassword = md5($password);
         
         $login_sql = "SELECT * FROM admin WHERE admin_username = '$username' and admin_pass = '$enpassword'";
         $result = mysqli_query($link, $login_sql);
         $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
         $count = mysqli_num_rows($result);
         
         if($count == 1)
         {
            $_SESSION["admin_loggedin"] = true;
            $_SESSION['admin_username'] = $username;
            // $_SESSION['login_pass'] = $password;
            header("location: ./");
         }
         else
         {
            $err_msg =
            '
               <div class="alert alert-danger alert-dismissible show fade">
                  <div class="alert-body">
                     <button class="close" data-dismiss="alert">
                        <span>×</span>
                     </button>
                     Invalid username or password.
                  </div>
               </div>
            ';
            // echo"<script>alert('Email or Password is invalid')</script>";  
            // echo "<script>location.href='login'</script>";
         }
      }
      
      // Close connection
      mysqli_close($link);
   }


?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Admin Login | diva lounge spa</title>
    <?php echo $head_tags; ?>
</head>
<body>

   <div id="app">
      <section class="section">
         <div class="container mt-5">
            <div class="row">
               <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                  <div class="login-brand">
                     <img src="assets/img/diva-logo-sm.png" alt="diva lounge spa logo" width="100">
                  </div>
                  <div class="card card-primary">
                     <div class="card-header">
                        <h4>Admin Login</h4>
                     </div>
                     <?php echo $err_msg; ?>
                     <div class="card-body">
                        <form enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="needs-validation" novalidate="">
                           <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                              <label for="username">Username</label>
                              <input id="username" type="text" class="form-control" value="<?php echo $username; ?>" name="username" tabindex="1" required autofocus>
                              <div class="invalid-feedback">
                                 <?php echo $username_err; ?>
                              </div>
                           </div>
                           <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                              <label for="password" class="control-label">Password</label>
                              <input id="password" type="password" class="form-control" name="password" value="<?php echo $password; ?>" tabindex="2" required>
                              <div class="invalid-feedback">
                                 <?php echo $password_err; ?>
                              </div>
                           </div>
                           <div class="form-group">
                              <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="3">
                              Login
                              </button>
                           </div>
                        </form>
                     </div>
                  </div>
                  <div class="simple-footer">
                     Copyright &copy; <script>document.write(new Date().getFullYear());</script> Diva Lounge Spa
                     <br>
                     Developed by <a href="https://thegraphe.com" target="_blank">The Graphē - A Design Studio</a>
                  </div>
               </div>
            </div>
         </div>
      </section>
   </div>

   <?php echo $script_tags; ?>
</body>
</html>