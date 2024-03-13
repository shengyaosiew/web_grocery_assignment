<?php

declare(strict_types=1); // Enforce strict typing for variables and function parameters

function get_username(object $pdo, $username) {
    // Prepare SQL query to retrieve username from Users table
    $query = "SELECT username FROM users WHERE username = :username;";

    // Prepare the statement using the PDO object
    $stmt = $pdo->prepare($query);

    // Bind the :username placeholder to the provided username parameter
    $stmt->bindParam(":username", $username);

    // Execute the prepared query against the database
    $stmt->execute();

    // Fetch the first row from the query result as an associative array
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Return the fetched result array containing the username value or an empty array
    return $result;
}

function get_email(object $pdo, $email) {
    $query = "SELECT email FROM users WHERE email = :email;";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function set_user(object $pdo, $usertype, $username, $password, $email, $phonenumber, $fullname, $country, $postcode, $state, $city, $addressline, $verificationcode ) {
    $query = "INSERT INTO users(usertype, username, password, email, phonenumber, fullname, country, postcode, state, city, addressline, verificationcode ) VALUES (:usertype, :username, :password, :email, :phonenumber, :fullname, :country, :postcode, :state, :city, :addressline, :verificationcode);";
    $stmt = $pdo->prepare($query);

    $options = [
        'cost' => 12
    ];

    $hashedPwd = password_hash($password, PASSWORD_BCRYPT, $options);

    $stmt->bindParam(":usertype", $usertype);
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":password", $hashedPwd);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":phonenumber", $phonenumber);
    $stmt->bindParam(":fullname", $fullname);
    $stmt->bindParam(":country", $country);
    $stmt->bindParam(":postcode", $postcode);
    $stmt->bindParam(":state", $state);
    $stmt->bindParam(":city", $city);
    $stmt->bindParam(":addressline", $addressline);
    $stmt->bindParam(":verificationcode", $verificationcode);
 

    // Execute the prepared query
    $stmt->execute();
}
