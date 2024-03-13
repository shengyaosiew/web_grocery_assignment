<?php
require_once("config_session.php");
include("conn.php");


$userId = $_SESSION['user_id'];

$targetDir = "uploads/"; 


// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Initialize variables to store file paths
    $coverPhotoPath = "";
    $image1Path = "";
    $videoPath = "";

    // Check if "coverPhoto" was uploaded successfully
    if (isset($_FILES["coverPhoto"]) && $_FILES["coverPhoto"]["error"] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES["coverPhoto"]["name"]);

        if (move_uploaded_file($_FILES["coverPhoto"]["tmp_name"], $targetFile)) {
            $coverPhotoPath = $targetFile;
        } else {
            echo "Cover Photo upload failed.";
        }
    }

    // Check if "image1" was uploaded successfully
    if (isset($_FILES["image1"]) && $_FILES["image1"]["error"] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES["image1"]["name"]);

        if (move_uploaded_file($_FILES["image1"]["tmp_name"], $targetFile)) {
            $image1Path = $targetFile;
        } else {
            echo "Image 1 upload failed.";
        }
    }

    

        // Check if "video_button" was uploaded successfully
        if (isset($_FILES["video_button"]) && $_FILES["video_button"]["error"] === UPLOAD_ERR_OK) {
            $filename = $_FILES["video_button"]["name"];
            $tempname = $_FILES["video_button"]["tmp_name"];
            $folder = $targetDir . $filename;
        
            if (move_uploaded_file($tempname, $folder)) {
                $videoPath = $folder;
            } else {
                $uploadError = "Video upload failed.";
            }
        }
        
    




    // Check if "userid" is set in the POST data before using it
    if (isset($_POST["userid"])) {
        // Escape and validate user input (e.g., product_name, product_description, product_price)
        $product_name = mysqli_real_escape_string($con, $_POST["product_name"]);
        $product_description = mysqli_real_escape_string($con, $_POST["product_description"]);
        $product_price = floatval($_POST["product_price"]); // Ensure it's a float
        $product_stock = intval($_POST["product_stock"]); // Ensure it's an integer
        $product_weight = floatval($_POST["product_weight"]); // Ensure it's a float

        // Insert the data into the product table
        $sql = "INSERT INTO product (coverPhoto, image1, video_button, product_name, product_description, product_price, product_stock, product_weight, userid) 
                VALUES ('$coverPhotoPath', '$image1Path', '$videoPath', '$product_name', '$product_description', '$product_price', '$product_stock', '$product_weight', '$userId')";

        if (mysqli_query($con, $sql)) {
            echo '<script>alert("1 record added!");
            window.location.href = "farmer_addnewitem.php";
            </script>';
        } else {
            echo 'Error: ' . mysqli_error($con);
        }
    } else {
        echo "Some POST data is missing.";
    }

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
    <link rel="stylesheet" href="farmer_addnewitem.css">
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
                    <li><a href="  "><img src="./farmer_item_pic/AboutUs_icon.png"></a></li>
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


<!-------------------------------------------------Content of right frame -------------------------------------->

    <form method="post" action="farmer_addnewitem.php" enctype="multipart/form-data">
<!-- Content of right frame -->
            <div class="content-frame">
                <div class="tittle-addnewitem">Add New Item</div> <br>
                <div class="basicinfo-word">Basic Information</div> <br>

                <div class="productimage-word">Product Image</div> <br>


                    <!-- add picture button container -->
                    <div class="addbutton-container">
                        <!-- First button -->
                        <button id="addbutton-frame0" type="button" onclick="document.getElementById('fileInput0').click()">
                            <img class="add-icon" src="./farmer_item_pic/add_icon.png" alt="Icon"> <br>
                            <div class="cover-photo-word">*Cover Photo</div>
                        </button>
                        <input type="file" id="fileInput0" name="coverPhoto" accept="image/jpeg, image/png, image/gif, image/jpg" style="display: none">

                        <!-- Second button -->
                        <button id="addbutton-frame1" type="button" onclick="document.getElementById('fileInput1').click()">
                            <img class="add-icon" src="./farmer_item_pic/add_icon.png" alt="Icon"> <br>
                            <div class="cover-photo-word">Image 1</div>
                        </button>
                        <input type="file" id="fileInput1" name="image1" accept="image/jpeg, image/png, image/gif, image/jpg" style="display: none">

                    </div>
                    
                    
            
                
                <div class="productvideo-word">Product Video</div> <br>
                <!-- add video button container -->
                <div class="videobutton-container">
                    <button class="addbutton-frame2" id="addbutton-frame2" type="button" onclick="document.getElementById('videoInput').click()">
                        <img class="add-icon" src="./farmer_item_pic/add_icon.png" alt="Icon"> <br>
                        <div class="cover-photo-word">Upload Video</div>
                    </button>
                    <input type="file" id="videoInput" name="video_button" accept="video/mp4" style="display: none">
                    <p class="video-note">1. Size: Max 2MB, resolution should not exceed 1280x1280px <br>
                        2. Duration: 5s <br>
                        3. Format: MP4 <br>
                        4. Note: You can publish this listing while the video is being processed. Video will be shown in the listing once successfully processed.</p>
                    </div>

                <!-- product name container -->
                <div class="product-name-container">
                    <div class="productvideo-word">*Product Name</div>
                    <input id="product-name-input" type="text" name="product_name" required>
                </div>

                <!-- product description container -->
                <div class="product-description-container">
                    <div class="productvideo-word">*Product <br>Desciption</div> 
                    <textarea id="product-description-input" name="product_description" maxlength="1000" required></textarea>
                </div>




                <div class="salesinfo-word">Sales Information</div> <br>
                <!-- product price container -->
                <div class="product-price-container">
                    <div class="productprice-word">*Price (RM)</div> 
                    <input id="product-price" type="text" name="product_price" placeholder="00.00" required>
                </div> 
                
                <!-- product stock container -->
                <div class="product-stock-container">
                    <div class="productprice-word">*Stock</div> 
                    <input id="product-stock" type="number" name="product_stock" step="1" min="0" placeholder="0" required>
                </div>

                     
                <div class="salesinfo-word">Shipping</div> <br>
                <!-- product weight container -->
                <div class="product-weight-container">
                    <div class="productprice-word">*Weight (g)</div> 
                    <input id="product-weight" type="text" name="product_weight" placeholder="00.00" required>
                </div> 

                

                <!-- product submit and reset button container -->
                <div class="product-button-container">
                    <button class="reset-button" type="reset">Reset</button>
                    <button class="submit-button">Submit</button>
                </div>

                <input type="hidden" name="userid" value="<?php echo $userId;?>">





            
            </div>
        </form>
              
</body>
</html>
