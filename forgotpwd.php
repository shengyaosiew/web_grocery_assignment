<?php
require_once './config_session.inc.php';
require_once './forgotpwd_view.inc.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="forgotpwd.css">
</head>

<body>
    <div class="forgotpwd-container">
        <div class="forgotpwd-container-logo">
            <img src="./images/heyday_icon.png" alt="heyday_icon">
            <h3>PASSWORD RECOVERY</h3>
        </div>
        
        <form action="./forgotpwd.inc.php" method="POST">
            <div class="forgotpwd-container-main">
                <input type="email" name="email" placeholder="E-MAIL ADDRESS:">
                <input type="text" name="verificationcode" placeholder="VERIFICATION CODE:">
                <input type="text" name="newpassword" placeholder="ENTER THE NEW PASSWORD:">
            </div>

            <input type="submit" value="SUBMIT">
        </form>

        <p class="askHelp">ALREADY A USER? <a href="login.php" target="_self">SIGN IN</a></p>
        <p class="askHelp">DONT HAVE USER ACCOUNT? <a href="signup.php" target="_self">SIGN UP</a></p>

        <?php
            check_details_errors()
        ?>
    </div>

</body>
</html>
