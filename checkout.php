<?php
require_once("./config_session.php");
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "webdevelopment";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$product_IDs = array(); // Define the array here

// Check if product IDs exist in session -------------------------------------------------------------------------------------------------
if (isset($_SESSION['cart_product_ids'])) {
    // Retrieve the product IDs from the session variable
    $cart_product_ids = $_SESSION['cart_product_ids'];

    // Remove duplicate product IDs
    $cart_product_ids = array_unique($cart_product_ids);

    // Create an array to store product IDs that still exist in the database
    $existingProductIDs = array();

    // Loop through the product IDs and check if they exist in the database
    foreach ($cart_product_ids as $product_ID) {
        // Prepare SQL statement
        $stmt = $conn->prepare("SELECT * FROM cart WHERE product_id = ?");
        $stmt->bind_param("i", $product_ID);

        // Execute SQL statement and get result
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if product exists in the database
        if ($result->num_rows > 0) {
            // Fetch product data and add it to the products array
            while ($row = $result->fetch_assoc()) {
                $product_IDs[] = $row;
                $existingProductIDs[] = $product_ID; // Store existing product IDs
            }
        }
    }

    // Update the session data with the product IDs that still exist in the database
    $_SESSION['cart_product_ids'] = $existingProductIDs;
}

// Check if the item in the cart is getting removed
if (isset($_GET['remove'])) {
    // Get the cart ID to show as removed
    $remove_id = $_GET['remove'];

    // Find the index of the removed product in the session data
    $indexToRemove = array_search($remove_id, $existingProductIDs);

    if ($indexToRemove !== false) {
        // Remove the product from the session data
        unset($product_IDs[$indexToRemove]);
        unset($existingProductIDs[$indexToRemove]);
        $_SESSION['cart_product_ids'] = $existingProductIDs;
    }
}

// if ($product_IDs) {
//     foreach ($product_IDs as $product){
//         echo "Product ID: " . $product['product_id'] . "<br>";
//         echo "Product Name: " . $product['name'] . "<br>";
//         echo "<br>";
//     }
// } else {
//     echo "No products found.";
// }

// Get the cart id in session -------------------------------------------------------------------------------------------------

