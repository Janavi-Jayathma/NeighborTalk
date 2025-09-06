<?php
// Database connection
$host = "localhost";
$user = "root";
$password = "";
$dbname = "group_77_db";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch navbar links
$sql = "SELECT name, url FROM navbar_links";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Navigation Bar</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <nav class="navbar">
    <div class="navbar-left">
      <a href="index.php"><img src="house.png" alt="Home" class="home-icon"></a>
    </div>

    <div class="navbar-center">
      <?php
      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              echo '<a href="'.$row['url'].'">'.$row['name'].'</a>';
          }
      }
      ?>
    </div>

    <div class="navbar-right">
      <a href="create-post.php" class="create-btn">Create a post</a>
      <a href="profile.php"><img src="user.png" alt="Profile" class="profile-icon"></a>
    </div>
  </nav>
</body>
</html>

<?php $conn->close(); ?>
