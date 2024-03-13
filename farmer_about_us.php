<?php
require_once "config_session.php";
$tempUserId = $_SESSION['user_id'];



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
    <link rel="stylesheet" href="farmer_about_us.css">
    <title>Document</title>
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
    <main class="mid-container">
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

                            <button class="customerFeedback" onclick="window.location.href='farmer_feedback.php?'">
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

        <div class="aboutus">
            <h2>About Us</h2>
            <hr style="border: 1px solid black; margin-top: -10px;">
        
            <!-- Mission Section -->
            <section class="aboutus-content" id="mission">
                <h3>Mission</h3>
                <p>At HeyDay, our mission is to promote sustainable agriculture. Our objectives include:</p>
                <ul>
                    <li>Ensuring an adequate food supply for present and future generations without causing environmental harm.</li>
                    <li>Supporting farmers and organizations in achieving sustainable growth, leading to increased living standards and reduced poverty.</li>
                    <li>Providing resources like a communication platform for buyers and sellers, as well as essential information like weather forecasts.</li>
                </ul>
            </section>
        
            <!-- Values and Principles Section -->
            <section class="aboutus-content" id="values">
                <h3>Values and Principles</h3>
                <ul>
                    <li>Commitment to sustainability</li>
                    <li>Environmental responsibility</li>
                    <li>Supporting farmers and communities</li>
                    <!-- Add more values/principles as applicable -->
                </ul>
            </section>
        
            <!-- Company History Section -->
            <section class="aboutus-content" id="history"> 
                <h3>Company History</h3>
                <p>HeyDay is a new startup business that is committed to revolutionizing the agricultural sector. Although we are a new company, we have ambitious goals and a dedicated team.</p>
            </section>
        
            <!-- Team Members Section -->
            <section class="aboutus-content" id="member">
                <h3>Our Team</h3>
                    <ul>
                        <li>
                            <strong>Chong Kah Jun</strong> - Co-Founder and CEO
                            <p>Chong is responsible for leading the company and setting its overall direction. With a background in business strategy, he ensures our vision is realized.</p>
                        </li>
                        <li>
                            <strong>Sin Boon Leon</strong> - Chief Technology Officer
                            <p>Sin oversees the technical development of our products, ensuring they meet the highest standards of quality and innovation.</p>
                        </li>
                        <li>
                            <strong>Siew Sheng Yao</strong> - Marketing Manager
                            <p>Siew is in charge of our marketing efforts, creating campaigns to reach our target audience and build brand awareness.</p>
                        </li>
                        <li>
                            <strong>Yong Lee Wai</strong> - Customer Support Specialist
                            <p>Yong provides exceptional support to our customers, addressing their inquiries and ensuring a positive experience with our products.</p>
                        </li>
                    </ul>
            </section>
        </div>        
    </main>
</body>
</html>
