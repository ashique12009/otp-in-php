<?php

require_once 'includes/config/Database.class.php';
require_once 'includes/config/CreateTable.class.php';
require_once 'includes/Register.class.php';
require_once 'includes/Login.class.php';
require_once 'mail_functions.php';

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
                        // Generate OTP
                        $otp = rand(1000, 9999);

                        // Send OTP to user email
                        if (sendOTP($otp)) {
                            // Insert generated OTP to Database
                            $loginObject->insertOTP($email, $otp);
                            echo 'OTP has been sent to your email';
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

                    header('Location: home.php');
                }
            }
        ?>
    </div>
</div>

<?php include('footer.php'); ?>
</body>
</html>