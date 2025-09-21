<?php
// File: delete.php
// This program handles the "Delete" operation for a specific blog post.

// Step 1: Include necessary files and start the session
// config.php connects to the database, starts the session, and defines helper functions.
require_once 'config.php';

// Step 2: Secure the page
// The require_login() function ensures only logged-in users can access this page.
require_login();

// Step 3: Get the Post ID from the URL
// We use a GET parameter to identify which post to delete.
// For example, the link on the dashboard will be 'delete.php?id=5'.
// We check if the 'id' parameter is set and is a numeric value.
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    
    // Step 4: Sanitize the input
    // Store the post ID in a variable.
    $post_id = $_GET['id'];
    
    // Step 5: Prepare the SQL DELETE statement securely
    // This query will delete a post from the 'posts' table.
    // CRITICAL: The WHERE clause checks both the post 'id' AND the 'user_id'.
    // This ensures that users can only delete their own posts, not posts belonging to others.
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
    
    // Step 6: Bind the parameters to the statement
    // 'ii' specifies that both parameters are integers.
    $stmt->bind_param("ii", $post_id, $_SESSION['user_id']);
    
    // Step 7: Execute the statement
    if ($stmt->execute()) {
        // If the query is successful, set a success message.
        $message = "Post deleted successfully!";
    } else {
        // If the query fails, set an error message.
        $message = "Error: Could not delete post.";
    }
    
} else {
    // If the 'id' parameter is missing or invalid, set an error message.
    $message = "Invalid request.";
}

// Step 8: Redirect back to the main page with a status message
// This script has no visible HTML content; it performs an action and then redirects.
header("Location: index.php?message=" . urlencode($message));
exit(); // Stop the script execution.

?>