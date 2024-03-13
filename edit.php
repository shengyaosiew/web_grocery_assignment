<?php
require_once("config_session.php");
include("conn.php");

$userId = $_SESSION['user_id'];

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['id'])) {
    $productID = $_GET['id'];

    $sql = "SELECT * FROM product WHERE product_ID = $productID";
    $result = mysqli_query($con, $sql);

    if (!$result) {
        die("Query failed: " . mysqli_error($con));
    }

    $product = mysqli_fetch_assoc($result);
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
    <title>Edit Product</title>
    <link rel="stylesheet" href="edit.css">

    <script>
        function showConfirmation() {
            if (confirm('Change this record?')) {
                // User clicked OK in the confirmation dialog
                // You can perform your action here
                alert('Record Updated.');
            } else {
                // User clicked Cancel in the confirmation dialog
                alert('Update canceled.');
            }
        }
    </script>
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

                        <button class="customerFeedback" onclick="window.location.href ='farmer_feedback.php?'">
                            <img src="./farmer_item_pic/customerfeedback_icon.png" alt="Customer Feedback">
                            Customer Feedback
                        </button>
                </div>
                
                <div>
                    <h3>Order</h3>
                        <button class="orderList">
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


<!-------------------------------------------------Content of right frame -------------------------------------->


    <!-- Content of right frame -->
    <form method="post" action="update.php" enctype="multipart/form-data">
        <input type="hidden" name="product_id" value="<?php echo $product['product_ID']; ?>">

        <div class="content-frame">
            <div class="tittle-addnewitem">Edit Item</div> <br>
            <div class="basicinfo-word">Basic Information</div> <br>

            <div class="productimage-word">Product Image</div> <br>

            <!-- Picture and video edit containers -->
            <div class="addbutton-container">
                <!-- Cover Photo edit container -->
                <button id="addbutton-frame0" type="button" onclick="document.getElementById('coverPhotoInput').click()">
                    <img class="add-icon" src="./farmer_item_pic/add_icon.png" alt="Icon"> <br>
                    <div class="cover-photo-word">Edit Cover Photo</div>
                </button>
                <input type="file" id="coverPhotoInput" name="coverPhoto" accept="image/jpeg, image/png, image/gif, image/jpg" style="display: none">

                <!-- Image 1 edit container -->
                <button id="addbutton-frame1" type="button" onclick="document.getElementById('image1Input').click()">
                    <img class="add-icon" src="./farmer_item_pic/add_icon.png" alt="Icon"> <br>
                    <div class="cover-photo-word">Edit Image 1</div>
                </button>
                <input type="file" id="image1Input" name="image1" accept="image/jpeg, image/png, image/gif, image/jpg" style="display: none">
            </div>

                <div class="productvideo-word">Product Video</div>
                <!-- Video edit container -->
                <div class="videobutton-container">
                    <button class="addbutton-frame2" id="addbutton-frame2" type="button" onclick="document.getElementById('videoInput').click()">
                        <img class="addvideo-icon" src="./farmer_item_pic/add_icon.png" alt="Icon">
                        <div class="cover-photo-word">Edit Video</div>
                    </button>
                    <input type="file" id="videoInput" name="video_button" accept="video/mp4" style="display: none">
                    <p class="video-note">1. Size: Max 30MB, resolution should not exceed 1280x1280px <br>
                        2. Duration: 10s-60s <br>
                        3. Format: MP4 <br>
                        4. Note: You can publish this listing while the video is being processed. <br>Video will be shown in the listing once successfully processed.</p>
                </div>
            

            <!-- Product name edit container -->
            <div class="product-name-container">
                <div class="productvideo-word">*Product Name</div>
                <input id="product-name-input" type="text" name="product_name" value="<?php echo $product['product_name']; ?>" required>
            </div>

            <!-- Product description edit container -->
            <div class="product-description-container">
                <div class="productvideo-word">*Product <br>Description</div>
                <textarea id="product-description-input" name="product_description" maxlength="1000" required><?php echo $product['product_description']; ?></textarea>
            </div>

            <div class="salesinfo-word">Sales Information</div> <br>

            <!-- Product price edit container -->
            <div class="product-price-container">
                <div class="productprice-word">*Price (RM)</div>
                <input id="product-price" type="text" name="product_price" placeholder="00.00" value="<?php echo $product['product_price']; ?>" required>
            </div>

            <!-- Product stock edit container -->
            <div class="product-stock-container">
                <div class="productprice-word">*Stock</div>
                <input id="product-stock" type="number" name="product_stock" step="1" min="0" placeholder="0" value="<?php echo $product['product_stock']; ?>" required>
            </div>

            <div class="salesinfo-word">Shipping</div> <br>

            <!-- Product weight edit container -->
            <div class="product-weight-container">
                <div class="productprice-word">*Weight (g)</div>
                <input id="product-weight" type="text" name="product_weight" placeholder="00.00" value="<?php echo $product['product_weight']; ?>" required>
            </div>


            <!-- Product submit and reset button container -->
            <div class="product-button-container">
                <button class="back-button" onclick="window.location.href='farmer_viewproduct.php?'" >Back</button>
                <button class="submit-button" onclick="showConfirmation()">Submit</button>
            </div>

        </div>
    </form>

</body>
</html>

