<?php
// File: logout.php
// This program handles the user logout process.

// Step 1: Include the configuration file to start the session.
// This is necessary to access and then destroy the current session.
require_once 'config.php';

// Step 2: Unset all session variables.
// This removes all data that was stored for the user,
// such as their ID and username.
session_unset();

// Step 3: Destroy the session itself.
// This command completely terminates the user's session on the server.
session_destroy();

// Step 4: Redirect to the login page with a success message.
// The user is sent back to the login screen, confirming they have logged out.
header("Location: login.php?message=" . urlencode("You have been logged out successfully."));
exit();
?>