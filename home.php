<?php
include('include/config.php');
if($admin_user->is_logged_in()){
		redirect(make_admin_url('home', 'list'));           
}
if(isset($_POST['login'])):
		if($user=validate_login('login', $_POST)):
				$admin_user->set_admin_user_from_object($user);                    
				redirect(make_admin_url('home', 'list')); 
		else:
			$admin_user->set_error();
			$admin_user->set_pass_msg("Invalid Username/Password");
		endif;
endif;?>

<!DOCTYPE html>
<html class="h-100" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>DLM - Login </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
    <!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous"> -->
    <link href="<?=DIR_WS_SITE?>assets/css/style.css" rel="stylesheet">
    <link href="<?=DIR_WS_SITE?>assets/plugins/toastr/toastr.min.css" rel="stylesheet">
    
</head>
<body class="h-100 loginbody">
    
    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="loader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10" />
            </svg>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

        <div class="login-form-bg  ptt">
        <div class="container ">
            <div class="row justify-content-center ">
			<div class="col-xl-7"></div>
                <div class="col-xl-5">
				<div class="col-xl-2 fleft login-icon"><img src="assets/images/login-icon.png"/></div>
				<div class="col-xl-10 fleft">
                    <div class="form-input-content">
                        <div class="card login-form mb-0">
                            <div class="card-body pt-5">

								<form class="mt-5 mb-5 login-input mlleft validate-form" action="" method="post" accept-charset="utf-8"> 
									<?php validation_errors(1); ?>	
									<div class="form-group">
                                        <input type="text" class="form-control marginb20" name="username" placeholder="Username">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control marginb30" id="passowrd" name="password" placeholder="Password">
                                    </div>
                                    <div class="form-group fleft"><button name='login' class="btn login-form__btn submit ">Log In</button></div>									
									
								</form>
                                <p class=" login-form__footer tright">Forgot Password</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--**********************************
        Scripts
    ***********************************-->
    <script src="assets/plugins/common/common.min.js"></script>
    <script src="assets/plugins/toastr/toastr.min.js"></script>
    <script src="assets/js/custom.min.js"></script>
    <script src="assets/js/settings.js"></script>
    <script src="assets/js/gleek.js"></script>
    <script src="assets/js/styleSwitcher.js"></script>
    
    <?php include_once('others/toastr.php');?>
    
</body>
</html> 