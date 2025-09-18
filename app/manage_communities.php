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

// Handle delete action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);
    $stmt = $conn->prepare("DELETE FROM communities WHERE id=?");
    $stmt->bind_param("i", $id);
    if($stmt->execute()){
        $message = "Community deleted successfully!";
    } else {
        $message = "Error deleting community: " . $conn->error;
    }
    $stmt->close();
}

// Fetch all communities
$communities = $conn->query("SELECT id, admin_username, name, vision, mission, admin_id FROM communities ORDER BY id DESC");
if (!$communities) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Communities - Super Admin</title>
    <link rel="stylesheet" href="../components/components_styles.css">
    <link rel="stylesheet" href="./app_styles.css">
    <link rel="stylesheet" href="./admin_styles.css">

    <script>
        function confirmDelete(id, name) {
            if (confirm("Are you sure you want to delete community '" + name + "'? This action cannot be undone.")) {
                document.getElementById("delete-form-" + id).submit();
            }
        }

        function viewCommunity(id) {
            window.location.href = "user_community.php?id=" + id;
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
    <h1>Manage Communities</h1>
    <p id="message-box" class="notice" style="display:none;"></p>

    <table>
        <tr>
            <th>ID</th>
            <th>Admin Username</th>
            <th>Name</th>
            <th>Vision</th>
            <th>Mission</th>
            <th>Admin ID</th>
            <th>Actions</th>
        </tr>
        <?php while ($community = $communities->fetch_assoc()): ?>
            <tr>
                <td><?= $community['id'] ?></td>
                <td><?= htmlspecialchars($community['admin_username']) ?></td>
                <td><?= htmlspecialchars($community['name']) ?></td>
                <td><?= htmlspecialchars($community['vision']) ?></td>
                <td><?= htmlspecialchars($community['mission']) ?></td>
                <td><?= $community['admin_id'] ?></td>
                <td>
                    <button class="btn btn-blue" onclick="viewCommunity(<?= $community['id'] ?>)">View</button>

                    <form method="POST" action="" id="delete-form-<?= $community['id'] ?>" style="display:inline-block;">
                        <input type="hidden" name="delete_id" value="<?= $community['id'] ?>">
                        <button type="button" class="btn-red"
                                onclick="confirmDelete(<?= $community['id'] ?>, '<?= htmlspecialchars($community['name']) ?>')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</main>

<?php include '../components/footer.php'; ?>
</body>
</html>
