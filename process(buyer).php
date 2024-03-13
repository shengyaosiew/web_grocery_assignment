<?php
require_once("config_session.php");
include("conn.php");
$userId = $_SESSION["user_id"];

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve edited user information from $_POST
    $username = $_POST['user_name'];
    $password = $_POST['user_password'];
    $email = $_POST['user_email'];
    $phonenumber = $_POST['user_phonenumber'];
    $fullname = $_POST['user_fullname'];
    $country = $_POST['user_country'];
    $postcode = $_POST['user_postcode'];
    $state = $_POST['user_state'];
    $city = $_POST['user_city'];
    $addressline = $_POST['user_addressline'];

    $sql = "SELECT * FROM users WHERE userid = $userId;";
    $result = mysqli_query($con, $sql);

    // Check if the query returned a result
    if ($result) {
        $users = mysqli_fetch_assoc($result);
        $storedPassword = $users['password']; // The original hashed password from the database

        // Handle uploaded files (images and videos) using $_FILES
        $userpic = $_FILES['userpic'];

        // Check if a new cover photo has been uploaded
        if ($userpic['error'] === UPLOAD_ERR_OK) {
            // Move the uploaded cover photo to a destination directory and update the corresponding database field
            $userpicname = $userpic['name'];
            $userpicTmpName = $userpic['tmp_name'];
            $userpicDest = "uploads/" . $userpicname;
            move_uploaded_file($userpicTmpName, $userpicDest);

            // Update the cover photo field in the database
            $sql = "UPDATE users SET userpic = '$userpicDest' WHERE userid = $userId;";
            mysqli_query($con, $sql);
        }

        if (!empty($password) && !password_verify($password, $storedPassword)) {
            // A new password is provided and it doesn't match the stored hashed password, so rehash it
            $options = [
                'cost' => 12
            ];

            $newPassword = password_hash($password, PASSWORD_BCRYPT, $options);
        } else {
            // No new password provided or it matches the stored hashed password, keep the original hashed password
            $newPassword = $storedPassword;
        }

        // Construct an SQL query to update the user information with the hashed password
        $sql = "UPDATE users 
        SET username = '$username',
            password = '$newPassword',
            email = '$email',
            phonenumber = '$phonenumber',
            fullname = '$fullname',
            country = '$country',
            postcode = '$postcode',
            state = '$state',
            city = '$city',
            addressline = '$addressline'
        WHERE userid = $userId;";

        // Execute the SQL query
        if (mysqli_query($con, $sql)) {
            // Redirect back to the setting page after a successful update
            header("Location: setting(seller).php");
        } else {
            echo "Error updating user information: " . mysqli_error($con);
        }
    } else {
        echo "Query no result";
    }
} else {
    echo "Incomplete or missing POST data. Please fill in the form completely.";
}

mysqli_close($con);
?>
