<?php
require_once("config_session.php");
include("conn.php");

$userId = $_SESSION['user_id'];

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve edited product information from $_POST and $_FILES arrays
    $productID = $_POST['product_id'];
    $productName = $_POST['product_name'];
    $productDescription = $_POST['product_description'];
    $productPrice = $_POST['product_price'];
    $productStock = $_POST['product_stock'];
    $productWeight = $_POST['product_weight'];

    // Handle uploaded files (images and videos) using $_FILES
    $coverPhoto = $_FILES['coverPhoto'];
    $image1 = $_FILES['image1'];
    $video = $_FILES['video_button'];

    // Check if a new cover photo has been uploaded
    if ($coverPhoto['error'] === UPLOAD_ERR_OK) {
        // Move the uploaded cover photo to a destination directory and update the corresponding database field
        $coverPhotoName = $coverPhoto['name'];
        $coverPhotoTmpName = $coverPhoto['tmp_name'];
        $coverPhotoDest = "uploads/" . $coverPhotoName;
        move_uploaded_file($coverPhotoTmpName, $coverPhotoDest);

        // Update the cover photo field in the database
        $sql = "UPDATE product SET coverPhoto = '$coverPhotoDest' WHERE product_ID = $productID";
        mysqli_query($con, $sql);
    }
    
    if ($image1['error'] === UPLOAD_ERR_OK) {
        // Move the uploaded imag1 to a destination directory and update the corresponding database field
        $image1Name = $image1['name'];
        $image1TmpName = $image1['tmp_name'];
        $image1Dest = "uploads/" . $image1Name;
        move_uploaded_file($image1TmpName, $image1Dest);

        // Update the cover photo field in the database
        $sql = "UPDATE product SET image1 = '$image1Dest' WHERE product_ID = $productID";
        mysqli_query($con, $sql);
    }

    if ($video['error'] === UPLOAD_ERR_OK) {
        // Move the uploaded video to a destination directory and update the corresponding database field
        $videoName = $video['name'];
        $videoTmpName = $video['tmp_name'];
        $videoDest = "uploads/" . $videoName;
        move_uploaded_file($videoTmpName, $videoDest);

        // Update the video_button field in the database
        $sql = "UPDATE product SET video_button = '$videoDest' WHERE product_ID = $productID";
        mysqli_query($con, $sql);
    }
    
    

    // Construct an SQL query to update the product information (excluding cover photo, image1, and video)
    $sql = "UPDATE product SET
        product_name = '$productName',
        product_description = '$productDescription',
        product_price = '$productPrice',
        product_stock = '$productStock',
        product_weight = '$productWeight'
        WHERE product_ID = $productID";

    // Execute the SQL query
    if (mysqli_query($con, $sql)) {
        // Redirect back to the product listing page (e.g., farmer_viewproduct.php) after successful update
        header("Location: farmer_viewproduct.php");
    } else {
        echo "Error updating product: " . mysqli_error($con);
    }
}
mysqli_close($con);
?>
