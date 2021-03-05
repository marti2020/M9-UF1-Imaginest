<?php
    require_once '../database.php';

    $message='';

    if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['passwordVerify']))
    {
        $sql = "INSERT INTO users (mail,username,passHash,userFirstName,userLastName,active) VALUES (:email, :name, :password, :first, :last,0)";
        $smt = $db->prepare($sql);
        $smt->bindParam(':email',$_POST['email']);
        $smt->bindParam(':name',$_POST['name']);
        $name=$_POST['name'];
        $smt->bindParam(':first',$_POST['first']);
        $smt->bindParam(':last',$_POST['last']);
        $pass=password_hash($_POST['password'], PASSWORD_BCRYPT);
        $smt->bindParam(':password', $pass);
        
        $pass1=$_POST['password'];
        $pass2=$_POST['passwordVerify'];
        
        if($pass1===$pass2)
        {
            if($smt->execute()){
            echo'<script>
        alert("Usuari ' .$name. ' registrat amb éxit, verifica el correu electrónic!!");
        </script>';
            $valor=rand();
            $valor=hash('sha256',$valor);
                
            $sql = "UPDATE users set activationCode='$valor' where mail=:email";
            $smt = $db->prepare($sql);
            $smt->bindParam(':email',$_POST['email']);
            $smt->execute();
            
            require'../mailCheck.php';
            }
            else{
                echo'<script>
        alert("Usuari ja existeix!!");
        </script>';
            }
        }
        else
        {
            echo'<script>
        alert("La contrasenya 1 no es igual que la 2");
        </script>';
        }
    }
    else if(isset($_POST['name']))
    {
        echo'<script>
        alert("Omple TOTS els camps del formulari");
        </script>';
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Colorlib Templates">
    <meta name="author" content="Colorlib">
    <meta name="keywords" content="Colorlib Templates">

    <!-- Title Page-->
    <title>Register Imaginest</title>

    <!-- Icons font CSS-->
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <!-- Font special for pages-->
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Vendor CSS-->
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="vendor/datepicker/daterangepicker.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="css/main.css" rel="stylesheet" media="all">
</head>

<body>
    <div class="page-wrapper bg-gra-01 p-t-180 p-b-100 font-poppins">
        <div class="wrapper wrapper--w780">
            <div class="card card-3">
                <div class="card-heading"></div>
                <div class="card-body">
                    <h2 class="title">Registration Form</h2>
                    <form action="<?php echo htmlspecialchars ($_SERVER["PHP_SELF"]);?>" method="post">
                        <div class="input-group">
                            <input class="input--style-3" type="text" placeholder="Username" name="name" required>
                        </div>  
                        <div class="input-group">
                            <input class="input--style-3" type="email" placeholder="Email" name="email" required>
                        </div>
                        
                        <div class="input-group">
                            <input class="input--style-3" type="text" placeholder="First name" name="first">
                        </div>
                        
                        <div class="input-group">
                            <input class="input--style-3" type="text" placeholder="Last name" name="last">
                        </div>
                        
                        <div class="input-group">
                            <input class="input--style-3" type="password" placeholder="Password" name="password" required>
                        </div>
                        <div class="input-group">
                            <input class="input--style-3" type="password" placeholder="Verify Password" name="passwordVerify" required>
                        </div>
                        <div class="p-t-10">
                            <button class="btn btn--pill btn--green" type="submit" >Submit</button>
                        </div>
                        
                    </form>
                    
                    <div class="p-t-10">
                    <a class="txt1" href="../index.php">
                        <h3>Login</h3></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Jquery JS-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <!-- Vendor JS-->
    <script src="vendor/select2/select2.min.js"></script>
    <script src="vendor/datepicker/moment.min.js"></script>
    <script src="vendor/datepicker/daterangepicker.js"></script>

    <!-- Main JS-->
    <script src="js/global.js"></script>

</body>
</html>