if (isset($_GET['checkout']) && $_GET['checkout'] == 'true') {
    // Assuming you have user and product IDs in the session
    $userID = $_SESSION['user_id'];
    $productIDs = $_SESSION['cart_product_ids'];

    if (!empty($productIDs)) {
        // Prepare SQL statement to select the cart IDs
        $sql = "SELECT cart_id FROM cart WHERE user_id = ? AND product_id IN (";
        // Generate commas(.=) by the end of each bind params "?"
        $sql .= str_repeat('?,', count($productIDs) - 1) . '?)';

        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Bind parameters
            // Gerate strings based on the number of ID's in productIDs, it will generate the string '?, ?, ?)'...
            $stmt->bind_param(str_repeat('i', count($productIDs) + 1), $userID, ...$productIDs);

            // Execute SQL statement and get the result
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if the result contains any rows
            if ($result->num_rows > 0) {
                // Fetch the cart IDs from the result and store them in an array
                $cartIDs = [];
                while ($row = $result->fetch_assoc()) {
                    $cartIDs[] = $row['cart_id'];
                }

                // Store the cart IDs in the session (for example, in the cart_ids array)
                $_SESSION['cart_ids'] = $cartIDs;

                // foreach ($cartIDs as $cartID) {
                //     echo "Cart ID: " . $cartID . "<br>";
                // }
            } else {
                echo "No matching cart IDs found.";
            }
        } else {
            echo "Error in preparing SQL statement: " . $conn->error;
        }
    } else {
        echo "No products found.";
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

//--------- check out part ------------------------------------------------------------------------------

// Initialize an empty array to store product IDs if it doesn't exist
if (!isset($_SESSION['cart_product_ids'])) {
    $_SESSION['cart_product_ids'] = array();
}

if (isset($_POST['add_to_cart'])) {
    $product_ID = $_POST['product_ID'];

    // Add the product's ID to the session variable
    $_SESSION['cart_product_ids'][] = $product_ID;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="checkout.css">
    <title>Document</title>
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

    <!------------ Logout picture part, place the correct path!! ------------>
    <main class="main-container">
        <div class="main-content">

            <div class="left-content">
                <button id="back-button" onclick="window.location.href = 'cart.php'">Back</button>

                <form action="checkout.php" method="POST">
                    <div class="form-group">
                        <label for="paymentname">Name:</label>
                        <input type="text" id="paymentname" name="paymentname">
                    </div>

                    <div class="form-group">
                        <label for="paymentphonenumber">Phone number:</label>
                        <input type="text" id="paymentphonenumber" name="paymentphonenumber">
                    </div>

                    <div class="form-group">
                        <label for="paymentemail">Email:</label>
                        <input type="email" id="paymentemail" name="paymentemail">
                    </div>

                    <div class="form-group">
                        <label for="paymentaddress">Delivery Address:</label>
                        <input type="text" id="paymentaddress" name="paymentaddress">
                    </div>

                    <div class="form-group">
                        <label for="paymentmethod">Payment Method:</label>
                        <select id="paymentmethod" name="paymentmethod">
                            <option value="e-wallet">E-Wallet</option>
                            <option value="bank">Bank Transfer</option>
                            <option value="cash">Cash on Delivery</option>
                        </select>
                    </div>
                    <input type="submit" value="Make Payment">
                </form>

            </div>

            <div class="right-content">
                <div class="bought-results" id="item-bought">
                    <Label>Item Bought:<label>
                            <div>
                                <?php
                                if ($product_IDs) {
                                    echo "<table border='1'>";
                                    echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th></tr>";

                                    foreach ($product_IDs as $product) {
                                        // Display product information in an HTML table row
                                        echo "<tr>";
                                        echo "<td>" . $product['name'] . "</td>";
                                        echo "<td>" . $product['price'] . "</td>";
                                        echo "<td>" . $product['quantity'] . "</td>";
                                        echo "</tr>";
                                    }

                                    echo "</table>";
                                } else {
                                    echo "No products found in the cart.";
                                }

                                ?>
                            </div>
                </div>

                <div class="bought-results" id="total-price">
                    <div>
                        <?php
                        if ($product_IDs) {
                            echo "<table border='1'>";

                            $totalPrice = 0; // Initialize the total price

                            foreach ($product_IDs as $product) {
                                // Calculate and accumulate the total price
                                $quantity = (int) $product['quantity'];
                                $price = (float) $product['price'];
                                $itemTotal = $quantity * $price;
                                $totalPrice += $itemTotal;
                            }

                            echo "</table>";

                            // Display the total price after the table with CSS styles
                            echo "<p>Total Price: <span>$" . number_format($totalPrice, 2) . "</span></p>";
                        } else {
                            echo "No products found in the cart.";
                        }
                        ?>

                        <?php
                        // Function to retrieve the cart_id associated with the user's cart
                        function getCartIdByProduct($user_id, $product_id, $conn)
                        {
                            $stmt = $conn->prepare("SELECT cart_id FROM cart WHERE user_id = ? AND product_id = ? LIMIT 1");
                            $stmt->bind_param("ii", $user_id, $product_id);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                return $row['cart_id'];
                            } else {
                                return false; // Return false if no cart_id is found
                            }
                        }

                        if ($_SERVER["REQUEST_METHOD"] === "POST") {
                            $user_id = $_SESSION['user_id'];
                            $paymentname = $_POST['paymentname'];
                            $paymentphonenumber = $_POST['paymentphonenumber'];
                            $paymentemail = $_POST['paymentemail'];
                            $paymentaddress = $_POST['paymentaddress'];
                            $method = $_POST['paymentmethod'];

                            $date = date("Y-m-d H:i:s");

                            // Prepare the INSERT statement for your_order_table
                            $stmt = $conn->prepare("INSERT INTO orders (user_id, product_id, cart_id, paymentname, paymentphonenumber, paymentemail, paymentaddress, method, total_quantity, total_price, date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                            // Loop through the product IDs in $product_IDs and their corresponding cart IDs
                            foreach ($product_IDs as $product) {
                                $product_id = $product['product_id'];
                                $cart_id = $product['cart_id']; // Get the associated cart ID

                                $quantity = (int) $product['quantity'];
                                $price = (float) $product['price'];
                                $itemTotal = $quantity * $price;

                                // Bind parameters and execute the INSERT statement
                                $stmt->bind_param("iiissssssss", $user_id, $product_id, $cart_id, $paymentname, $paymentphonenumber, $paymentemail, $paymentaddress, $method, $quantity, $itemTotal, $date);

                                if ($stmt->execute()) {
                                    // Add the cart_id to the list of cart IDs to be deleted
                                    $cartIDsToDelete[] = $cart_id;
                                } else {
                                    // Handle the database insertion error
                                    echo "Error: " . $stmt->error;
                                }
                            }

                            // Delete records from the cart table with the cart IDs in $cartIDsToDelete
                            if (!empty($cartIDsToDelete)) {
                                $cartIDsToDeleteStr = implode(',', $cartIDsToDelete);
                                $deleteCartSQL = "DELETE FROM cart WHERE cart_id IN ($cartIDsToDeleteStr)";
                                if ($conn->query($deleteCartSQL) === TRUE) {
                                    // Records in the cart table deleted successfully
                                    
                                    // Subtract product quantities in the product table
                                    foreach ($product_IDs as $product) {
                                        $product_id = $product['product_id'];
                                        $quantity = (int) $product['quantity'];

                                        // Retrieve the current quantity in the product table
                                        $stmt = $conn->prepare("SELECT product_stock FROM product WHERE product_id = ?");
                                        $stmt->bind_param("i", $product_id);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        
                                        if ($result->num_rows > 0) {
                                            $row = $result->fetch_assoc();
                                            $currentQuantity = (int) $row['product_stock'];
                                            
                                            // Calculate the new quantity after subtracting the ordered quantity
                                            $newQuantity = $currentQuantity - $quantity;

                                            // Update the product table with the new quantity
                                            $updateStmt = $conn->prepare("UPDATE product SET product_stock = ? WHERE product_ID = ?");
                                            $updateStmt->bind_param("ii", $newQuantity, $product_id);
                                            if ($updateStmt->execute()) {
                                                echo '<script>
                                                    alert("Order Success! Thank You!!");
                                                    window.location.href = "product.php";
                                                    </script>';
                                            } else {
                                                // Handle the update error
                                                echo "Error updating product quantity: " . $updateStmt->error;
                                            }
                                        } else {
                                            // Handle the case where the product is not found
                                            echo "Product not found for ID: " . $product_id;
                                        }
                                    }
                                    

                                } else {
                                    // Handle the database deletion error
                                    echo "Error deleting records from the cart table: " . $conn->error;
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
