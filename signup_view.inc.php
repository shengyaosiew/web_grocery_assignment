<?php
declare(strict_types=1);

function check_signup_errors() {
    if (isset($_SESSION['errors_signup'])) {
        $errors = $_SESSION['errors_signup'];

        echo '<script>alert("';
        foreach ($errors as $error) {
            echo $error . "\\n";
        }
        echo '");</script>';

        unset($_SESSION['errors_signup']);

    } else if (isset($_GET["signup"]) && $_GET["signup"] === "success") {
        echo '<script>alert("Signup success!");</script>';
    }   
}