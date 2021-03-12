<?php
    use PHPMailer\PHPMailer\PHPMailer;
    require_once 'database.php';

    if(isset($_POST['gpass']))
    {
        $sql=$db->prepare('SELECT mail from users where mail=:email or username=:email');
        $sql->bindParam(':email',$_POST['gpass']);
        if($sql->execute())
        {
            $valor=rand();
            $valor=hash('sha256',$valor);
            
            $sql=$sql->fetch(PDO::FETCH_ASSOC);
            $correu=$sql['mail'];

            $minutes_to_add = 90;

            $time = new DateTime();
            $time->add(new DateInterval('PT' . $minutes_to_add . 'M'));

            $hora = $time->format('Y-m-d H:i:s');

            $sql = "UPDATE users set resetPassCode='$valor', resetPass=1, resetPassExpiry='$hora' where mail='$correu'";
            $smt = $db->prepare($sql);
            $smt->execute();

            require 'vendor/autoload.php';
            $mail = new PHPMailer();
            $mail->IsSMTP();

            $mail->SMTPDebug = 0;
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'tls';
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 587;

            //Credencials del compte GMAIL
            $mail->Username = 'mdelacruz2021@educem.net';
            $mail->Password = '_Asix@2021_';

            //Dades del correu electrònic
            $mail->SetFrom('mdelacruz2021@educem.net','Imaginest');
            $mail->Subject = 'Reset your password, '.$correu;

            $mail->MsgHTML('<img src="https://i.imgur.com/ECOI48J.png">'.'<br>Reseteja la password aquí: ' . '<a href="http://localhost/PRACTICA3/resetPasswordSend.php?code='.$valor.'&mail='.$correu.'">I Want to reset!!</a>');

            //Destinatari
            $address = $correu;
            $mail->AddAddress($address, $correu);

            //Enviament
            $result = $mail->Send();

            echo'<script>
            alert("Revisa el correu per cambiar la password!!");
            location.href="index.php";
            </script>';
        }

    }else
    if(isset($_GET['code']) and isset($_GET['mail']))
        {
            $valid=0;
            $correu=$_GET['mail'];
            $con=$db->prepare('SELECT mail, resetPassCode, resetPassExpiry from users where mail=:email or username=:email');
            $con->bindParam(':email',$correu);
            if($con->execute())
            {
                $valid=$con->fetch(PDO::FETCH_ASSOC);
                $minutes_to_add = 60;

                $time = new DateTime();
                $time->add(new DateInterval('PT' . $minutes_to_add . 'M'));

                $hora = $time->format('Y-m-d H:i:s');
                if($_GET['code']!=$valid['resetPassCode'] or $_GET['mail']!=$valid['mail'] or $hora>$valid['resetPassExpiry'])
                {
                    $sql = "UPDATE users set resetPassCode=null, resetPass=0, resetPassExpiry=null where mail='$correu'";
                    $smt = $db->prepare($sql);
                    $smt->execute();
                    header('Location: index.php');
                }else
                if(!empty($_POST['pass1']) and !empty($_POST['pass2']) and $_POST['pass1']==$_POST['pass2'])
                {
                    $pass=password_hash($_POST['pass1'], PASSWORD_BCRYPT);
                    $correu=$_GET['mail'];
                    $sql = "UPDATE users set passHash='$pass', resetPassCode=null, resetPass=0, resetPassExpiry=null where mail='$correu'";
                    $smt = $db->prepare($sql);
                    $smt->execute();
                    
                    require 'vendor/autoload.php';
                    $mail = new PHPMailer();
                    $mail->IsSMTP();

                    $mail->SMTPDebug = 0;
                    $mail->SMTPAuth = true;
                    $mail->SMTPSecure = 'tls';
                    $mail->Host = 'smtp.gmail.com';
                    $mail->Port = 587;

                    //Credencials del compte GMAIL
                    $mail->Username = 'mdelacruz2021@educem.net';
                    $mail->Password = '_Asix@2021_';

                    //Dades del correu electrònic
                    $mail->SetFrom('mdelacruz2021@educem.net','Imaginest');
                    $mail->Subject = 'Password correctament canviada';

                    $mail->MsgHTML('<img src="https://i.imgur.com/ECOI48J.png">'.'<br>Password correctament canviada, ves al login per iniciar sessió!!');
                    
                    $address = $correu;
                    $mail->AddAddress($address, $correu);

                    //Enviament
                    $result = $mail->Send();
                    
                    header('Location: index.php');
                }
            }
        }
        else {header('Location: index.php');}
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
				<form class="login100-form validate-form" action="<?php echo htmlspecialchars ($_SERVER["REQUEST_URI"]);?>" method="post">
					<span class="login100-form-logo">
						<i class="zmdi zmdi-landscape"></i>
					</span>
                    
                    <span class="nomTitle p-b-10 p-t-27">
						IMAGINEST
					</span>
                    
					<span class="login100-form-title p-b-34 p-t-27">
						Change password
					</span>

					<div class="wrap-input100 validate-input" data-validate = "Enter password">
						<input class="input100" type="password" name="pass1" placeholder="Password">
						<span class="focus-input100" data-placeholder="&#xf207;"></span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Confirm password">
						<input class="input100" type="password" name="pass2" placeholder="Repeat Password">
						<span class="focus-input100" data-placeholder="&#xf191;"></span>
					</div>

					<div class="container-login100-form-btn">
						<button class="login100-form-btn">
							Change
						</button>
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