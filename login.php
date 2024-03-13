<?php
require_once './config_session.inc.php';
require_once './login_view.inc.php';

// Check if cookies are set for username and password
$username = isset($_COOKIE['username']) ? $_COOKIE['username'] : '';
$password = isset($_COOKIE['password']) ? $_COOKIE['password'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="login.css">
</head>

<body>
    <div class="sign-in-container">
        <div class="sign-in-container-logo">
            <img src="./images/heyday_icon.png" alt="heyday_icon">
            <h3>SIGN IN</h3>
        </div>
        
        <form action="./login.inc.php" method="POST">
            <div class="sign-in-container-main">
                <!-- Fill in the username and password fields with cookie values if they are set -->
                <input type="text" id="username" name="username" placeholder="USERNAME:" value="<?php echo $username; ?>" autocomplete="username">
                <input type="password" id="pwd" name="pwd" placeholder="PASSWORD:" value="<?php echo $password; ?>" autocomplete="current-password">
            </div>

            <div class="sign-in-container-rememberMe">
                <!-- Check the checkbox if the username cookie is set -->
                <input type="checkbox" id="rememberMe" name="remember_me" <?php echo ($username && $password) ? 'checked' : ''; ?>> 
                <label for="rememberMe">REMEMBER ME</label>
            </div>

            <input type="submit" value="SIGN IN"><br><br><br>
        </form>

        <p class="askHelp">FORGOT MY PASSWORD? <a href="forgotpwd.php" target="_self">CLICK HERE</a></p>
        <p class="askHelp">DONT HAVE USER ACCOUNT? <a href="signup.php" target="_self">SIGN UP</a></p>

    <?php
    check_login_errors();
    ?>

    </div>

</body>
</html>
