<?php
require_once '../database/db.php';
$page_title = "Learn and Share";

// Fetch posts with author details
$sql = "SELECT p.*, u.username 
        FROM posts p
        JOIN users u ON p.user_id = u.user_id
        ORDER BY p.created_at DESC";

$result = $conn->query($sql);
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'ABC Company'; ?></title>
    <link rel="stylesheet" href="./app_styles.css">
    <link rel="stylesheet" href="../components/components_styles.css">
</head>

<body>
    <?php
    $page_title = "Home - Group 77";
    include '../components/header.php';
    ?>
    <main>
        <div class="topic-header">
            <h1>Learn and Share</h1>
            <p>I'm a paragraph. Click here to add your own text and edit me. I'm a great place for you to tell a story and let your users know a little more about you.</p>
        </div>

        <div style="margin-bottom: 20px;">
            <a href="learn_and_share.php" class="btn-blue">All Posts</a>
            <a href="my_posts.php" class="btn-blue">My Posts</a>
        </div>

        <div class="content-section">
            <div class="topic-cards">
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($post = $result->fetch_assoc()): ?>
                        <?php
                            $files = glob("../posts_uploads/{$post['id']}.*");
                            $imagePath = count($files) ? $files[0] : "../images/event-sample.png";
                        ?>
                        <a class="card" href="post_page.php?id=<?= $post['id'] ?>">
                            <img src="<?= $imagePath ?>" alt="Post Image">
                            <div class="card-content">
                                <h3><?= htmlspecialchars($post['title']) ?></h3>
                                <p><?= htmlspecialchars(substr($post['content'], 0, 200)) ?>...</p>
                                <small>By: <?= htmlspecialchars($post['username']) ?></small>
                            </div>
                        </a>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No posts found.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Footer -->

</body>

</html>

<?php
// Include footer
include '../components/footer.php';
?>