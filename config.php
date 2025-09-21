<?php
// File: config.php
// This is the central configuration file for the application.

// --- Step 1: Session Management ---
// Starts a new session or resumes an existing one. Must be called before any output.
session_start();

// --- Step 2: Database Connection Details ---
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Default XAMPP password is empty
define('DB_NAME', 'blog');

// --- Step 3: Establish Database Connection ---
// Creates a new MySQLi object to connect to the database.
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check for a connection error.
if ($conn->connect_error) {
    // If connection fails, stop the script and display an error message.
    die("Connection failed: " . $conn->connect_error);
}

// --- Step 4: Authentication Helper Functions ---

/**
 * Checks if a user is currently logged in by looking for a 'user_id' in the session.
 * @return bool True if logged in, false otherwise.
 */
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

/**
 * Secures a page by checking if a user is logged in.
 * If the user is not logged in, it redirects them to the login page.
 */
function require_login() {
    if (!is_logged_in()) {
        header("Location: login.php");
        exit();
    }
}
?>