<?php
    session_start();
    require_once '../database.php';

    if(isset($_SESSION['mail']))
    {
        $con=$db->prepare('SELECT iduser, username, mail, passHash from users where mail=:mail');
        $con->bindParam(':mail',$_SESSION['mail']);
        $con->execute();
        
        $results=$con->fetch(PDO::FETCH_ASSOC);
        
        $user=null;
        
        if(!empty($results)){
            $user=$results;
        }
    }else
    {
        header('Location: ../index.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>IMAGINEST</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="stylesheet" type="text/css" href="css/main.css">

</head>
<body>
    <?php if(!empty($user)): ?>
      <br> Welcome <?= $user['username']; ?>
      <br>You are Successfully Logged In
      <a href="../logout.php">
        Logout
      </a>
    <?php else: ?>
      <h1>Please Login or SignUp</h1>

      <a href="../index.php">Login</a>
    <?php endif; ?>
	
    <!--<button type="button" text="LOG OUT" class="btn btn-default btn-sm"></button>-->

</body>
</html>