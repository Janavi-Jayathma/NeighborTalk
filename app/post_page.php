<?php
session_start();
require_once '../database/db.php';

//Get post id from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid post ID.");
}
$post_id = intval($_GET['id']);

//Fetch single post with user info
$postSql = "SELECT p.*, u.username 
            FROM posts p 
            JOIN users u ON p.user_id = u.user_id 
            WHERE p.id = ?";

$stmt = $conn->prepare($postSql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $post_id);
$stmt->execute();
$postResult = $stmt->get_result();
$post = $postResult->fetch_assoc();
$stmt->close();

if (!$post) {
    die("Post not found.");
}

// --- Handle comment delete ---
if (isset($_GET['delete_comment']) && is_numeric($_GET['delete_comment'])) {
    $comment_id = intval($_GET['delete_comment']);

    // Fetch current user role + id
    $currentUserId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $currentRole = isset($_SESSION['role']) ? $_SESSION['role'] : null;

    // Fetch comment info
    $checkStmt = $conn->prepare("SELECT user_id FROM post_comments WHERE id = ? AND post_id = ?");
    $checkStmt->bind_param("ii", $comment_id, $post_id);
    $checkStmt->execute();
    $checkStmt->bind_result($commentUserId);
    $checkStmt->fetch();
    $checkStmt->close();

    // Allow delete if super_admin OR post owner
    if ($currentRole === 'super_admin' || $currentUserId == $post['user_id']) {
        $delStmt = $conn->prepare("DELETE FROM post_comments WHERE id = ?");
        $delStmt->bind_param("i", $comment_id);
        $delStmt->execute();
        $delStmt->close();
        header("Location: post_page.php?id=" . $post_id);
        exit;
    }
}

//Handle new comment submission
$commentMessage = "";
if (isset($_POST['comment_submit'])) {
    $comment = trim($_POST['comment']);
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    if (!empty($comment)) {
        $stmt = $conn->prepare("INSERT INTO post_comments (post_id, user_id, username, comment) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $post_id, $user_id, $username, $comment);

        if ($stmt->execute()) {
            $commentMessage = "Comment posted!";
        } else {
            $commentMessage = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $commentMessage = "Please write a comment before submitting.";
    }
}

$commentsResult = $conn->query("SELECT * FROM post_comments WHERE post_id = $post_id ORDER BY created_at DESC");

//Fetch comments for this post
$commentSql = "SELECT c.comment, c.created_at, u.username 
               FROM post_comments c
               JOIN users u ON c.user_id = u.user_id
               WHERE c.id = ?
               ORDER BY c.created_at DESC";
$stmt = $conn->prepare($commentSql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$comments = $stmt->get_result();
$stmt->close();

// Determine user avatar
$avatarFiles = glob("../images/avatars/{$post['username']}.*");
$userAvatar = count($avatarFiles) ? $avatarFiles[0] : "../images/avatars/default.jpg";

// Determine event image
$files = glob("../posts_uploads/{$post['id']}.*");
$imagePath = count($files) ? $files[0] : "../images/avatars/default.jpg";

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learn and Share</title>
    <link rel="stylesheet" href="./app_styles.css">
    <link rel="stylesheet" href="../components/components_styles.css">
</head>

<body>
    <?php
    $page_title = "Home - Group 77";
    include '../components/header.php';
    ?>
    <main >
        <div class="topic-header">
            <h1>Learn and Share</h1>
            <p>I'm a paragraph. Click here to add your own text and edit me. I'm a great place for you to tell a story and let your users know a little more about you.</p>
        </div>

        <!--  Post Section -->
        <section class="post-card">
            <div class="post-container">
            <div class="post-header">
                <div class="community-info">
                    <img src="<?php echo $userAvatar; ?>" alt="user image" class="avatar">
                    <div>
                        <h3><?php echo htmlspecialchars($post['username']); ?></h3>
                        <p><?php echo date("M d, Y", strtotime($post['created_at'])); ?></p>
                    </div>
                </div>
            </div>

            <div class="post-content">
                <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                <img src="<?php echo $imagePath; ?>" alt="post_image" class="post-image">
                <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p></p>
            </div>
</div>
        </section>

        <!-- Separate Comments Section -->
        <section class="comments-section">
            <h3>Comments</h3>
            <?php if (!empty($commentMessage)) : ?>
                <p class="comment-message"><?php echo htmlspecialchars($commentMessage); ?></p>
            <?php endif; ?>

            <form action="post_page.php?id=<?php echo $post_id; ?>" method="post">
                <textarea name="comment" placeholder="Write your comment..."></textarea>
                <button type="submit" name="comment_submit" class="btn-blue">Publish</button>
            </form>

            <div class="comments-list">
            <?php
            if ($commentsResult && $commentsResult->num_rows > 0) {
                while ($comment = $commentsResult->fetch_assoc()) {
                    echo '<div class="comment">';
                    echo '<strong>' . htmlspecialchars($comment['username']) . '</strong>';
                    echo '<small> - ' . htmlspecialchars($comment['created_at']) . '</small>';
                    echo '<p>' . nl2br(htmlspecialchars($comment['comment'])) . '</p>';

                    // Show delete button for super_admin, post owner, or comment owner
                    if (
                        (isset($_SESSION['role']) && $_SESSION['role'] === 'super_admin') ||
                        (isset($_SESSION['user_id']) && ($_SESSION['user_id'] == $post['user_id'] || $_SESSION['user_id'] == $comment['user_id']))
                    ) {
                        echo '<a href="post_page.php?id=' . $post_id . '&delete_comment=' . $comment['id'] . '" class="btn-red" onclick="return confirm(\'Delete this comment?\')">Delete</a>';
                    }

                    echo '</div>';
                }
            } else {
                echo "<p>No comments yet.</p>";
            }
            ?>
            </div>
        </section>

    </main>

    <!-- Footer -->

</body>

</html>

<?php
// Include footer
include '../components/footer.php';
?>

