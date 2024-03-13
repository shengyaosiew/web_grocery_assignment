<?php
require_once("config_session.php");

$userId = $_SESSION["user_id"];

// Establish a database connection (you might need to adjust the connection details)
$host = 'localhost';
$dbname = 'webdevelopment';
$dbusername = 'root';
$dbpassword = '';

try {
    $con = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve user data from the database
    $stmt = $con->prepare("SELECT * FROM users WHERE userid = :user_id");
    $stmt->bindParam(":user_id", $userId);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $users = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        echo "The list is empty";
    }
} catch (PDOException $e) {
    // Handle database connection errors
    echo "Database Connection Error: " . $e->getMessage();
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="setting(seller).css">
    <title>Document</title>
</head>
<body>
    <!------------------------------------------ Top section ---------------------------------------------------->
    <!------------------------------------------ Top navBar ---------------------------------------------------->
<section>
    <header class="header-main">
        <div class="header-main-logo">
            <img src="./farmer_item_pic/logo.png" alt="HeyDaylogo">

            <div class="farmerbackground"></div>
            <div class="farmerword">Farmer</div>
            <nav class="header-main-topNav">
                <ul>
                    <li><a href="supermarket_about_us.php"><img src="./farmer_item_pic/AboutUs_icon.png"></a></li>
                    <li><a href="farmer_order.php"><img src="./farmer_item_pic/home_icon.png"></a></li>
                    <li><a href="#"><img src="./farmer_item_pic/setting_icon.png"></a></li>
                    <li><a href="./logout.php"> <img src="./farmer_item_pic/logout.png"></a></li>
                </ul>
            </nav>
        </div>

        <div class="header-main-profile">
            <ul>
                <li>
                    <div id="username"><?php echo $users['username']; ?></div>
                </li>
                <li>
                    <div id="phonenumber"><?php echo $users['phonenumber']; ?></div>
                </li>
                <li>
                    <div id="userid">ID: <?php echo $users['userid']; ?></div>
                </li>
            </ul>

            <div class="header-main-profile-logo"><img src="<?php echo $users['userpic']; ?>" alt="UserProfile"></div>
        </div>
    </header>


    <!------------------------------------------ Mid section ---------------------------------------------------->
    <!------------------------------------------ Side bar ---------------------------------------------------->
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
                <a class="heyday-email" href="mailto:hiday@gmail.com">heydayA@gmail.com</a>
            </div>
        </div>
    </aside>
    <main>
        <div class="container">
            <h1>User Settings</h1>
            <form action="process(seller).php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="user_id" value="<?php echo $_SESSION["user_id"]; ?>">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input id="user_name" type="text" name="user_name" placeholder="username" value="<?php echo $users['username']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input id="user_password" type="password" name="user_password" placeholder="Enter your password / Leave blank to remain the same password">
                </div>


                <div class="form-group">
                    <label for="email">Email:</label>
                    <input id="user_email" type="email" name="user_email" value="<?php echo $users['email']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="phonenumber">Phone Number:</label>
                    <input id="user_phonenumber" type="text" name="user_phonenumber" value="<?php echo $users['phonenumber']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="FullName">Full Name:</label>
                    <input id="user_fullname" type="text" name="user_fullname" value="<?php echo $users['fullname']; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="country">Country:</label>
                    <input id="user_country" type="text" name="user_country" value="<?php echo $users['country']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="postcode">Postcode:</label>
                    <input id="user_postcode" type="text" name="user_postcode"value="<?php echo $users['postcode']; ?>" require>
                </div>

                <div class="form-group">
                    <label for="state">State:</label>
                    <input id="user_state" type="text" name="user_state"value="<?php echo $users['state']; ?>" require>
                </div>

                <div class="form-group">
                    <label for="city">City:</label>
                    <input id="user_city" type="text" name="user_city" value="<?php echo $users['city']; ?>" require>
                </div>

                <div class="form-group">
                    <label for="addressline">Address Line 1:</label>
                    <input id="user_addressline" type="text" name="user_addressline" value="<?php echo $users['addressline']; ?>" require>
                </div>

                <div class="form-group">
                    <label for="userpic">User Picture:</label>
                    <input type="file" name="userpic">
                </div>

                <input type="submit" value="Save">
            </form>
        </div>
    </main>
</section>
</body>
</html>