<?php
require_once 'config.php';

// Step 2: Secure the page
require_login();

// Initialize variables
$post_id = null;
$post = null;
$message = '';

// Step 3: Get the Post ID from the URL and validate it
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $post_id = $_GET['id'];
} else {
    // If no valid ID is provided, redirect with an error.
    header("Location: index.php?message=" . urlencode("Invalid post ID."));
    exit();
}

// Step 4: Handle the form submission (POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate inputs
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (empty($title) || empty($content)) {
        $message = "Title and content cannot be empty.";
    } else {
        // Prepare the UPDATE statement to prevent SQL injection
        // CRITICAL: The WHERE clause checks both the post 'id' AND the 'user_id'
        // This ensures users can only update their own posts.
        $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ssii", $title, $content, $post_id, $_SESSION['user_id']);

        if ($stmt->execute()) {
            // Redirect to the dashboard with a success message
            header("Location: index.php?message=" . urlencode("Post updated successfully!"));
            exit();
        } else {
            $message = "Error: Could not update post.";
        }
    }
}

// Step 5: Fetch the existing post data to pre-fill the form (GET request)
// This query also checks ownership to prevent users from editing others' posts.
$stmt = $conn->prepare("SELECT title, content FROM posts WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $post_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $post = $result->fetch_assoc();
} else {
    // If the post doesn't exist or doesn't belong to the user, redirect.
    header("Location: index.php?message=" . urlencode("Post not found or you don't have permission to edit it."));
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Post</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Edit Post</h1>
        <nav>
            <a href="index.php" class="btn">Back to Dashboard</a>
            <a href="logout.php" class="btn">Logout</a>
        </nav>
    </header>

    <main>
        <?php if ($message): ?>
            <div class="alert"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form action="edit.php?id=<?php echo htmlspecialchars($post_id); ?>" method="post">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea id="content" name="content" rows="10" required><?php echo htmlspecialchars($post['content']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Post</button>
        </form>
    </main>
</body>
</html>