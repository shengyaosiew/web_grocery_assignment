<?php
require_once "config_session.php";
$userId = $_SESSION["user_id"];


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

// Retrieve the orders item  --------------------------------------------------------------------------------------------------------
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Define the SQL query for the inner join
    $query = "SELECT o.*, p.* FROM `orders` AS o
              INNER JOIN product AS p ON o.product_id = p.product_ID
              WHERE o.user_id = :userId";

    // Prepare and execute the SQL query
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":userId", $userId);
    $stmt->execute();

    // Fetch the results into an associative array
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Now, $results contains the data from both "order" and "product" tables for the user.

    // You can loop through $results to access the data:
    foreach ($results as $row) {
        $orderId = $row['order_id'];
        $productName = $row['product_name'];
    }


} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="supermarket_feedback.css">
    <title>supermarket_about_us</title>
</head>

<body>
    <!------------------------------------------ Top section ---------------------------------------------------->
    <!------------------------------------------ Top navBar ---------------------------------------------------->
    <header class="header-main">
        <div class="header-main-logo">
            <img src="./Image/logo.png" alt="HeyDaylogo">

            <div class="farmerbackground">
                <div class="farmerword">Supermarket</div>
            </div>

            <nav class="header-main-topNav">
                <ul>
                    <li><a href="supermarket_about_us.php"><img src="./Image/AboutUs_icon.png"></a></li>
                    <li><a href="product.php"><img src="./Image/home_icon.png"></a></li>
                    <li><a href="setting(buyer).php"><img src="./Image/setting_icon.png"></a></li>
                    <li><a href="./logout.php"> <img src="./farmer_item_pic/logout.png"></a></li>
                </ul>
            </nav>
        </div>

        <div class="header-main-profile">
            <ul>
                <li>
                    <div id="userName"><?php echo $username; ?></div>
                </li>
                <li>
                    <div id="userPhone"><?php echo $userPhone; ?></div>
                </li>
                <li>
                    <div id="userId">ID: <?php echo $userId; ?></div>
                </li>
            </ul>

            <div class="header-main-profile-logo"><img src="<?php echo $userProfilePicture; ?>" alt="UserProfile"></div>
        </div>
    </header>
    <!------------------------------------------ Mid section ---------------------------------------------------->
    <!------------------------------------------ Side bar ---------------------------------------------------->
    <main class="mid-section">
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
                        <button class="review" onclick="window.location.href='supermarket_feedback.php'">
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

        <section class="feedback-form">
        <h2>Provide Feedback</h2>
        
        <h3>Item(s) you purchased:</h3>
        <form  action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="form-group">
                <label for="product_name">Product Name:</label>
                <select id="product_name" name="product_name" required>
                    <option value="">Select a product</option>
                    <?php
                    foreach ($results as $row) {
                        $productName = $row['product_name'];
                        // Output each product name as an option in the dropdown
                        // The =\" and \" are used to escape double quotes
                        echo "<option value=\"$productName\">$productName</option>"; 
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="satisfied_status">Satisfied Status:</label>
                <div class="radio-buttons">
                    <label>
                        <input type="radio" name="satisfied_status" value="excellent" required>
                        Excellent
                    </label>
                    <label>
                        <input type="radio" name="satisfied_status" value="good" required>
                        Good
                    </label>
                    <label>
                        <input type="radio" name="satisfied_status" value="okay" required>
                        Okay
                    </label>
                    <label>
                        <input type="radio" name="satisfied_status" value="bad" required>
                        Bad
                    </label>
                    <label>
                        <input type="radio" name="satisfied_status" value="terrible" required>
                        Terrible
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label for="comment">Comment:</label>
                <textarea id="comment" name="comment" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <button type="submit">Submit Feedback</button>
            </div>
        </form>
    </section>

    </main>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST["product_name"];
    $satisfied_status = $_POST["satisfied_status"];
    $comment = $_POST["comment"];
    $user_id = $_SESSION["user_id"];

    $query = "SELECT product_ID FROM product WHERE product_name = :product_name";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":product_name", $product_name);
    $stmt->execute();

    // Fetch the product_id
    $product_id = $stmt->fetchColumn();

    if ($product_id) {
        $query = "INSERT INTO feedback (user_id, product_id, satisfied_status, comment, feedback_date) 
                  VALUES (:user_id, :product_id, :satisfied_status, :comment, NOW())";

        // Bind the values and execute the query using the existing PDO connection
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":product_id", $product_id);
        $stmt->bindParam(":satisfied_status", $satisfied_status);
        $stmt->bindParam(":comment", $comment);

        // Execute the query
        $stmt->execute();
        echo "<script>alert('Feedback submitted successfully!');</script>";
    } else {
        echo "Selected product not found in the database.";
    }
}