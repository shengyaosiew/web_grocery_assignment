<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username   = $_POST["username"];
    $pwd        = $_POST["pwd"];
    $rememberMe = isset($_POST["remember_me"]);

    try {
        require_once './dbh.inc.php';
        require_once './login_model.inc.php';
        require_once './login_contr.inc.php';

        //ERROR HANDLERS
        $errors = [];

        if (is_input_empty($username, $pwd)) {
            $errors["empty_input"] = "Fill in all fields!";
        }
        
        $result = get_user($pdo, $username);

        if (is_username_wrong($result)) {
            $errors["login_incorrect"] = "Username not exist!";
        }

        if (is_password_wrong($pwd, $result["password"])) {
            $errors["login_incorrect"] = "Incorrect password!";
            
            // Uncheck the "remember_me" checkbox
            if (isset($_POST['remember_me'])) {
                unset($_POST['remember_me']);
            }
        }

        require_once 'config_session.inc.php';

        if ($errors) {
            $_SESSION["errors_login"] = $errors;
            header("Location: ./login.php");
            die();
        }
        
        session_regenerate_id(true);

        $_SESSION["user_id"] = $result["userid"];
        $_SESSION["username"] = $result["username"];
        $_SESSION["usertype"] = $result["usertype"];

        $_SESSION["last_generation"] = time();

        // If login is successful and 'remember_me' is checked
        if (isset($_POST['remember_me'])) {
            // Set cookies for username and password
            setcookie('username', $_POST['username'], time() + (86400 * 30), "/"); // 86400 = 1 day
            setcookie('password', $_POST['pwd'], time() + (86400 * 30), "/");
        } elseif (!isset($_POST['remember_me'])) {
            // If 'remember_me' is not checked, clear the cookies
            if (isset($_COOKIE['username'])) {
                setcookie('username', '', time() - 3600, "/");
            }
            if (isset($_COOKIE['password'])) {
                setcookie('password', '', time() - 3600, "/");
            }
        }

        header("Location: ./login.php?login=success");

        $pdo = null;
        $stmt = null;
        die();

    } catch (PDOException $e) {
        die ("Query failed: " . $e->getMessage());
    }
} else {
    header("Location: ../login.php");
    die();
}
