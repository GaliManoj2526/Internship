<?php
// File: create.php
// This program handles the "Create" operation for the blog.

// Step 1: Include necessary files and start the session
// config.php connects to the database and defines helper functions.
require_once 'config.php';

// Step 2: Secure the page
// The require_login() function ensures only logged-in users can access this page.
require_login();

// Step 3: Initialize variables
// $message will be used to display feedback (errors or success) to the user.
$message = '';

// Step 4: Process the form data if it has been submitted
// The code inside this 'if' block only runs when the form is submitted via the POST method.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Step 5: Sanitize and retrieve user input
    // trim() removes any accidental spaces from the beginning or end of the input.
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    
    // Step 6: Validate the input
    // Check if the title and content are not empty.
    if (!empty($title) && !empty($content)) {
        
        // Step 7: Prepare and execute the database query securely
        // Using a prepared statement with placeholders (?) prevents SQL injection attacks.
        $stmt = $conn->prepare("INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)");
        
        // Bind the actual values to the placeholders.
        // 'iss' specifies the data types: Integer for user_id, String for title, String for content.
        $stmt->bind_param("iss", $_SESSION['user_id'], $title, $content);
        
        // Execute the statement to insert the data.
        if ($stmt->execute()) {
            // If the query is successful, redirect the user to the main page.
            header("Location: index.php?message=Post created successfully!");
            exit(); // Stop the script after redirection.
        } else {
            // If the query fails, set an error message.
            $message = "Error: The post could not be created.";
        }
        
    } else {
        // If validation fails (e.g., a field is empty), set an error message.
        $message = "Please fill out both the title and content fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create a New Post</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <!-- Reusable Navigation Bar -->
        <nav>
            <div>Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!</div>
            <div>
                <a href="index.php">Dashboard</a>
                <a href="create.php">Create Post</a>
                <a href="logout.php">Logout</a>
            </div>
        </nav>

        <div class="form-container" style="max-width: 800px;">
            <h2>Create a New Post</h2>
            
            <!-- This section will display any error or success messages -->
            <?php if ($message): ?>
                <div class="message"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>

            <!-- HTML Form to Create a New Post -->
            <form action="create.php" method="POST">
                <div class="form-group">
                    <label for="title">Post Title</label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="content">Post Content</label>
                    <textarea id="content" name="content" rows="10" required></textarea>
                </div>
                <button type="submit" class="btn">Publish</button>
            </form>
        </div>
    </div>
</body>
</html>

