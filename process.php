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

    $sql = "SELECT * FROM users WHERE user_id = $userId";
    $result = mysqli_query($con, $sql);

    // Check if the query returned a result
    if ($result) {
        $users = mysqli_fetch_assoc($result);

        // Now you can set the values for each form field
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
    }

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
        $sql = "UPDATE users SET userpic = '$userpicDest' WHERE userid = $userId";
        mysqli_query($con, $sql);
    }

    $options = [
        'cost' => 12
    ];

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT, $options);



    // Construct an SQL query to update the user information with the hashed password
    $sql = "UPDATE users 
    SET username = '$username',
        password = '$hashedPassword',
        email = '$email',
        phonenumber = '$phonenumber',
        fullname = '$fullname',
        country = '$country',
        postcode = '$postcode',
        state = '$state',
        city = '$city',
        addressline = '$addressline'
    WHERE userid = $userId";

    // Execute the SQL query
    if (mysqli_query($con, $sql)) {
        // Redirect back to the setting page after successful update
        header("Location: setting.php");
    } else {
        echo "Error updating user information: " . mysqli_error($con);
    }
}

mysqli_close($con);


// Dynamic profile settings (Explain by kah jun) --------------------------------------------------------------------------------------------------------
// Define database connection parameters
$host = 'localhost'; // Database host address
$dbname = 'webdevelopment'; // Database name
$dbusername = 'root'; // Database username
$dbpassword = ''; // Database password

try {
  // Create a PDO connection object using the defined parameters
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);

    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Dynamic user information
    $userId = $_SESSION["user_id"];

    //-------------- Profile Picture ---------------------------------
    $query = "SELECT userpic FROM users WHERE userid = :id;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":id", $userId);
    $stmt->execute();

    // Check if the query returned a result
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (isset($row['userpic'])) {
            $userProfilePicture = $row['userpic'];
        } 
    } 
    //Default picture
    if (empty($userProfilePicture)) {
        $userProfilePicture = './farmer_item_pic/farmer_profile.png';
    }

    // -------------- User Information ---------------------------
    $query = "SELECT username, phonenumber FROM users WHERE userid = :id;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":id", $userId);
    $stmt->execute();

    // Check if the query returned a result
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (isset($row['username'])) {
            $username = $row['username'];
        } 
        if (isset($row['phonenumber'])) {
            $userPhone = $row['phonenumber'];
        } 
    // Set default values for username and phone
    } else {
        $username = 'John Sinar';
        $userPhone = '(+601) 18 888 9988';
    }

} catch (PDOException $e) {
    // Handle connection failure and display error message
    die("Connection failed: " . $e->getMessage());
}
?>