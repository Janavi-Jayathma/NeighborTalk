<?php
session_start();
require_once '../database/db.php';

// Restrict access to admins only
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'super_admin') {
    header("Location: ../index.php");
    exit;
}

// Initialize message variable
$message = "";

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Update role
    if (isset($_POST['user_id'], $_POST['role'])) {
        $userId = intval($_POST['user_id']);
        $newRole = $_POST['role'];
        $allowedRoles = array('user','admin','super_admin');

        if (in_array($newRole, $allowedRoles)) {
            $stmt = $conn->prepare("UPDATE users SET role=? WHERE user_id=?");
            $stmt->bind_param("si", $newRole, $userId);
            if ($stmt->execute()) {
                $message = "User role updated successfully!";
            } else {
                $message = "Error updating role: ".$conn->error;
            }
            $stmt->close();
        } else {
            $message = "Invalid role selection";
        }
    }

    // Delete user
    if (isset($_POST['delete_user_id'])) {
        $userId = intval($_POST['delete_user_id']);
        $stmt = $conn->prepare("DELETE FROM users WHERE user_id=?");
        $stmt->bind_param("i",$userId);
        if($stmt->execute()){
            $message = "User deleted successfully!";
        } else {
            $message = "Error deleting user: ".$conn->error;
        }
        $stmt->close();
    }
}

// Fetch all users
$users = $conn->query("SELECT user_id, username, email_address, role FROM users ORDER BY user_id DESC");

if (!$users) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users - Admin</title>
    <link rel="stylesheet" href="../components/components_styles.css">
    <link rel="stylesheet" href="./app_styles.css">
    <link rel="stylesheet" href="./admin_styles.css">

    <script>
        function confirmDelete(userId, username) {
            if (confirm("Are you sure you want to delete user '" + username + "'? This action cannot be undone.")) {
                document.getElementById("delete-form-" + userId).submit();
            }
        }

        window.addEventListener('DOMContentLoaded', () => {
            const message = <?= json_encode($message) ?>;
            if (message) {
                const msgBox = document.getElementById('message-box');
                msgBox.textContent = message;
                msgBox.style.display = 'block';
                setTimeout(() => { msgBox.style.display = 'none'; }, 3000);
            }
        });

        function confirmUpdate(userId, username, selectedRole) {
            if (confirm("Are you sure you want to update user '" + username + "' to role '" + selectedRole + "'?")) {
                document.getElementById("update-form-" + userId).submit();
            }
        }

        function confirmDelete(userId, username) {
            if (confirm("Are you sure you want to delete user '" + username + "'? This action cannot be undone.")) {
                document.getElementById("delete-form-" + userId).submit();
            }
        }
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
    <h1>Manage Users</h1>

    <table>
        <tr>
            <th>ID</th><th>Username</th><th>Email</th><th>Role</th><th></th>
        </tr>
        <?php while ($user = $users->fetch_assoc()): ?>
            <tr>
                <td><?= $user['user_id'] ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['email_address']) ?></td>
                <td>
                    <form method="POST" action="" class="role-form" id="update-form-<?= $user['user_id'] ?>">
                        <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                        <div class="role-actions">
                            <select name="role" class="role-select" id="admin-select-<?= $user['user_id'] ?>">
                                <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Community Admin</option>
                                <option value="super_admin" <?= $user['role'] === 'super_admin' ? 'selected' : '' ?>>Admin</option>
                            </select>
                            <button type="button" class="btn btn-blue"
                                    onclick="confirmUpdate(<?= $user['user_id'] ?>, '<?= htmlspecialchars($user['username']) ?>', document.getElementById('role-select-<?= $user['user_id'] ?>').value)">
                                Update
                            </button>
                        </div>
                    </form>
                </td>
                <td>
                    <div>
                    <form method="POST" action="" id="delete-form-<?= $user['user_id'] ?>" style="display:inline-block;">
                        <input type="hidden" name="delete_user_id" value="<?= $user['user_id'] ?>">
                        <button type="button" class="btn-red"
                                onclick="confirmDelete(<?= $user['user_id'] ?>, '<?= htmlspecialchars($user['username']) ?>')">
                            Delete
                        </button>
                    </form>
                    </div>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</main>

<?php include '../components/footer.php'; ?>
</body>
</html>
