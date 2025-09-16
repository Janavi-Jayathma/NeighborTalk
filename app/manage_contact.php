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

    // Update contact status
    if (isset($_POST['contact_id'], $_POST['status'])) {
        $contactId = intval($_POST['contact_id']);
        $newStatus = $_POST['status'];
        $allowedStatuses = array('new', 'in_progress', 'resolved');

        if (in_array($newStatus, $allowedStatuses)) {
            $stmt = $conn->prepare("UPDATE contacts SET status=? WHERE id=?");
            $stmt->bind_param("si", $newStatus, $contactId);
            if ($stmt->execute()) {
                $message = "Status updated successfully!";
            } else {
                $message = "Error updating status: " . $conn->error;
            }
            $stmt->close();
        } else {
            $message = "Invalid status selection";
        }
    }

    // Delete contact
    if (isset($_POST['delete_contact_id'])) {
        $contactId = intval($_POST['delete_contact_id']);
        $stmt = $conn->prepare("DELETE FROM contacts WHERE id = ?");
        $stmt->bind_param("i", $contactId);
        if ($stmt->execute()) {
            $message = "Contact deleted successfully!";
        } else {
            $message = "Error deleting contact: " . $conn->error;
        }
        $stmt->close();
    }
}

// Fetch all contacts
$contacts = $conn->query("SELECT id, name, contact_number, email_address, description, status, created_at FROM contacts ORDER BY created_at DESC");
if (!$contacts) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Contacts - Super Admin</title>
    <link rel="stylesheet" href="../components/components_styles.css">
    <link rel="stylesheet" href="./app_styles.css">
    <link rel="stylesheet" href="./admin_styles.css">

    <script>
        function confirmDelete(contactId, name) {
            if (confirm("Are you sure you want to delete contact: '" + name + "'? This action cannot be undone.")) {
                document.getElementById("delete-form-" + contactId).submit();
            }
        }

        function confirmUpdate(contactId, status) {
            document.getElementById("update-form-" + contactId).submit();
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
    </div>

<main class="admin-container">
    <h1>Manage Contacts</h1>

    <p id="message-box" class="notice" style="display:none;"></p>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Contact Number</th>
            <th>Email Address</th>
            <th>Description</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
        <?php while ($contact = $contacts->fetch_assoc()): ?>
            <tr>
                <td><?= $contact['id'] ?></td>
                <td><?= htmlspecialchars($contact['name']) ?></td>
                <td><?= htmlspecialchars($contact['contact_number']) ?></td>
                <td><?= htmlspecialchars($contact['email_address']) ?></td>
                <td><?= htmlspecialchars($contact['description']) ?></td>
                <td>
                    <form method="POST" action="" id="update-form-<?= $contact['id'] ?>">
                        <input type="hidden" name="contact_id" value="<?= $contact['id'] ?>">
                        <select name="status" onchange="confirmUpdate(<?= $contact['id'] ?>, this.value)">
                            <option value="new" <?= $contact['status']==='new'?'selected':'' ?>>New</option>
                            <option value="in_progress" <?= $contact['status']==='in_progress'?'selected':'' ?>>In Progress</option>
                            <option value="resolved" <?= $contact['status']==='resolved'?'selected':'' ?>>Resolved</option>
                        </select>
                    </form>
                </td>
                <td><?= $contact['created_at'] ?></td>
                <td>
                    <form method="POST" action="" id="delete-form-<?= $contact['id'] ?>" style="display:inline-block;">
                        <input type="hidden" name="delete_contact_id" value="<?= $contact['id'] ?>">
                        <button type="button" class="btn-red" onclick="confirmDelete(<?= $contact['id'] ?>, '<?= htmlspecialchars($contact['name']) ?>')">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</main>

<?php include '../components/footer.php'; ?>
</body>
</html>
