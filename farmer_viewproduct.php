<?php
require_once("./config_session.php");
include("conn.php");

$userId = $_SESSION['user_id'];

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}



if (isset($_GET['sold'])) {
    $sql = "SELECT coverPhoto, product_name, product_ID, product_price, product_stock, product_weight FROM product WHERE product_stock <= 0 AND userid = '$userId'";
} else {
    $sql = "SELECT coverPhoto, product_name, product_ID, product_price, product_stock, product_weight FROM product WHERE userid = '$userId'";
}

$result = mysqli_query($con, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($con));
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
    <link rel="stylesheet" href="farmer_viewproduct.css">
    <title>farmer_viewproduct</title>

    <script>
            function searchItem() {
                // Declare variables
                var inputs = document.querySelectorAll(".itemsearchbar");
                var filter = inputs[0].value.toUpperCase(); // Assuming you have a single search input field
                var table = document.querySelector(".product-table");
                var tr = table.getElementsByTagName("tr");

                // Loop through all table rows and hide those that don't match the search query
                for (var x = 0; x < tr.length; x++) {
                    var td = tr[x].getElementsByTagName("td")[1]; // Change the index to the column where you want to perform the search (2nd column in this case)
                    if (td) {
                        var txtValue = td.textContent || td.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[x].style.display = "";
                        } else {
                            tr[x].style.display = "none";
                        }
                    }
                }
            }


            function updateTotalCount() {
                var table = document.querySelector(".product-table");
                var rowCount = table.getElementsByTagName("tr").length - 1; // Subtract 1 to exclude the header row
                document.getElementById("total-count").textContent = rowCount + "Products" ;
            }

            // Call the function to initially update the count after the page has loaded
            window.addEventListener('load', updateTotalCount);


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

    <form method="post" action="farmer_viewproduct.php" enctype="multipart/form-data">
<!-- Content of right frame -->
            <div class="content-frame">
                <div class="tittle-addnewitem">My Item</div> <br>

                <div class="upper-container">
                    <div id="total-count">0 Product</div>
                    <input class="itemsearchbar" type="text" id="searchBar" name="username" placeholder="Search Item" onkeyup="searchItem()">
                  <!--  <button class="all-button" id="allbutton">All</button>
                    <button class="soldout-button" id="soldoutbutton">Sold Out</button>-->
                    <a class="all-button" onclick="window.location.href='farmer_viewproduct.php'">All</a>
                    <a class="soldout-button" onclick="window.location.href='farmer_viewproduct.php?sold=0'">Sold Out</a>
                    <a class="addnewitem-button" onclick="window.location.href='farmer_addnewitem.php?'">Add New Item</a>
                </div>


                <table class="product-table">
                    <tr>
                        <th>Picture</th>
                        <th>Name</th>
                        <th>ID</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Weight</th>
                        <th>Options</th>
                    </tr>
                    <?php
                        // Loop through the fetched product data and display it in table rows
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<tr class='product-row'>";
                            echo "<td><img class='photosize' src='" . $row['coverPhoto'] . "' alt='Product Image'></td>";
                            echo "<td>" . $row['product_name'] . "</td>";
                            echo "<td>" . $row['product_ID'] . "</td>";
                            echo "<td>" . $row['product_price'] . "</td>";
                            echo "<td>" . $row['product_stock'] . "</td>";
                            echo "<td>" . $row['product_weight'] . "</td>";

                            echo"<td>";
                            echo "<div class='btn-group'>";
                                echo '<a class="edit-button" href = "edit.php?id='.$row['product_ID'].'">Edit</a> ';
                                echo '<a class="delete-button"onclick="return confirm(\'Delete this record?\')" href = "delete.php?id='.$row['product_ID'].'">Delete</a>';
                            echo "</div>";
                            echo "</td>";

                            echo "</tr>";
                        }
                        
                    ?>
                </table> 

            </div>
    </form>
              
</body>
</html>
