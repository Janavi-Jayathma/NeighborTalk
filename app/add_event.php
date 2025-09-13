<?php
session_start();
require_once '../database/db.php';

// Get logged in username
$admin_username = $_SESSION['username'];


// Initialize variables
$community = null;
$community_id = null;
$community_name = "Unknown";
$page_title = "Community | Publish Event";

// Connect to DB
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch community info for logged-in user
$stmt = $conn->prepare("SELECT id, name FROM communities WHERE admin_username = ? LIMIT 1");
$stmt->bind_param("s", $admin_username);
$stmt->execute();
$result = $stmt->get_result();
$community = $result->fetch_assoc();
$stmt->close();

if ($community) {
    $community_id = $community['id'];
    $community_name = $community['name'];
    $page_title = htmlspecialchars($community_name) . " | Publish Event";
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $type = trim($_POST['type']);
    $description = trim($_POST['description']);
    $content = trim($_POST['content']);

    // Insert into events table without attachment first
    $stmt = $conn->prepare("INSERT INTO events (community_id, community_name, title, type, description, content) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $community_id, $community_name, $title, $type, $description, $content);

    if ($stmt->execute()) {
        // Get the inserted event ID
        $event_id = $stmt->insert_id;
        $stmt->close();

        // Handle file upload if exists
        if (!empty($_FILES['attachment']['name']) && $_FILES['attachment']['error'] === 0) {
            $uploadDir = "../event_uploads/";
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Extract original file extension
            $ext = pathinfo($_FILES["attachment"]["name"], PATHINFO_EXTENSION);
            $attachmentPath = $uploadDir . $event_id . "." . $ext;

            move_uploaded_file($_FILES["attachment"]["tmp_name"], $attachmentPath);

            // Update the event record with attachment path
            $updateStmt = $conn->prepare("UPDATE events SET attachment = ? WHERE id = ?");
            $updateStmt->bind_param("si", $attachmentPath, $event_id);
            $updateStmt->execute();
            $updateStmt->close();
        }

        echo "<script>alert('Event published successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="./app_styles.css">
    <link rel="stylesheet" href="../components/components_styles.css">
</head>

<body>
    <?php
    $page_title = "Home - Group 77";
    include '../components/header.php';
    ?>
    <main class="center-card-wrap">
        <div class="topic-unwrap">
            <h1>Publish Your Event</h1>
            <p>Short Description- Something suitable</p>
        </div>

        <!-- Event Form -->
        <section class="post-form">
            <form action="add_event.php" method="post" enctype="multipart/form-data">
                <div class="upload-btn-wrapper">
                    <label for="attachment">Upload Image</label>
                    <input type="file" id="attachment" name="attachment" accept="image/*">
                </div>
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title">
                </div>

                <div class="form-group">
                    <label for="type">Event Type</label>
                    <input type="text" id="type" name="type">
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description"></textarea>
                </div>

                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea id="content" name="content"></textarea>
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn-blue">Publish</button>
                    <button type="reset" class="btn-red">Clear</button>
                </div>
            </form>
        </section>

    </main>
</body>

</html>

<?php
// Include footer
include '../components/footer.php';
?>