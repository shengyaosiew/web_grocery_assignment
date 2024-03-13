<?php
function getDynamicprofile($userId) {
    $host = 'localhost'; // Database host address
    $dbname = 'webdevelopment'; // Database name
    $dbusername = 'root'; // Database username
    $dbpassword = ''; // Database password

    try {
        // Create a PDO connection object using the defined parameters
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);

        // Set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Initialize variables
        $userProfilePicture = './farmer_item_pic/farmer_profile.png';
        $username = 'John Sinar';
        $userPhone = '(+601) 18 888 9988';

        // Get user profile picture
        $query = "SELECT userpic FROM users WHERE userid = :id;";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":id", $userId);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (isset($row['userpic'])) {
                $userProfilePicture = $row['userpic'];
            }
        }

        // Get user information
        $query = "SELECT username, phonenumber FROM users WHERE userid = :id;";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":id", $userId);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (isset($row['username'])) {
                $username = $row['username'];
            }
            if (isset($row['phonenumber'])) {
                $userPhone = $row['phonenumber'];
            }
        }

        $pdo = null; // Close PDO connection

        return [
            'userProfilePicture' => $userProfilePicture,
            'username' => $username,
            'userPhone' => $userPhone
        ];
    } catch (PDOException $e) {
        // Handle connection failure and display error message
        die("Connection failed: " . $e->getMessage());
    }
}