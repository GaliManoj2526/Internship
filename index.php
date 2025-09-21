<?php
// File: index.php
// This is the main dashboard, handling the "Read" operation.

// Step 1: Include necessary files and start the session
require_once 'config.php';

// Step 2: Secure the page
require_login();

// Step 3: Fetch all posts belonging to the current user
// We select posts where the user_id matches the one stored in the session.
// We order them by creation date to show the newest posts first.
$stmt = $conn->prepare("SELECT id, title, content, created_at FROM posts WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$posts = $result->fetch_all(MYSQLI_ASSOC);

// Get any messages from the URL (e.g., after a successful post creation)
$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Blog Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <nav>
            <a href="create.php" class="btn btn-primary">Create New Post</a>
            <a href="logout.php" class="btn">Logout</a>
        </nav>
    </header>

    <main>
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>

        <h2>My Posts</h2>
        <?php if (empty($posts)): ?>
            <p>You haven't created any posts yet. <a href="create.php">Create your first post!</a></p>
        <?php else: ?>
            <div class="posts-container">
                <?php foreach ($posts as $post): ?>
                    <div class="post-card">
                        <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                        <p class="post-meta">Published on <?php echo date('F j, Y, g:i a', strtotime($post['created_at'])); ?></p>
                        <p><?php echo nl2br(htmlspecialchars(substr($post['content'], 0, 200))); ?>...</p>
                        <div class="post-actions">
                            <a href="edit.php?id=<?php echo $post['id']; ?>" class="btn">Edit</a>
                            <a href="delete.php?id=<?php echo $post['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>

