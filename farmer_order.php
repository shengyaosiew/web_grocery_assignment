<?php
require_once("config_session.php");
include("conn.php");

// Check if the database connection was successful
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}


$userId = $_SESSION['user_id'];


// Query to fetch data from the database
$query = "SELECT o.date, o.user_id, p.product_name, o.total_quantity, (o.total_quantity * p.product_price) AS total_price, o.paymentname, o.paymentphonenumber, o.paymentemail, o.paymentaddress
          FROM orders o
          JOIN product p ON o.product_id = p.product_ID
          WHERE p.userid = $userId
          ORDER BY o.date DESC, o.user_id";

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
    <link rel="stylesheet" href="farmer_order.css">
    <title>farmer_order</title>

    <script>
            function filterOrders() {
                var inputDate = document.getElementById("filter-date").value;
                var rows = document.querySelectorAll(".product-table tr");
                
                for (var i = 1; i < rows.length; i++) {
                    var orderDateCell = rows[i].cells[0];
                    if (orderDateCell) {
                        var orderDate = new Date(orderDateCell.textContent);
                        if (!inputDate || isSameDate(inputDate, orderDate)) {
                            rows[i].style.display = "";
                        } else {
                            rows[i].style.display = "none";
                        }
                    }
                }
            }

            // Function to compare two dates to check if they are the same date
            function isSameDate(date1, date2) {
                date1 = new Date(date1);
                return date1.getFullYear() === date2.getFullYear() &&
                    date1.getMonth() === date2.getMonth() &&
                    date1.getDate() === date2.getDate();
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


    <form method="get" action="farmer_order.php" enctype="multipart/form-data">
<!-- Content of right frame -->
            <div class="content-frame">
                <div class="tittle-addnewitem">Order List</div> <br>

                <div class="upper-container">
                    <div id="filter-container">
                        <label for="filter-date">Filter by Date:</label>
                        <input type="date" id="filter-date" name="filter-date" oninput="filterOrders()">
                    </div>
                </div>

                <table class="product-table">
                <tr>
                    <th>Order Date</th>
                    <th>userid </th>
                    <th>Product Name</th>
                    <th>Total Quantity</th>
                    <th>Total Price</th>
                    <th>Username</th>
                    <th>Phonenumber</th>
                    <th>Email</th>
                    <th>Address</th>
                </tr>


                <?php
                    // Initialize variables to track the previous "Order Date" and "User ID"
                    $previousDate = null;
                    $previousUserId = null;

                    // Loop through the query results and display them in the table
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";

                        // Check if the "Order Date" has changed
                        if ($row['date'] != $previousDate || $row['user_id'] != $previousUserId) {
                            echo "<td>" . $row['date'] . "</td>";
                            echo "<td>" . $row['user_id'] . "</td>";
                            $previousDate = $row['date'];
                            $previousUserId = $row['user_id'];
                        } else {
                            echo "<td></td>"; // Empty cell for repeated dates and user_ids
                            echo "<td></td>"; // Empty cell for user_ids
                        }

                        echo "<td>" . $row['product_name'] . "</td>";
                        echo "<td>" . $row['total_quantity'] . "</td>";
                        echo "<td>" . $row['total_price'] . "</td>";
                        echo "<td>" . $row['paymentname'] . "</td>";
                        echo "<td>" . $row['paymentphonenumber'] . "</td>";
                        echo "<td>" . $row['paymentemail'] . "</td>";
                        echo "<td>" . $row['paymentaddress'] . "</td>";
                        echo "</tr>";
                    }

                    // Close the database connection
                    mysqli_close($con);
                ?>
            </table>
    </div>
</form>

</body>
</html>
