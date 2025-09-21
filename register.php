<?php
// File: register.php
// This program handles new user registration.

// Step 1: Include necessary files and start the session
require_once 'config.php';

// Step 2: Redirect if already logged in
// If a user is already logged in, they don't need to see the registration page.
if (is_logged_in()) {
    header("Location: index.php");
    exit();
}

// Initialize variables
$message = '';

// Step 3: Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate inputs
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $message = "Username and password are required.";
    } elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters long.";
    } else {
        // Step 4: Check if username already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Username already exists. Please choose another one.";
        } else {
            // Step 5: Hash the password for security
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Step 6: Insert the new user into the database
            $insert_stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $insert_stmt->bind_param("ss", $username, $hashed_password);

            if ($insert_stmt->execute()) {
                // Redirect to the login page with a success message
                header("Location: login.php?message=" . urlencode("Registration successful! Please log in."));
                exit();
            } else {
                $message = "Error: Could not register user.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Create a New Account</h1>
    </header>

    <main class="auth-form">
        <?php if ($message): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form action="register.php" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password (min. 6 characters)</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </main>
</body>
</html>

