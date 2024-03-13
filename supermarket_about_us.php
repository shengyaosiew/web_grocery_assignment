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
    <link rel="stylesheet" href="supermarket_about_us.css">
    <title>supermarket_about_us</title>
</head>

<body>
    <!------------------------------------------ Top section ---------------------------------------------------->
    <!------------------------------------------ Top navBar ---------------------------------------------------->
    <header class="header-main">
        <div class="header-main-logo">
            <img src="./supermarket_item_pic/logo.png" alt="HeyDaylogo">

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
    <main class="mid-container">
        <aside class="sidebar">
            <div class="sidebar-features">
                <nav>
                    <div>
                        <h3>Marketplace</h3>
                        <button class="marketPlace" onclick="window.location.href='product.php?'">
                            <img src="./supermarket_item_pic/market place.png" alt="Purchase Portal">
                            <span>Purchase portal</span>
                        </button>
                    </div>

                    <div>
                        <h3>Review</h3>
                        <button class="review" onclick="window.location.href='supermarket_feedback.php?'">
                            <img src="./supermarket_item_pic/feedback.png" alt="Feedback">
                            Feedback
                        </button>
                    </div>

                    <div>
                        <h3>My purchase</h3>
                        <button class="myPurchase" onclick="window.location.href='orderhistory.php?'">
                            <img src="./supermarket_item_pic/purchase history.png" alt="My purchase">
                            Order history
                        </button>
                    </div>
                </nav>
            </div>

            <div class="sidebar-companyInfo">
                <h3>Hey Day.Sdn Bhd</h3>
                <div class="sidebar-companyInfo-phoneNum">
                    <img src="./farmer_item_pic/phone_icon.svg" alt="HidayPhoneNum">
                    <a href="https://wa.link/58qs5p" target="_blank">(+60)16-888 9999</a>
                </div>

                <div class="sidebar-companyInfo-email">
                    <img src="./farmer_item_pic/email_icon.svg" alt="HidayEmail">
                    <a href="mailto:hiday@gmail.com">heyday@gmail.com</a>
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
