<?php
require_once "config_session.php";

include 'conn.php';
$userId = $_SESSION['user_id'];

// Query to fetch data from the database
$query = "SELECT o.date, p.product_name, o.total_quantity, (o.total_quantity * p.product_price) AS total_price
          FROM orders o
          JOIN product p ON o.product_id = p.product_ID
          WHERE o.user_id = $userId
          ORDER BY o.date";  // Ensure results are ordered by date

$result = mysqli_query($con, $query);

// Check if the query was executed successfully
if ($result === false) {
    die("Query execution failed: " . mysqli_error($con));
}

// Initialize a variable to track the previous "Order Date"
$previousDate = null;

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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>orderhistory</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="orderhistory.css">
</head>

<body>

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

    <form method="post" action="orderhistory.php" enctype="multipart/form-data">
        <!-- Content of right frame -->
        <div class="content-frame">
            <div class="title-addnewitem">Order history</div> <br>
            <table class="product-table">
                <tr>
                    <th>Order Date</th>
                    <th>Product Name</th>
                    <th>Total Quantity</th>
                    <th>Total Price</th>
                </tr>


                <?php
                // Loop through the query results and display them in the table
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    // Check if the "Order Date" has changed
                    if ($row['date'] != $previousDate) {
                        echo "<td>" . $row['date'] . "</td>";
                        $previousDate = $row['date'];
                    } else {
                        echo "<td></td>"; // Empty cell for repeated dates
                    }
                    echo "<td>" . $row['product_name'] . "</td>";
                    echo "<td>" . $row['total_quantity'] . "</td>";
                    echo "<td>" . $row['total_price'] . "</td>";
                    echo "</tr>";
                }

                // Close the database connection
                mysqli_close($con);
                ?>
            </table>
        </div>
    </form>



    <script src="script.js"></script>
</body>

</html>
