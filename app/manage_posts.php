<?php
session_start();
require_once '../database/db.php';

// Restrict access to super_admin only
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'super_admin') {
    header("Location: ../index.php");
    exit;
}

// Initialize message variable
$message = "";

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Update post status
    if (isset($_POST['id'], $_POST['status'])) {
        $postId = intval($_POST['id']);
        $newStatus = $_POST['status'];
        $allowedStatuses = array('pending', 'approved', 'rejected');

        if (in_array($newStatus, $allowedStatuses)) {
            $stmt = $conn->prepare("UPDATE posts SET status=? WHERE id=?");
            $stmt->bind_param("si", $newStatus, $postId);
            if ($stmt->execute()) {
                $message = "Post status updated successfully!";
            } else {
                $message = "Error updating post status: " . $conn->error;
            }
            $stmt->close();
        } else {
            $message = "Invalid status selection";
        }
    }

    // Delete post
    if (isset($_POST['delete_id'])) {
        $postId = intval($_POST['delete_id']);
        $stmt = $conn->prepare("DELETE FROM posts WHERE id=?");
        $stmt->bind_param("i", $postId);
        if ($stmt->execute()) {
            $message = "Post deleted successfully!";
        } else {
            $message = "Error deleting post: " . $conn->error;
        }
        $stmt->close();
    }
}

// Fetch all posts
$posts = $conn->query("SELECT id, title, user_id, status, created_at FROM posts ORDER BY created_at DESC");
if (!$posts) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Posts - Super Admin</title>
    <link rel="stylesheet" href="../components/components_styles.css">
    <link rel="stylesheet" href="./app_styles.css">
    <link rel="stylesheet" href="./admin_styles.css">

    <script>
        function confirmDelete(id, title) {
            if (confirm("Are you sure you want to delete the post: '" + title + "'? This action cannot be undone.")) {
                document.getElementById("delete-form-" + id).submit();
            }
        }

        function confirmUpdate(id, title, status) {
            if (confirm("Are you sure you want to update post: '" + title + "' to status '" + status + "'?")) {
                document.getElementById("update-form-" + id).submit();
            }
        }

        window.addEventListener('DOMContentLoaded', () => {
            const message = <?= json_encode($message) ?>;
            if (message) {
                const msgBox = document.getElementById('message-box');
                if(msgBox){
                    msgBox.textContent = message;
                    msgBox.style.display = 'block';
                    setTimeout(() => { msgBox.style.display = 'none'; }, 3000);
                }
            }
        });
    </script>
</head>
<body>
<?php include '../components/header.php'; ?>

<!-- Management Links -->
    <div class="admin-actions">
      <a href="manage_users.php" class="btn-blue">Manage Users</a>
      <a href="manage_posts.php" class="btn-blue">Manage Posts</a>
      <a href="manage_events.php" class="btn-blue">Manage Events</a>
      <a href="manage_communities.php" class="btn-blue">Manage Communities</a>
      <a href="manage_contacts.php" class="btn-blue">User Inquiries</a>
    </div>

<main class="admin-container">
    <h1>Manage Posts</h1>

    <p id="message-box" class="notice" style="display:none;"></p>

    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>User ID</th>
            <th>Status</th>
            <th>Actions</th>
            <th>Created At</th>
        </tr>
        <?php while ($post = $posts->fetch_assoc()): ?>
            <tr>
                <td><?= $post['id'] ?></td>
                <td>
                    <a href="post_page.php?id=<?= $post['id'] ?>" class="post-link">
                        <?= htmlspecialchars($post['title']) ?>
                    </a>
                </td>
                <td><?= htmlspecialchars($post['user_id']) ?></td>
                <td>
                    <form method="POST" action="" id="update-form-<?= $post['id'] ?>">
                        <input type="hidden" name="id" value="<?= $post['id'] ?>">
                        <select name="status" id="status-select-<?= $post['id'] ?>">
                            <option value="pending" <?= $post['status']==='pending'?'selected':'' ?>>Pending</option>
                            <option value="approved" <?= $post['status']==='approved'?'selected':'' ?>>Approved</option>
                            <option value="rejected" <?= $post['status']==='rejected'?'selected':'' ?>>Rejected</option>
                        </select>
                        <button type="button" class="btn btn-blue" style="display:inline-block;"
                                onclick="confirmUpdate(<?= $post['id'] ?>, '<?= htmlspecialchars($post['title']) ?>', document.getElementById('status-select-<?= $post['id'] ?>').value)">
                            Update
                        </button>
                    </form>
                </td>
                <td>
                    <form method="POST" action="" id="delete-form-<?= $post['id'] ?>" style="display:inline-block;">
                        <input type="hidden" name="delete_id" value="<?= $post['id'] ?>">
                        <button type="button" class="btn-red"
                                onclick="confirmDelete(<?= $post['id'] ?>, '<?= htmlspecialchars($post['title']) ?>')">
                            Delete
                        </button>
                    </form>
                </td>
                <td><?= $post['created_at'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</main>

<?php include '../components/footer.php'; ?>
</body>
</html>
