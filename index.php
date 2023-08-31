<?php

require_once 'includes/config/Database.class.php';
require_once 'includes/config/CreateTable.class.php';
require_once 'includes/Register.class.php';
require_once 'includes/Login.class.php';
require_once 'PHPMailer/src/Exception.php';
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$databaseObject     = new Database();
$databaseConnection = $databaseObject->getConnection();
$createTableObject  = new CreateTable($databaseConnection);
$createTableObject->createDatabaseTables();

include('header.php');
?>

<div id="login-register-form">
    <div class="forms">
        <h1>Registration</h1>
        <form action="" method="post">
            <input type="email" name="email" id="email" placeholder="Email">
            <br><br>
            <input type="password" name="password" id="password" placeholder="Password">
            <br><br>
            <input type="submit" value="Register" name="register">
        </form>
        <?php 
            if (isset($_POST['register'])) {
                if (isset($_POST['email']) && $_POST['email'] != "" && isset($_POST['password']) && $_POST['password'] != "") {
                    $registrationObject = new Register($databaseConnection);
                    if ($registrationObject->insertUser($_POST['email'], $_POST['password'])) {
                        echo "Registration done!";
                    }
                }
                else {
                    echo "Registration fail!";
                }
            }
        ?>
    </div>
    <div class="forms">
        <h1>Login</h1>
        <form action="" method="post">
            <input type="email" name="email" id="email" placeholder="Email">
            <br><br>
            <input type="submit" value="Enter email to send OTP" name="login">
        </form>
        <?php 
            if (isset($_POST['login'])) {
                if (isset($_POST['email']) && $_POST['email'] != "") {
                    $loginObject = new Login($databaseConnection);
                    if ($loginObject->checkUser($_POST['email'])) {
                        $email = $_POST['email'];
                        $phpmailer = new PHPMailer(true);
                        try {
                            // Server settings
                            $phpmailer = new PHPMailer();
                            $phpmailer->isSMTP();
                            $phpmailer->Host = 'sandbox.smtp.mailtrap.io';
                            $phpmailer->SMTPAuth = true;
                            $phpmailer->Port = 2525;
                            $phpmailer->Username = '8c47d5a7abace5';
                            $phpmailer->Password = '7b69ef42c5e37e';
                            // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
                        
                            // Recipients
                            $phpmailer->setFrom('ashique-otp@local.com', 'Ashique OTP');
                            $phpmailer->addAddress('ashique12009@yahoo.com', 'Ashique User');     // Add a recipient
                            $phpmailer->addReplyTo('info@example.com', 'Information');
                            $phpmailer->addCC('cc@example.com');
                            $phpmailer->addBCC('bcc@example.com');
                        
                            // Content
                            $phpmailer->isHTML(true);                                  // Set email format to HTML
                            $phpmailer->Subject = 'Take your OTP';
                            $phpmailer->Body    = 'Your OTP is: ';
                            $phpmailer->AltBody = 'Your OTP is: ';
                        
                            $phpmailer->send();
                            echo 'OTP has been sent to your email';
                        } 
                        catch (Exception $e) {
                            echo 'Email failed';
                        }

                        ?>
                        <form action="" method="post" class="otp-form">
                            <input type="text" name="otp" id="otp" placeholder="OTP">
                            <br><br>
                            <input type="submit" value="Enter OTP" name="otp">
                        </form>
                        <?php 
                    }
                }
                else {
                    echo "Login fail!";
                }
            }

            if (isset($_POST['otp'])) {
                if ($_POST['otp'] != "") {
                    


                }
            }
        ?>
    </div>
</div>

<?php include('footer.php'); ?>
</body>
</html>