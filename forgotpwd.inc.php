<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email            = $_POST["email"];
    $verificationcode = $_POST["verificationcode"];
    $newpassword      = $_POST["newpassword"];


    try {
        require_once './dbh.inc.php';
        require_once './forgotpwd_model.inc.php';
        require_once './forgotpwd_contr.inc.php';

        $errors = [];

        if (is_request_empty($email, $verificationcode, $newpassword)) {
            $errors["empty_request"] = "Fill in the details!";
        }

        $result = get_details($pdo, $email, $verificationcode);

        if (is_email_or_verificationcode_wrong($result)) {
            $errors["wrong_email_verificationcode"] = "Email or the verification code is incorrect!";
        }

        require_once 'config_session.inc.php';

        if ($errors) {
            $_SESSION["errors_signup"] = $errors;
            header("Location: ./forgotpwd.php");
            die();
        }
        
        create_new_password($pdo, $newpassword, $email);

        header("Location: ./forgotpwd.php?forgotpwd=success"); //change the page to success

        $pdo = null;
        $stmt = null;
        die();

    } catch (PDOException $e) {
        die ("Query failed: " . $e->getMessage());
    }

} else {
    header("Location: ./forgotpwd.php");
    die();
}