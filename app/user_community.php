<?php
session_start();
require_once '../database/db.php';

// Check if community ID is provided in URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Community ID not provided.");
}

$community_id = intval($_GET['id']);

// Fetch community details from database
$stmt = $conn->prepare("SELECT * FROM communities WHERE id = ?");
$stmt->bind_param("i", $community_id);
$stmt->execute();
$result = $stmt->get_result();
$community = $result->fetch_assoc();
$stmt->close();

if (!$community) {
    die("Community not found.");
}

// Set page title dynamically
$page_title = htmlspecialchars($community['name']) . " | ABC Community";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us</title>
  <link rel="stylesheet" href="app_styles.css">
  <link rel="stylesheet" href="../components/components_styles.css">
</head>

<body>
  <?php
  $page_title = "ABC Community";
  include '../components/header.php';
  ?>
  <main>
    <!-- About Header Section -->
    <div class="topic-header">
      <h1><?php echo htmlspecialchars($community['name']); ?></h1>
      <a href="events.php?community_id=<?php echo $community['id']; ?>" class="btn-blue">Community Events</a>
    </div>


    <!-- Vision and Mission -->
    <section class="vision-mission">
      <div class="outlined-card-wrap card-wrap">
        <h2>Our Vision</h2>
        <p><?php echo nl2br(htmlspecialchars($community['vision'])); ?>.</p>
      </div>
      <div class="outlined-card-wrap card-wrap">
        <h2>Our Mission</h2>
        <p><?php echo nl2br(htmlspecialchars($community['mission'])); ?></p>
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