<?php
require_once("./config_session.php");

include 'conn.php';
$userId = $_SESSION['user_id'];

// Retrieve product information from query parameters
$product_name = $_GET['product_name'] ?? '';
$product_price = $_GET['product_price'] ?? '';
$product_image = $_GET['product_image'] ?? '';
$product_id = $_GET['product_ID'] ?? '';
$product_quantity = 1;
$user_id = $userId;


// Retrieve video and image paths from your database
$select_product = mysqli_query($con, "SELECT * FROM `product` WHERE product_name = '$product_name'");
if (mysqli_num_rows($select_product) > 0) {
    $fetch_product = mysqli_fetch_assoc($select_product);
    $video_path = $fetch_product['video_button'];
    $image_path = $fetch_product['coverPhoto'];
    $image_path2 = $fetch_product['image1'];
    // Retrieve additional product details
    $product_id = $fetch_product['product_ID'];
    $product_price = $fetch_product['product_price'];
    $product_weight = $fetch_product['product_weight'];
    $product_stock = $fetch_product['product_stock'];
    $product_description = $fetch_product['product_description'];
}

if (isset($_POST['add_to_cart'])) { // Check if the form is submitted
    $select_cart = mysqli_query($con, "SELECT * FROM `cart` WHERE name = '$product_name'");

    if (mysqli_num_rows($select_cart) > 0) {
        echo '<script>alert("Product already added to cart!");</script>';
    } else {
        $insert_product = mysqli_query($con, "INSERT INTO cart (user_id, product_id, name, price, image, quantity) 
        VALUES ('$user_id', '$product_id', '$product_name', '$product_price', '$product_image', '$product_quantity')");
        echo '<script>alert("Product added to cart successfully!");</script>';
    }
}


//--------- check out part ------------------------------------------------------------------------------

// Initialize an empty array to store product IDs if it doesn't exist
if (!isset($_SESSION['cart_product_ids'])) {
    $_SESSION['cart_product_ids'] = array();
}

if (isset($_POST['add_to_cart'])) {
    // Add the product's ID to the session variable
    $_SESSION['cart_product_ids'][] = $product_id;
}


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
        $userProfilePicture = './Image/supermarket_img.png';
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


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link rel="stylesheet" href="information.css">
    <title>information</title>
</head>

<body>

    <!------------------------------------------ Top section ---------------------------------------------------->
    <!------------------------------------------ Top navBar ---------------------------------------------------->
    <header class="header-main">
        <div class="header-main-logo">
            <img src="Image/logo.png" alt="HeyDaylogo">

            <div class="farmerbackground"></div>
            <div class="farmerword">Supermarket</div>

            <nav class="header-main-topNav">
                <ul>
                    <li><a href="supermarket_about_us.php"><img src="Image/AboutUs_icon.png"></a></li>
                    <li><a href="product.php"><img src="Image/home_icon.png"></a></li>
                    <li><a href="setting(buyer).php"><img src="Image/setting_icon.png"></a></li>
                    <li><a href="./logout.php"> <img src="./farmer_item_pic/logout.png"></a></li>
                </ul>
            </nav>
            <?php

            $select_rows = mysqli_query($con, "SELECT * FROM `cart`") or die('query failed');
            $row_count = mysqli_num_rows($select_rows);

            ?>

            <a href="cart.php" class="cart-icon"><i class="fas fa-cart-shopping"></i><span><?php echo $row_count; ?></span></a>
        </div>




        <div class="header-main-profile">
            <ul>
                <li><div id="userName"><?php echo $username; ?></div></li>
                <li><div id="userPhone"><?php echo $userPhone; ?></div></li>
                <li><div id="userId">ID: <?php echo $userId; ?></div></li>
            </ul>

            <div class="header-main-profile-logo"><img src="<?php echo $userProfilePicture; ?>" alt="UserProfile"></div>
        </div>
    </header>
    <!------------------------------------------ Mid section ---------------------------------------------------->
    <!------------------------------------------ Side bar ---------------------------------------------------->
    <aside class="sidebar">
        <div class="sidebar-features">
            <nav>
                <div>
                    <h3>Marketplace</h3>
                    <button class="marketPlace" onclick="window.location.href='product.php?'">
                        <img src="Image/market place.png" alt="Purchase Portal">
                        <span>Purchase portal</span>
                    </button>
                </div>

                <div>
                    <h3>Review</h3>
                    <button class="review" onclick="window.location.href='supermarket_feedback.php?'">
                        <img src="Image/feedback.png" alt="Feedback">
                        Feedback
                    </button>
                </div>

                <div>
                    <h3>My purchase</h3>
                    <button class="myPurchase" onclick="window.location.href='orderhistory.php?'">
                        <img src="Image/purchase history.png" alt="My purchase">
                        Order history
                    </button>
                </div>
            </nav>
        </div>

        <div class="sidebar-companyInfo">
            <h3>Hey Day.Sdn Bhd</h3>
            <div class="sidebar-companyInfo-phoneNum">
                <img src="Image/phone_icon.svg" alt="HidayPhoneNum">
                <a class="heyday-phone" href="https://wa.link/58qs5p" target="_blank">(+60)16-888 9999</a>

            </div>

            <div class="sidebar-companyInfo-email">
                <img src="Image/email_icon.svg" alt="HidayEmail">
                <a class="heyday-email" href="mailto:hiday@gmail.com">hiday@gmail.com</a>
            </div>
        </div>
    </aside>




    <div class="centered-box">
        <!-- Display either video or image based on selection -->
        <video id="video" controls loop autoplay muted width="100%" height="auto">
            <source src="<?php echo $video_path; ?>" type="video/mp4">
        </video>
        <div id="image-container" class="centered-image-container" style="display:none;">
            <img id="image" class="fit-image" src="<?php echo $image_path; ?>" alt="Product Image">
        </div>
        <div class="small-boxes-container">
            <!--  onclick handlers to change content -->
            <div class="small-box" onclick="changeContent(0)" style="border: 2px solid black;">
                <img src="Image/play button.png" alt="Image description" class="fit-image">
            </div>
            <div class="small-box" onclick="changeContent(1)">
                <img src="<?php echo $image_path; ?>" alt="Product Image" class="fit-image">
            </div>
            <div class="small-box" onclick="changeContent(2)">
                <img src="<?php echo $image_path2; ?>" alt="Product Image" class="fit-image">
            </div>
        </div>
    </div>




    <!-- product details here -->
    <div class="text">
        <p>
            Name: <?php echo $product_name; ?><br><br>
            ID: <?php echo $product_id; ?><br><br>
            Price: RM<?php echo $product_price; ?>/each <br><br>
            Weight: <?php echo $product_weight; ?>g<br><br>
            Stock: <?php echo $product_stock; ?> Available<br><br>
            Description: <?php echo $product_description; ?><br><br>
        </p>

        <form action="" method="post">
            <!-- Add to Cart button -->
            <div class="add-to-cart">
                <button type="submit" class="btn" name="add_to_cart">Add to Cart</button>
            </div>
        </form>


    </div>



    <script src="script.js"></script>

</body>

</html>
