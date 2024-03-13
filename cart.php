<?php
require_once "config_session.php";
include 'conn.php';

$userId = $_SESSION['user_id'];

if (isset($_POST['update_update_btn'])) {
    $update_value = $_POST['update_quantity'];
    $update_id = $_POST['update_quantity_id'];

    // Retrieve available stock from products table
    $stock_query = mysqli_query($con, "SELECT product_stock FROM `product` WHERE product_id = (SELECT product_id FROM `cart` WHERE cart_id = '$update_id')");
    $stock_result = mysqli_fetch_assoc($stock_query);
    $available_stock = $stock_result['product_stock'];

    // Check if requested quantity is valid
    if ($update_value <= $available_stock) {
        // Update quantity in cart
        $update_quantity_query = mysqli_query($con, "UPDATE `cart` SET quantity = '$update_value' WHERE cart_id = '$update_id'");
        if ($update_quantity_query) {
            header('location:cart.php');
        } else {
            // Handle update error
            echo "Error updating quantity: " . mysqli_error($con);
        }
    } else {
        // Quantity exceeds available stock, display error message
        echo '<script>alert("Quantity exceeds available stock!");</script>';
    }
};

if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    $delete_query = "DELETE FROM `cart` WHERE cart_id = '$remove_id'";
    $delete_result = mysqli_query($con, $delete_query);

    if (!$delete_result) {
        die("Error: " . mysqli_error($con));
    }

    header('location:cart.php');
}


if (isset($_GET['delete_all'])) {
    mysqli_query($con, "DELETE FROM `cart`");
    header('location:cart.php');
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cart</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="cart.css">
</head>

<body>

    <header class="header-main">
        <div class="header-main-logo">
            <img src="Image/logo.png" alt="HeyDaylogo">

            <div class="farmerbackground"></div>
            <div class="farmerword">Supermarket</div>

            <nav class="header-main-topNav">
                <ul>
                    <li><a href="supermarket_about_us.php"><img src="Image/AboutUs_icon.png"></a></li>
                    <li><a href="product.php"><img src="Image/home_icon.png"></a></li>
                    <li><a href="setting(buyer).php"><img src="Image/setting_icon.png"></a></li>
                    <li><a href="./logout.php"> <img src="./farmer_item_pic/logout.png"></a></li>
                </ul>
            </nav>
            <?php

            $select_rows = mysqli_query($con, "SELECT * FROM `cart`") or die('query failed');
            $row_count = mysqli_num_rows($select_rows);

            ?>

            <a href="cart.php" class="cart-icon"><i class="fas fa-cart-shopping"></i><span><?php echo $row_count; ?></span></a>
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


    <div class="container">

        <section class="shopping-cart">

            <h1 class="heading">shopping cart</h1><br><br><br><br>

            <table>

                <thead>
                    <th>image</th>
                    <th>name</th>
                    <th>price</th>
                    <th>quantity</th>
                    <th>total price</th>
                    <th>action</th>
                </thead>

                <tbody>

                    <?php

                    $select_cart = mysqli_query($con, "SELECT * FROM `cart`");
                    $grand_total = 0;
                    if (mysqli_num_rows($select_cart) > 0) {
                        while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                    ?>

                            <tr>
                                <td><img src="<?php echo $fetch_cart['image']; ?>" height="100" alt="image"></td>
                                <td><?php echo $fetch_cart['name']; ?></td>
                                <td>RM<?php echo is_numeric($fetch_cart['quantity']) ? ((float)$fetch_cart['price']) : 'Invalid Quantity'; ?></td>
                                <td>
                                    <form action="" method="post" onsubmit="return confirm('Are you sure you want to update this item?')">
                                        <input type="hidden" name="update_quantity_id" value="<?php echo $fetch_cart['cart_id']; ?>">
                                        <input type="number" name="update_quantity" min="1" value="<?php echo $fetch_cart['quantity']; ?>">
                                        <input type="submit" value="update" name="update_update_btn">
                                    </form>
                                </td>
                                <?php
                                $sub_total = (float)$fetch_cart['price'] * (int)$fetch_cart['quantity']; // Initialize $sub_total here
                                ?>
                                <td>RM<?php echo ($sub_total); ?></td>
                                <td><a href="cart.php?remove=<?php echo $fetch_cart['cart_id']; ?>" onclick="return confirm('Are you sure you want to remove this item from the cart?')" class="delete-btn"> <i class="fas fa-trash"></i> remove</a></td>

                            </tr>
                    <?php
                            $grand_total += $sub_total;
                        };
                    };
                    ?>
                    <tr class="table-bottom">
                        <td><a href="product.php" class="option-btn" style="margin-top: 0;">continue shopping</a></td>
                        <td colspan="3">grand total</td>
                        <td>RM<?php echo $grand_total; ?></td>
                        <td><a href="cart.php?delete_all" onclick="return confirm('Are you sure you want to delete all items from the cart?');" class="delete-btn"> <i class="fas fa-trash"></i> delete all </a></td>
                    </tr>

                </tbody>

            </table>

            <div class="checkout-btn">
                <a href="checkout.php?checkout=true" class="btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>">Proceed to Checkout</a>
            </div>



        </section>


    </div>

    <!-- custom js file link  -->
    <script src="script.js"></script>

</body>

</html>
