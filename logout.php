<?php
session_start();

session_unset();
session_destroy();

// Redirect to login page
header("Location: ./login.php");
die();

//Please put this at the top navbar, and replace the href to the correct path
//<a href="../login_signup/logout.php">Logout</a>  