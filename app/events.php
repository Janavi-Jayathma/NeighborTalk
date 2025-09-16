<?php
session_start();
require_once '../database/db.php';

// Page title
$page_title = "Events page | ABC Community";

// Default query → only approved events for normal users
$sql = "SELECT e.*, c.name AS community_name, u.username AS admin_username 
        FROM events e
        JOIN communities c ON e.community_id = c.id
        JOIN users u ON c.admin_id = u.user_id
        WHERE e.status = 'approved'
        ORDER BY e.created_at DESC";

// Check if logged-in user is admin
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin' && isset($_SESSION['user_id'])) {
    $adminId = intval($_SESSION['user_id']);

    // Get community managed by this admin
    $check = $conn->query("SELECT id FROM communities WHERE admin_id = $adminId LIMIT 1");

    if ($check && $check->num_rows > 0) {
        $community = $check->fetch_assoc();
        $community_id = intval($community['id']);

        // Admin can see all events (any status) in their community
        $sql = "SELECT e.*, c.name AS community_name, u.username AS admin_username 
                FROM events e
                JOIN communities c ON e.community_id = c.id
                JOIN users u ON c.admin_id = u.user_id
                WHERE e.community_id = $community_id
                ORDER BY e.created_at DESC";
    }
}

$result = $conn->query($sql);
if (!$result) {
    die("Query failed: " . $conn->error);
}
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
    $page_title = "Home - Group 77";
    include '../components/header.php';
    ?>
    <main>
        <div class="topic-header">
            <h1>Events</h1>
            <p>I'm a paragraph. Click here to add your own text and edit me. I'm a great place for you to tell a story and let your users know a little more about you.</p>
        </div>

        <div class="content-section">
            <div class="topic-cards">
                <?php
                if ($result && $result->num_rows > 0) {
                    while ($event = $result->fetch_assoc()) {
                        // Find uploaded image for this event
                        $files = glob("../event_uploads/{$event['id']}.*");
                        $imagePath = count($files) ? $files[0] : "../images/event-sample.png";
                        ?>
                        <a class="card" href="event_page.php?id=<?php echo $event['id']; ?>">
                            <img src="<?php echo $imagePath; ?>" alt="Event Image" class="card-image">
                            <div class="card-content">
                                <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                                <p><?php echo htmlspecialchars($event['description']); ?></p>
                                <h6>Community: <?php echo htmlspecialchars($event['community_name']); ?></h6>
                                <h6>Posted by: <?php echo htmlspecialchars($event['admin_username']); ?></h6>
                                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') { ?>
                                    <h6>Status: <?php echo htmlspecialchars($event['status']); ?></h6>
                                <?php } ?>
                            </div>
                        </a>
                        <?php
                    }
                } else {
                    echo "<p>No events found.</p>";
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