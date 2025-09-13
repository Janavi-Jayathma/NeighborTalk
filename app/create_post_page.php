<?php
require_once '../database/db.php';
// You can set a page title dynamically from any page using:
$page_title = "Event page | ABC Community";

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
    <main >
        <div class="topic-header">
            <h1>Learn and Share</h1>
            <p>I'm a paragraph. Click here to add your own text and edit me. I'm a great place for you to tell a story and let your users know a little more about you.</p>
        </div>

    <!-- Event Form -->
    <section class="post-form">
        <form action="post_page.php" method="post" enctype="multipart/form-data">
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

            <div class="form-group file-upload">
                <label for="attachment">File Attachment</label>
                <input type="file" id="attachment" name="attachment" accept="image/*">
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn-blue">Publish</button>
                <button type="reset" class="btn-red">Clear</button>
            </div>
        </form>
    </section>

    </main>

    <!-- Footer -->

</body>

</html>

<?php
// Include footer
include '../components/footer.php';
?>

