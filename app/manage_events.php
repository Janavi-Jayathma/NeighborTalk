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

    // Update event status
    if (isset($_POST['id'], $_POST['status'])) {
        $eventId = intval($_POST['id']);
        $newStatus = $_POST['status'];
        $allowedStatuses = array('pending', 'approved', 'rejected');

        if (in_array($newStatus, $allowedStatuses)) {
            $stmt = $conn->prepare("UPDATE events SET status=? WHERE id=?");
            $stmt->bind_param("si", $newStatus, $eventId);
            if ($stmt->execute()) {
                $message = "Event status updated successfully!";
            } else {
                $message = "Error updating status: " . $conn->error;
            }
            $stmt->close();
        } else {
            $message = "Invalid status selection";
        }
    }

    // Delete event
    if (isset($_POST['delete_id'])) {
        $eventId = intval($_POST['delete_id']);
        $stmt = $conn->prepare("DELETE FROM events WHERE id=?");
        $stmt->bind_param("i", $eventId);
        if ($stmt->execute()) {
            $message = "Event deleted successfully!";
        } else {
            $message = "Error deleting event: " . $conn->error;
        }
        $stmt->close();
    }
}

// Fetch all events
$events = $conn->query("
    SELECT id, community_id, community_name, title, type, status, created_at
    FROM events
    ORDER BY created_at DESC
");
if (!$events) die("Query failed: " . $conn->error);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Events - Super Admin</title>
    <link rel="stylesheet" href="../components/components_styles.css">
    <link rel="stylesheet" href="./app_styles.css">
    <link rel="stylesheet" href="./admin_styles.css">

    <script>
        function confirmDelete(id, title) {
            if (confirm("Are you sure you want to delete the event: '" + title + "'?")) {
                document.getElementById("delete-form-" + id).submit();
            }
        }

        function confirmUpdate(id, title) {
            document.getElementById("update-form-" + id).submit();
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
    <h1>Manage Events</h1>
    <p id="message-box" class="notice" style="display:none;"></p>

    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Community</th>
            <th>Status</th>
            <th>Actions</th>
            <th>Created At</th>
        </tr>
        <?php while ($event = $events->fetch_assoc()): ?>
            <tr>
                <td><?= $event['id'] ?></td>
                <td>
                    <a href="event_page.php?id=<?= $event['id'] ?>" class="event-link">
                        <?= htmlspecialchars($event['title']) ?>
                    </a>
                </td>
                <td><?= htmlspecialchars($event['community_name']) ?> (ID: <?= $event['community_id'] ?>)</td>
                <td>
                    <form method="POST" action="" id="update-form-<?= $event['id'] ?>">
                        <input type="hidden" name="id" value="<?= $event['id'] ?>">
                        <select name="status" id="status-select-<?= $event['id'] ?>">
                            <option value="pending" <?= $event['status']==='pending'?'selected':'' ?>>Pending</option>
                            <option value="approved" <?= $event['status']==='approved'?'selected':'' ?>>Approved</option>
                            <option value="rejected" <?= $event['status']==='rejected'?'selected':'' ?>>Rejected</option>
                        </select>
                        <button type="button" class="btn btn-blue"
                                onclick="confirmUpdate(<?= $event['id'] ?>)">
                            Update
                        </button>
                    </form>
                </td>
                <td>
                    <form method="POST" action="" id="delete-form-<?= $event['id'] ?>" style="display:inline-block;">
                        <input type="hidden" name="delete_id" value="<?= $event['id'] ?>">
                        <button type="button" class="btn-red"
                                onclick="confirmDelete(<?= $event['id'] ?>, '<?= htmlspecialchars($event['title']) ?>')">
                            Delete
                        </button>
                    </form>
                </td>
                <td><?= $event['created_at'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</main>

<?php include '../components/footer.php'; ?>
</body>
</html>
