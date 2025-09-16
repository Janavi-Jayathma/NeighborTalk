<?php
require_once '../database/db.php';
// You can set a page title dynamically from any page using:
$page_title = "Help and Support | ABC Company";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $name = trim($_POST['name']);
    $contact_number = trim($_POST['contact_number']);
    $email_address = trim($_POST['email_address']);
    $description = trim($_POST['description']);

    if (empty($name) || empty($contact_number) || empty($email_address) || empty($description)) {
        $error_message = "All fields are required!";
    } else {
        // Prepare and execute insert query
        $stmt = $conn->prepare("INSERT INTO contacts (name, contact_number, email_address, description) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $contact_number, $email_address, $description);

        if ($stmt->execute()) {
            $success_message = "Your message has been sent successfully!";
            $_POST = array();
        } else {
            $error_message = "Error: " . $stmt->error;
        }

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
            <h1>Help and Support</h1>
            <p>I'm a paragraph. Click here to add your own text and edit me. I'm a great place for you to tell a story and let your users know a little more about you.</p>
        </div>

        <div class="donations-container">
            <div class="donations-form-section">
                <h2>Send Us a Message</h2>
                <div class="donation-info">
                    <p>Fill out the form below and we'll get back to you shortly.</p>
                </div>
                <form action="contact.php" method="post">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars(isset($_POST['name']) ? $_POST['name'] : ''); ?>">
                    <label for="contact_number">Contact Number</label>
                    <input type="text" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars(isset($_POST['contact_number']) ? $_POST['contact_number'] : ''); ?>">
                    <label for="email_address">Email Address</label>
                    <input type="email" id="email_address" name="email_address" value="<?php echo htmlspecialchars($_POST['email_address'] ?? ''); ?>">
                    <label for="description">Description</label>
                    <textarea id="description" name="description"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                    <button type="submit" class="btn-blue">Send Message</button>
                </form>
            </div>
            <div class="donations-image-section">
                <img src="../images/contact.png" alt="Contact Us Illustration">
            </div>
        </div>

        <h1>Help & Support</h1>
    <p>Welcome to ABC Community Help Center. Here you can find answers to common questions and guidance on using the platform.</p>

    <!-- FAQ Section -->
    <section class="help-section">
        <h2>Frequently Asked Questions (FAQ)</h2>

        <div class="help-item">
            <p class="faq-question">1. How do I register for an event?</p>
            <p class="faq-answer">
                Go to the <a href="events.php" class="btn-blue">Events Page</a>, select an event, and fill out the registration form. You will receive a confirmation after successful registration.
            </p>
        </div>

        <div class="help-item">
            <p class="faq-question">2. How can I post in the "Learn and Share" section?</p>
            <p class="faq-answer">
                Navigate to <a href="learn_and_share.php" class="btn-blue">Learn and Share</a>. Click "Add New Post," enter your title, content, and optionally upload an image, then submit for approval.
            </p>
        </div>

        <div class="help-item">
            <p class="faq-question">3. How do I comment on an event or post?</p>
            <p class="faq-answer">
                Open the post or event page. Scroll down to the comment section, type your comment, and click "Publish." Your comment will appear immediately if you are logged in.
            </p>
        </div>

        <div class="help-item">
            <p class="faq-question">4. How do I edit or delete my posts or comments?</p>
            <p class="faq-answer">
                You can edit or delete your posts and comments using the edit/delete buttons available next to your content. Only super admins or content owners can delete comments.
            </p>
        </div>

        <div class="help-item">
            <p class="faq-question">5. Who can view registered users for an event?</p>
            <p class="faq-answer">
                Only the event owner (community admin) or super admins can view registered users. Use the "View Registered Users" button on the event page.
            </p>
        </div>
    </section>
    </main>

    <!-- Footer -->

</body>

</html>

<?php
// Include footer
include '../components/footer.php';
?>

