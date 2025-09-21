<?php
// File: login.php
// This program handles the user login process with corrected logic.

// Step 1: Include necessary files and start the session
require_once 'config.php';

// Step 2: Redirect if already logged in
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
    } else {
        // Step 4: Prepare and execute the query to fetch the user
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Step 5: Verify if a user was found
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Step 6: Verify the password against the stored hash
            if (password_verify($password, $user['password'])) {
                // Password is correct, create the session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: index.php");
                exit();
            } else {
                // Password is not correct
                $message = "Invalid username or password.";
            }
        } else {
            // No user found with that username
            $message = "Invalid username or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Login to Your Blog</h1>
    </header>

    <main class="auth-form">
        <?php if ($message): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form action="login.php" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a>.</p>
    </main>
</body>
</html>