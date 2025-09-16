<?php
session_start();
require_once '../database/db.php';
// You can set a page title dynamically from any page using:
$page_title = "Share your thoughts";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    die("You must be logged in to create a post.");
}
$username = $_SESSION['username'];

// Get user_id from username
$stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    die("User not found.");
}

$user_id = $user['user_id'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $content = trim($_POST['content']);

    // Insert into events table without attachment first
    $stmt = $conn->prepare("INSERT INTO posts (user_id, title, description, content) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $title, $description, $content);

    if ($stmt->execute()) {
        $post_id = $stmt->insert_id;

    // Handle file upload if exists
        if (!empty($_FILES['attachment']['name']) && $_FILES['attachment']['error'] === 0) {
            $uploadDir = "../posts_uploads/";
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Extract original file extension
            $ext = pathinfo($_FILES["attachment"]["name"], PATHINFO_EXTENSION);
            $attachmentPath = $uploadDir . $post_id . "." . $ext;

            move_uploaded_file($_FILES["attachment"]["tmp_name"], $attachmentPath);

            // Update the event record with attachment path
            $updateStmt = $conn->prepare("UPDATE posts SET attachment = ? WHERE id = ?");
            $updateStmt->bind_param("si", $attachmentPath, $post_id);
            $updateStmt->execute();
            $updateStmt->close();

            echo "<script>alert('Post published successfully!');</script>";
        }
        else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}
$conn->close();
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
    <main class="center-card-wrap">
        <div class="topic-unwrap">
            <h1>Thoughts?</h1>
            <p>Your thoughts on this would be appreciated.</p>
        </div>

        <!-- Event Form -->
        <section class="post-form">
            <form action="create_post_page.php" method="post" enctype="multipart/form-data">
                <div class="upload-btn-wrapper">
                    <label for="attachment">Upload Image</label>
                    <input type="file" id="attachment" name="attachment" accept="image/*">
                </div>
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title">
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