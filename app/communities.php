<?php
session_start();
require_once '../database/db.php';
// You can set a page title dynamically from any page using:
$page_title = "Communities";

// Fetch all communities
$sql = "SELECT id, name, vision, mission, admin_username FROM communities";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'ABC Company'; ?></title>
    <link rel="stylesheet" href="./app_styles.css">
    <link rel="stylesheet" href="../components/components_styles.css">
</head>

<body>
    <?php
    include '../components/header.php';
    ?>
    <main>
        <div class="topic-header">
            <h1>Communities</h1>
            <p>I'm a paragraph. Click here to add your own text and edit me. I'm a great place for you to tell a story and let your users know a little more about you.</p>
        </div>

        <div class="content-section">
            <div class="topic-cards">
                <?php
                    if ($result && $result->num_rows > 0) {
                        while ($community = $result->fetch_assoc()) {
                            $adminUsername = isset($community['admin_username']) ? $community['admin_username'] : '';
                            
                            // Find avatar image using any common extension
                            $files = glob("../images/avatars/{$adminUsername}.*");
                            if (!empty($files)) {
                                $imagePath = $files[0];
                            } else {
                                $imagePath = "../images/avatars/default.jpg"; // fallback
                            }
                            ?>
                            <a class="card" href="user_community.php?id=<?php echo $community['id']; ?>">
                                <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Community Admin Image">
                                <div class="card-content">
                                    <h3><?php echo htmlspecialchars($community['name']); ?></h3>
                                    <p><?php echo htmlspecialchars($community['vision']); ?></p>
                                </div>
                            </a>
                            <?php
                        }
                    } else {
                        echo "<p>No communities found.</p>";
                    }
                ?>
            </div>
        </div>
    </main>

    <!-- Footer -->

</body>

</html>

<?php
// Include footer
include '../components/footer.php';
?>