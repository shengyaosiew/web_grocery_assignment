<?php

declare(strict_types=1);

function is_request_empty($email, $verificationcode, $newpassword ) {

    if (empty($email) || empty($verificationcode)|| empty($newpassword))  {
        return true;
    } else {
        return false;
    }
}

function is_email_or_verificationcode_wrong(bool|array $result) {
    
    if (!$result) {
        return true;
    } else {
        return false;
    }
}

function create_new_password(object $pdo, $newpassword, $email) {
    set_new_password($pdo, $newpassword, $email);
}
