<?php
require_once '../database/db.php';
// You can set a page title dynamically from any page using:
$page_title = "Share your thoughts";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $content = trim($_POST['content']);

    // File upload
    $attachmentPath = null;
    if (!empty($_FILES['attachment']['name']) && $_FILES['attachment']['error'] === 0) {
        $uploadDir = "../posts_uploads/";
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $attachmentPath = $uploadDir . basename($_FILES["attachment"]["name"]);
        move_uploaded_file($_FILES["attachment"]["tmp_name"], $attachmentPath);
    }

    // Insert into DB
    $stmt = $conn->prepare("INSERT INTO posts (title, description, content, attachment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $description, $content, $attachmentPath);

    if ($stmt->execute()) {
        echo "<script>alert('Post published successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}
$conn->close();

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