<?php
session_start();
include("includes/config.php");

$success = $error = "";
$errors = array();

if(isset($_POST['login'])){
    
    $user = trim(stripslashes(mysqli_real_escape_string($con, $_POST['user'])));
    $password = trim(stripslashes(mysqli_real_escape_string($con, $_POST['password'])));
    
    if(empty($user)){
        array_push($errors,$userErr = "please provide your username or email");
    }
    if(empty($password)){
        array_push($errors,$passwordErr = "password cannot be empty");
    }
    
    if(count($errors) == 0){
        $encryptedPwd = md5($password);
        $query = mysqli_query($con, "SELECT * FROM users WHERE (username='$user' OR email='$user') AND password='$encryptedPwd' ");
        
        
        if(mysqli_num_rows($query) == 1){
            $userData = mysqli_fetch_assoc($query);
            $userId = $userData['id'];
            $userName = $userData['username'];
            $userRole = $userData['role'];
            $userStatus = $userData['status'];
            
            
            
            if($userStatus == "Active"){
                $_SESSION['schoolsAdmin'] = $userId;
                $_SESSION['schoolsAdminUserName'] = $userName;
                $_SESSION['schoolsAdminStatus'] = $userStatus;
                $_SESSION['auth_success_msg'] = "You are now logged in. Pick up from where you left";
                header('location: index.php');
            }
            else{
                $error = "Your account is not active. Contact the site administrator";
            }
            
        }
        else{
            $error = "Invalid User details supplied";
        }
        
        
    }
    else{
        $error = "All errors must be resolved";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Creative - Bootstrap 3 Responsive Admin Template">
    <meta name="author" content="GeeksLabs">
    <meta name="keyword" content="Creative, Dashboard, Admin, Template, Theme, Bootstrap, Responsive, Retina, Minimal">
    <link rel="shortcut icon" href="img/favicon.png">

    <title>Login | GIZ Schools</title>

    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- bootstrap theme -->
    <link href="css/bootstrap-theme.css" rel="stylesheet">
    <!--external css-->
    <!-- font icon -->
    <link href="css/elegant-icons-style.css" rel="stylesheet" />
    <link href="css/font-awesome.css" rel="stylesheet" />
    <!-- Custom styles -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style-responsive.css" rel="stylesheet" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->

    <!-- =======================================================
      Theme Name: NiceAdmin
      Theme URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
      Author: BootstrapMade
      Author URL: https://bootstrapmade.com
    ======================================================= -->
</head>

<body class="login-img3-body">

    <div class="container">

        <form class="login-form" action="" method="post">
            <div class="login-wrap">
                <p class="login-img"><i class="fa fa-lock"></i></p>
                <?php include("includes/alerts.php"); ?>
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon_profile"></i></span>
                    <input type="text" name="user" class="form-control" placeholder="Username or Email" autofocus>
                    <span class="text-danger"><?php if(isset($userErr)){ echo $userErr; } ?></span>
                </div>
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon_key_alt"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Password">
                    <span class="text-danger"><?php if(isset($passwordErr)){ echo $passwordErr; } ?></span>
                </div>
                <label class="checkbox">
                    <input type="checkbox" value="remember-me"> Remember me
                    <span class="pull-right"> <a href="#"> Forgot Password?</a></span>
                </label>
                <button class="btn btn-primary btn-lg btn-block" type="submit" name="login">Login</button>
            </div>
        </form>

    </div>


</body>

</html>
