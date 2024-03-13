<?php

declare(strict_types=1);

function is_email_invalid($email) {
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        // if email is invalid
        return true;
    } else {
        return false;
    }
}

function is_username_taken(object $pdo, $username) {
    if (get_username($pdo, $username)) {
        return true;
    } else {
        return false;
    }
    
}

function is_email_registered(object $pdo, $email) {
    if (get_email($pdo, $email)) {
        return true;
    } else {
        return false;
    }
    
}

function create_user(object $pdo, $usertype, $username, $password, $email, $phonenumber, $fullname, $country, $postcode, 
$state, $city, $addressline, $verificationcode) {
    
    set_user($pdo, $usertype, $username, $password, $email, $phonenumber, $fullname, $country, $postcode, 
    $state, $city, $addressline, $verificationcode);
}