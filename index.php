<?php
    
    session_start();
    if($_SERVER["REQUEST_METHOD"]=="POST")
    {
        require_once 'database.php';
        
         if (!empty($_POST['mail']) && !empty($_POST['pass']))
         {
            $valid=0;
            $con=$db->prepare('SELECT mail, passHash, active from users where mail=:email or username=:email');
            $con->bindParam(':email',$_POST['mail']);
            $con->execute();
            $valid=$con->fetch(PDO::FETCH_ASSOC);
            
            if(empty($valid) or $valid['active']!=1){
                echo'<script>
                    alert("El username o mail no existeix!!");
                    </script>';
            }else
            if(password_verify($_POST['pass'], $valid['passHash']))
            {
                /*echo'<script>
                    alert("'.count($valid).'");
                    </script>';*/
                $con = "UPDATE users SET lastSignIn=CURRENT_TIMESTAMP WHERE mail=:email or username=:email";
                $smt = $db->prepare($con);
                $smt->bindParam(':email',$_POST['mail']);
                $smt->execute();
                
                $_SESSION['mail']=$valid['mail'];
                header('Location: home/home.php');
            }else
            {
                 echo'<script>
                 alert("Contrasenya incorrecta!!");
                 </script>';
            }
         }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>IMAGINEST</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.png"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100" style="background-image: url('images/2.jpg');">
			<div class="wrap-login100">
				<form class="login100-form validate-form" action="<?php echo htmlspecialchars ($_SERVER["PHP_SELF"]);?>" method="post">
					<span class="login100-form-logo">
						<i class="zmdi zmdi-landscape"></i>
					</span>
                    
                    <span class="nomTitle p-b-10 p-t-27">
						IMAGINEST
					</span>
                    
					<span class="login100-form-title p-b-34 p-t-27">
						Log in
					</span>

					<div class="wrap-input100 validate-input" data-validate = "Enter username or mail">
						<input class="input100" type="text" name="mail" placeholder="Username / Mail">
						<span class="focus-input100" data-placeholder="&#xf207;"></span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Enter password">
						<input class="input100" type="password" name="pass" placeholder="Password">
						<span class="focus-input100" data-placeholder="&#xf191;"></span>
					</div>

					<div class="contact100-form-checkbox">
						<input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
						<label class="label-checkbox100" for="ckb1">
							Remember me
						</label>
					</div>

					<div class="container-login100-form-btn">
						<button class="login100-form-btn">
							Log in
						</button>
					</div>

					<div class="text-center p-t-15">
						<a class="txt1" href="Redirect/register.php">
							<h3>Register now</h3>
						</a>
					</div>
                    
                    <!--<script>
                    function myFunction() {
                        var person = prompt("Please enter your email");
                      if ($valid){
                        alert("Revisa el correu");
                      } else if(person == null || person == "") {
                        alert("El mail no existeix.");
                      } else
                      alert("Introdueix algo");
                    }
                    </script>-->
                    <!-- Button trigger modal -->
                    <div class="text-center p-t-15">
                    <button type="button" class="btn btn-light" data-toggle="modal" data-target="#exampleModal">
                      Has olvidat la password?
                    </button>
                    </div>

				</form>
			</div>
		</div>
	</div>
	

	<div id="dropDownSelect1"></div>
    
<!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <form action="resetPasswordSend.php" method="post">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Escriu el correu electrónic</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                              <label for="fname">Escriu abaix el email o nom d'usuari:</label><br>
                              <input type="text" name="gpass" required><br>
                          </div>
                          <div class="modal-footer">
                            <button type="submit" class="btn btn-secondary">Send Reset Password Email   </button>
                          </div>
                        </form>
                        </div>
                      </div>
                    </div>
	
<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>
</html>