<?php
session_start();
require_once '../database/db.php';

// Check if user is admin (adjust according to your system)
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'super_admin') {
    header("Location: ../auth/login.php");
    exit;
}

$page_title = "Admin Dashboard - Group 77";

// Stats Queries
$userResult = $conn->query("SELECT COUNT(*) AS total FROM users");
$userRow = $userResult ? $userResult->fetch_assoc() : null;
$usersCount = $userRow ? $userRow['total'] : 0;

$postResult = $conn->query("SELECT COUNT(*) AS total FROM posts");
$postRow = $postResult ? $postResult->fetch_assoc() : null;
$postsCount = $postRow ? $postRow['total'] : 0;

$eventResult = $conn->query("SELECT COUNT(*) AS total FROM events");
$eventRow = $eventResult ? $eventResult->fetch_assoc() : null;
$eventsCount = $eventRow ? $eventRow['total'] : 0;

$communityResult = $conn->query("SELECT COUNT(*) AS total FROM communities");
$communityRow = $communityResult ? $communityResult->fetch_assoc() : null;
$communitiesCount = $communityRow ? $communityRow['total'] : 0;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group 77 - Home</title>
    <link rel="stylesheet" href="./app_styles.css">
    <link rel="stylesheet" href="./admin_styles.css">
    <link rel="stylesheet" href="../components/components_styles.css">
</head>

<?php
$page_title = "Home - Group 77";
include '../components/header.php';
?>

<body>
  
  <main class="admin-container">
    <h1>Admin Dashboard</h1>
    <h3>Welcome, <?= htmlspecialchars($_SESSION['username']) ?> 👋</h3>

    <!-- Quick Stats -->
    <div class="stats-grid">
      <div class="stat-card">👤 Users <span><?= $usersCount ?></span></div>
      <div class="stat-card">📝 Posts <span><?= $postsCount ?></span></div>
      <div class="stat-card">📅 Events <span><?= $eventsCount ?></span></div>
      <div class="stat-card">🌐 Communities <span><?= $communitiesCount ?></span></div>
    </div>

    <!-- Management Links -->
    <div class="admin-actions">
      <a href="manage_users.php" class="btn-blue">Manage Users</a>
      <a href="manage_posts.php" class="btn-blue">Manage Posts</a>
      <a href="manage_events.php" class="btn-blue">Manage Events</a>
      <a href="manage_communities.php" class="btn-blue">Manage Communities</a>
    </div>

    <!-- Recent Activity -->
    <section class="recent-activity">
      <h2>Recent Posts</h2>
      <table>
        <tr><th>ID</th><th>Title</th><th>Author</th><th>Created</th></tr>
        <?php
        $recentPosts = $conn->query("SELECT p.id, p.title, u.username, p.created_at 
                                     FROM posts p JOIN users u ON p.user_id=u.user_id 
                                     ORDER BY p.created_at DESC LIMIT 5");
        while ($post = $recentPosts->fetch_assoc()):
        ?>
          <tr>
            <td><?= $post['id'] ?></td>
            <td><?= htmlspecialchars($post['title']) ?></td>
            <td><?= htmlspecialchars($post['username']) ?></td>
            <td><?= $post['created_at'] ?></td>
          </tr>
        <?php endwhile; ?>
      </table>
    </section>

    <section class="recent-activity">
      <h2>Recent Events</h2>
      <table>
        <tr><th>ID</th><th>Title</th><th>Community</th><th>Created</th></tr>
        <?php
        $recentEvents = $conn->query("SELECT e.id, e.title, c.name AS community, e.created_at 
                                      FROM events e JOIN communities c ON e.community_id=c.id 
                                      ORDER BY e.created_at DESC LIMIT 5");
        while ($event = $recentEvents->fetch_assoc()):
        ?>
          <tr>
            <td><?= $event['id'] ?></td>
            <td><?= htmlspecialchars($event['title']) ?></td>
            <td><?= htmlspecialchars($event['community']) ?></td>
            <td><?= $event['created_at'] ?></td>
          </tr>
        <?php endwhile; ?>
      </table>
    </section>
  </main>

  <?php include '../components/footer.php'; ?>
</body>
</html>
