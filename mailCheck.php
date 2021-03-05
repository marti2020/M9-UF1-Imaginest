<?php
    use PHPMailer\PHPMailer\PHPMailer;
    if(!isset($_GET['code']) and !isset($_GET['mail']) and !empty($_POST['name']))
    {
    require 'vendor/autoload.php';
    $mail = new PHPMailer();
    $mail->IsSMTP();

    //Configuració del servidor de Correu
    //Modificar a 0 per eliminar msg error
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
    $mail->Subject = 'Activate your account now, '.$_POST['name'];

    $mail->MsgHTML('<img src="https://i.imgur.com/ECOI48J.png">'.'<br>Activa la cuenta aquí: ' . '<a href="http://localhost/PRACTICA3/mailCheck.php?code='.$valor.'&mail='.$_POST['email'].'">Activate!!</a>');
    //$mail->addAttachment("fitxer.pdf");
    
    //Destinatari
    $address = $_POST['email'];
    $mail->AddAddress($address, $_POST['name']);

    //Enviament
    $result = $mail->Send();
    }
    else{
        require_once 'database.php';
        $sql = "UPDATE users set active=1 where mail=:email";
            $smt = $db->prepare($sql);
            $smt->bindParam(':email',$_GET['mail']);
            $smt->execute();
                echo'<script>
                alert("Usuari activat amb éxit, inicia sessió");
                location.href="index.php";
                </script>'; 
    }