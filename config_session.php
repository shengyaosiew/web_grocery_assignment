<?php
// Session configuration and security enhancements
ini_set('session.use_only_cookies', 1); // Enforce session IDs to be stored only in cookies
ini_set('session.use_strict_mode', 1); // Prevent session ID injection via URL parameters

// Set session cookie parameters for enhanced security
session_set_cookie_params([
    'lifetime' => 1800, // Set session cookie lifetime to 30 minutes (1800 seconds)
    'domain' => 'localhost', // Restrict session cookie to the 'localhost' domain
    'path' => '/', // Set session cookie path to root directory ('/')
    'secure' => true, // Transmit session cookie only over HTTPS
    'httponly' => true // Prevent JavaScript access to session cookie
]);

// Start the session
session_start();


if (isset($_SESSION["user_id"])) {
    // Help the user to update the session id
    if (!isset($_SESSION["last_generation"])) {
        regenerate_session_id();
    } else {
        $interval = 60 * 30;
        if (time() - $_SESSION["last_generation"] >= $interval) {
            regenerate_session_id();
        }
    }


} else {
    // Check if the 'last_generation' session variable is set and if the last regeneration time exceeds the interval (30 minutes)
    $interval = 60 * 30;
    if (!isset($_SESSION["last_generation"]) || time() - $_SESSION["last_generation"] >= $interval) {
        regenerate_session_id();
    }
}

// Function to regenerate the session ID and update the 'last_generation' timestamp
function regenerate_session_id() {
    session_regenerate_id(true); // Regenerate the session ID
    $_SESSION["last_generation"] = time(); // Update the last regeneration timestamp
}



