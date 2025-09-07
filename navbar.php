<?php
// ---------- Database Connection ----------
$host = "localhost";
$user = "root";
$password = "";
$dbname = "group_77_db"; // ✅ Change this to your real DB name

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    // If DB fails, show fallback header (no errors)
    $db_error = true;
} else {
    $sql = "SELECT name, url FROM navbar_links";
    $result = $conn->query($sql);
    if (!$result) {
        $db_error = true;
    } else {
        $db_error = false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Learn and Share</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <!-- HEADER / NAVBAR -->
  <nav class="navbar">
    <!-- Left: Home Icon -->
    <div class="navbar-left">
      <a href="index.php"><img src="house.png" alt="Home" class="home-icon"></a>
    </div>

    <!-- Center: Navigation Links -->
    <div class="navbar-center">
      <?php
      if (!$db_error && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              echo '<a href="'.$row['url'].'">'.$row['name'].'</a>';
          }
      } else {
          // ✅ Fallback static links (no DB required)
          echo '<a href="index.php">Home</a>';
          echo '<a href="stay-informed.php">Stay Informed</a>';
          echo '<a href="support.php">Support</a>';
          echo '<a href="contact.php">Contact Us</a>';
          echo '<a href="about.php">About Us</a>';
      }
      ?>
    </div>

    <!-- Right: Create Button + Profile Icon -->
    <div class="navbar-right">
      <a href="create-post.php" class="create-btn">Create a post</a>
      <a href="profile.php"><img src="user.png" alt="Profile" class="profile-icon"></a>
    </div>
  </nav>
</body>
</html>

<?php if (isset($conn) && $conn && !$db_error) $conn->close(); ?>
