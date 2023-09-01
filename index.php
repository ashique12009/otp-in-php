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
            <input type="email" name="email" id="email" placeholder="Email" class="form-control-text">
            <br><br>
            <input type="password" name="password" id="password" placeholder="Password" class="form-control-text">
            <br><br>
            <input type="submit" value="Register" name="register" class="form-control-button">
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
            <input type="email" name="email" id="email" placeholder="Email" class="form-control-text">
            <br><br>
            <input type="submit" value="Send OTP" name="login" class="form-control-button">
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
                    }
                    else {
                        echo "Email not found!";
                    }
                }
                else {
                    echo "Sending OTP fail!";
                }
            }

            ?>

            <form action="" method="post" class="otp-form">
                <input type="text" name="otp" id="otp" placeholder="OTP" value="" class="form-control-text">
                <br><br>
                <input type="submit" value="Login" name="submit_otp" class="form-control-button">
            </form>

            <?php

            if (isset($_POST['submit_otp'])) {
                if ($_POST['otp'] != "") {
                    $otp = $_POST['otp'];
                    $loginObject = new Login($databaseConnection);
                    if ($loginObject->checkOTPIsExpired($otp)) {
                        // This OTP is not expired so update the Database and redirect to home page
                        $loginObject->expireOTP($otp);
                        header('Location: home.php');
                    }
                    else {
                        echo 'OTP is expired!';
                    }
                }
                else {
                    echo 'OTP is empty!';
                }
            }
        ?>
    </div>
</div>

<?php include('footer.php'); ?>
</body>
</html>