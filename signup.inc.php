<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $usertype         = $_POST["usertype"];
    $username         = $_POST["username"];
    $password         = $_POST["password"];
    $email            = $_POST["email"];
    $phonenumber      = $_POST["phonenumber"];
    $fullname         = $_POST["fullname"];
    $country          = $_POST["country"];
    $postcode         = $_POST["postcode"];
    $state            = $_POST["state"];
    $city             = $_POST["city"];
    $addressline      = $_POST["addressline"];
    $verificationcode = $_POST["verificationcode"];

    try {
        require_once './dbh.inc.php'; 
        require_once './signup_model.inc.php'; //File for query the database
        require_once './signup_contr.inc.php'; //File for error handlers, and validation

        //ERROR HANDLERS
        $errors = [];

        if (is_email_invalid($email)){
            $errors["invalid_email"] = "Invalid email used!";
        }
        if (is_username_taken($pdo, $username)) {
            $errors["username_taken"] = "Username already taken";
        }
        if (is_email_registered($pdo, $email)) {
            $errors["email_used"] = "Email already registered";
        }

        require_once 'config_session.inc.php';

        if ($errors) {
            $_SESSION["errors_signup"] = $errors;
            header("Location: ./signup.php");
            die();
        }
        
        create_user($pdo, $usertype, $username, $password, $email, $phonenumber, $fullname, $country, $postcode, 
        $state, $city, $addressline, $verificationcode );

        header("Location: ./signup.php?signup=success"); //change the page to success

        $pdo = null;
        $stmt = null;
        die();

    } catch (PDOException $e) {
        die ("Query failed: " . $e->getMessage());
    }

} else {
    header("Location: ./signup.php");
    die();
}