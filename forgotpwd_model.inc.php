<?php

declare(strict_types=1);

function get_details(object $pdo, $email, $verificationcode) {
    $query = "SELECT * FROM users WHERE email = :email and verificationcode = :verificationcode;";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":verificationcode", $verificationcode);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function set_new_password(object $pdo, $newpassword, $email) {
    $query = "UPDATE users SET password = :newpassword WHERE email = :email;";

    $options = [
        'cost' => 12
    ];
    $hashedPwd = password_hash($newpassword, PASSWORD_BCRYPT, $options);

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":newpassword", $hashedPwd);
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}