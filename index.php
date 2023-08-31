<?php
require_once 'includes/config/Database.class.php';
require_once 'includes/config/CreateTable.class.php';
require_once 'includes/Register.class.php';

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
    </div>
</div>

<?php include('footer.php'); ?>
</body>
</html>