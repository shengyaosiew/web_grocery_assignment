<?php

declare(strict_types=1);

function is_input_empty($username, $pwd ) {

    if (empty($username)|| empty($pwd))  {
        return true;
    } else {
        return false;
    }
}

function is_username_wrong(bool|array $result) {
    
    if (!$result) {
        return true;
    } else {
        return false;
    }
}

function is_password_wrong($pwd, $hashedPwd) {
    // Check if either the provided password is empty or the hashed password is null
    if ($pwd === '' || $hashedPwd === null) {
        return true; // Password is wrong
    } else {
        // Use password_verify to check if the provided password matches the hashed password
        return !password_verify($pwd, $hashedPwd);
    }
}
