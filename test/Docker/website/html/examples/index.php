<?php
session_start();

if (isset($_SESSION['connec']) && $_SESSION['connec'] == true){
	header('Location: dashboard.php');
}
?>

<!doctype html>
<html lang="en">
<head>
    <title>Login</title>

	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />


	<style>body{padding-top: 60px;}</style>

    <link href="../assets/css/bootstrap.css" rel="stylesheet" />

	<link href="../assets/css/login-register.css" rel="stylesheet" />
	<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">

	<script src="../assets/js/jquery-1.10.2.js" type="text/javascript"></script>
	<script src="../assets/js/bootstrap.js" type="text/javascript"></script>
	<script src="../assets/js/login-register.js" type="text/javascript"></script>

</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
                 <a class="btn big-login" data-toggle="modal" href="javascript:void(0)" onclick="openLoginModal();">Log in</a>
            <div class="col-sm-4"></div>
        </div>


		 <div class="modal fade login" id="loginModal">
		      <div class="modal-dialog login animated">
    		      <div class="modal-content">
    		         <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Login with</h4>
                    </div>
                    <div class="modal-body">
                        <div class="box">
                             <div class="content">
                                <div class="error"></div>
                                <div class="form loginBox">
																	<form method="post" action="login.php" class="form-signin">
                                    <input type="text" name="pseudo" id="pseudo" class="form-control" placeholder="Pseudo" required>
                                    <input type="password" name="passwd" id="passwd" class="form-control" placeholder="Password" required>
                                    <input class="btn btn-default btn-login" type="submit" value="Login">
                                    </form>
                                </div>
                             </div>
                        </div>
                    </div>
    		      </div>
		      </div>
		  </div>
    </div>
<script type="text/javascript">
    $(document).ready(function(){
        openLoginModal();
    });
</script>


</body>
</html>
