<?php
    $tempUserId = $_SESSION['user_id'];
    include("conn.php");

    $id = $_GET['id'];

    $sql = "DELETE FROM product where product_ID =" .$id;

    mysqli_query($con,$sql);

    echo "<script>alert('Record deleted!'); window.location.href='farmer_viewproduct.php';</script>";

?>
