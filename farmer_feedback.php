<?php
require_once("config_session.php");

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



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="farmer_feedback.css">
    <title>farmer_addnewitem</title>
</head>
<body>
    
    <!------------------------------------------ Top section ---------------------------------------------------->
    <!------------------------------------------ Top navBar ---------------------------------------------------->
    <header class="header-main">
        <div class="header-main-logo">
            <img src="./farmer_item_pic/logo.png" alt="HeyDaylogo">

            <div class="farmerbackground"></div>
            <div class="farmerword">Farmer</div>

            <nav class="header-main-topNav">
                <ul>
                    <li><a href="farmer_about_us.php"><img src="./farmer_item_pic/AboutUs_icon.png"></a></li>
                    <li><a href="farmer_order.php"><img src="./farmer_item_pic/home_icon.png"></a></li>
                    <li><a href="setting(seller).php"><img src="./farmer_item_pic/setting_icon.png"></a></li>
                    <li><a href="./logout.php"> <img src="./farmer_item_pic/logout.png"></a></li>
                </ul>
            </nav>
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
    <div class="container">
        <aside class="sidebar" >
            <div class="sidebar-features">
                <nav>
                    <div>
                        <h3>Product</h3>
                            <button class="myItem" onclick="window.location.href='farmer_viewproduct.php?'">
                                <img src="./farmer_item_pic/myitem_icon.png" alt="My Item">
                                My Item
                            </button>

                            <button class="addNewItem" onclick="window.location.href='farmer_addnewitem.php?'">
                                <img src="./farmer_item_pic/addnewitem_icon.png" alt="Add New item">
                                Add New item
                            </button>

                            <button class="customerFeedback">
                                <img src="./farmer_item_pic/customerfeedback_icon.png" alt="Customer Feedback">
                                Customer Feedback
                            </button>
                    </div>
                    
                    <div>
                        <h3>Order</h3>
                            <button class="orderList" onclick="window.location.href='farmer_order.php?'">
                                <img src="./farmer_item_pic/order_list_icon.png" alt="Order List">
                                Order List
                            </button>
                    </div>

                </nav>
            </div>

            <div class="sidebar-companyInfo">
                <h3>Hey Day.Sdn Bhd</h3>
                <div class="sidebar-companyInfo-phoneNum">
                    <img src="./farmer_item_pic/phone_icon.svg" alt="HidayPhoneNum">
                    <a class="heyday-phone" href="https://wa.link/58qs5p" target="_blank">(+60)16-888 9999</a>
                </div>

                <div class="sidebar-companyInfo-email">
                    <img src="./farmer_item_pic/email_icon.svg" alt="HidayEmail">
                    <a class="heyday-email" href="mailto:hiday@gmail.com">heyday@gmail.com</a>
                </div>
            </div>
        </aside>

        <main class="main-content">

            <h1>Customer Feedback</h1>

            <?php
            $userFeedbackData = array(); // An array to store feedback data with product and payment details

            $query = "SELECT p.product_name, f.satisfied_status, f.comment, f.feedback_date, o.paymentname
                    FROM feedback f
                    INNER JOIN product p ON f.product_id = p.product_ID
                    INNER JOIN `orders` o ON f.product_id = o.product_id
                    WHERE p.userid = :user_id;";
                        
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(":user_id", $userId);
            $stmt->execute();
                        
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $userFeedbackData[] = $row;
                }
            }
            
            // Now, you can display the data in a table
            echo '<table border="1">';
            echo '<tr><th>Product Name</th><th>Payment Name</th><th>Satisfied Status</th><th>Comment</th><th>Feedback Date</th></tr>';
                        
            foreach ($userFeedbackData as $feedback) {
                echo '<tr>';
                echo "<td>{$feedback['product_name']}</td>";
                echo "<td>{$feedback['paymentname']}</td>"; // Use the correct column name here
                echo "<td>{$feedback['satisfied_status']}</td>";
                echo "<td>{$feedback['comment']}</td>";
                echo "<td>{$feedback['feedback_date']}</td>";
                echo '</tr>';
            }
                        
            echo '</table>';
            ?>
        </main>
    </div>
</body>
</html>