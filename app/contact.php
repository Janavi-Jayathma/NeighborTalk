<?php

require_once '../components_styles.css';
include '../components/header.php';
// You can set a page title dynamically from any page using:
$page_title = "Contact Us | ABC Company";

// You would typically handle form submission logic here
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process the contact form data
    $name = $_POST['name'];
    $contact_number = $_POST['contact_number'];
    $email_address = $_POST['email_address'];
    $description = $_POST['description'];
    // ... validation and database insertion ...
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'ABC Company'; ?></title>
    <link rel="stylesheet" href="app_styles.css">
</head>
<body>
    
    <main class="main-content">
        <div class="support-header">
            <h1>Contact Us</h1>
            <p>I'm a paragraph. Click here to add your own text and edit me. I'm a great place for you to tell a story and let your users know a little more about you.</p>
        </div>

        <div class="donations-container">
            <div class="donations-form-section">
                <h2>Send Us a Message</h2>
                <div class="donation-info">
                    <p>Fill out the form below and we'll get back to you shortly.</p>
                </div>
                <form action="contact.php" method="post">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name">
                    </div>
                    <div class="form-group">
                        <label for="contact_number">Contact Number</label>
                        <input type="text" id="contact_number" name="contact_number">
                    </div>
                    <div class="form-group">
                        <label for="email_address">Email Address</label>
                        <input type="email" id="email_address" name="email_address">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description"></textarea>
                    </div>
                    <div class="form-buttons">
                        <button type="submit" class="btn donate-btn">Send Message</button>
                    </div>
                </form>
            </div>
            <div class="donations-image-section">
                <img src="../images/contact.png" alt="Contact Us Illustration">
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
