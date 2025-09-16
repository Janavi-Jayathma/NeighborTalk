<?php
include '../database/db.php';
session_start();

// Set the community ID or admin username
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
$community = null;
$principles = array();

// Fetch community details
$stmt = $conn->prepare("SELECT id, name, vision, mission FROM communities WHERE admin_username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$community = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Fetch core principles
if ($community && $community['id']) {
    $community_id = $community['id'];
    $stmt = $conn->prepare("SELECT principle, description FROM community_principles WHERE community_id = ? ORDER BY id ASC");
    $stmt->bind_param("i", $community_id);
    $stmt->execute();
    $principles = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars(isset($community['name']) ? $community['name'] : 'Community'); ?></title>
  <link rel="stylesheet" href="app_styles.css">
  <link rel="stylesheet" href="../components/components_styles.css">
</head>

<body>
  <?php
    $page_title = isset($community['name']) ? $community['name'] : 'Community';
    include '../components/header.php';
  ?>
  <main>
    <!-- Community Profile Header Section -->
    <div class="topic-header">
      <h1><?php echo htmlspecialchars(isset($community['name']) ? $community['name'] : 'Community'); ?></h1>
      <a href="events.php?community_id=<?= urlencode($community['id']) ?>" class="btn-blue">Community Events</a>

    </div>
    

    <?php
    if (htmlspecialchars($_SESSION['role']) == 'admin') {
      echo '<div style="width: 100%; margin: auto; display: flex; justify-content: center; gap: 1rem; margin-bottom: 2rem;">
      <a href="community-profile-edit.php" class="btn-blue">Edit Community Details</a>
      <a href="../app/add_event.php" class="btn-blue">Add New Event</a>
    </div>';
    }
    ?>


    <!-- Vision and Mission -->
    <section class="vision-mission">
      <div class="outlined-card-wrap card-wrap">
        <h2>Our Vision</h2>
        <p><?php echo htmlspecialchars(isset($community['vision']) ? $community['vision'] : 'Community vision will appear here.'); ?></p>
      </div>
      <div class="outlined-card-wrap card-wrap">
        <h2>Our Mission</h2>
        <p><?php echo htmlspecialchars(isset($community['mission']) ? $community['mission'] : 'Community mission will appear here.'); ?></p>
      </div>
    </section>

    <!-- Core Principles -->
  <section class="outlined-card-wrap">
    <h2 style="text-align: center;">Core Principles</h2>
    <div class="principles-grid">
      <?php if (!empty($principles)): ?>
        <?php foreach ($principles as $p): ?>
          <div class="principle-p-wrap">
            <h2><?php echo htmlspecialchars($p['principle']); ?></h2>
            <p><?php echo htmlspecialchars($p['description']); ?></p>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p style="text-align:center;">No principles found for this community.</p>
      <?php endif; ?>
    </div>
  </section>
  </main>
</body>
<?php
// Include footer
include '../components/footer.php';
?>

</html>