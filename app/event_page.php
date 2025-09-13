<?php
require_once '../database/db.php';
// You can set a page title dynamically from any page using:
$page_title = "Event page | ABC Community";

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
            <h1>Events</h1>
            <p>I'm a paragraph. Click here to add your own text and edit me. I'm a great place for you to tell a story and let your users know a little more about you.</p>
        </div>

        <!--  Post Section -->
        <section class="post-card">
            <div class="post-container">
            <div class="post-header">
                <div class="community-info">
                    <img src="avatar.png" alt="Community Logo" class="avatar">
                    <div>
                        <h3>ABC Community</h3>
                        <p>Aug 12, 2025</p>
                    </div>
                </div>
                <span class="tag">Virtual Webinar</span>
            </div>

            <div class="post-content">
                <h2>Official Public Health Guidance and and updates</h2>
                <img src="../images/image.png" alt="Child washing hands" class="post-image">
                <p>
                    Consumers expect personalized experiences. But how do you deliver tailored recommendations at scale? 
                    In this webinar, experts from Blue Robot and Zapier will share how to use the power of product 
                    recommendation quizzes to engage customers, collect valuable data, and drive conversions.
                </p>
                <p>📅 Thursday 20 March at 12PM Eastern Time</p>
                <p>What you'll learn:</p>
                <ul>
                    <li>✅ How to design high-converting quiz campaigns</li>
                    <li>✅ Best practices for testing and distributing your quizzes</li>
                    <li>✅ How to integrate quizzes with Zapier & other tools to automate workflows</li>
                </ul>
                <p>Spots are limited—register now!</p>
            </div>
</div>
        </section>

        <!-- Separate Comments Section -->
        <section class="comments-section">
            <h3>Comments</h3>
            <textarea placeholder="Write your comment..."></textarea>
            <div class="form-buttons">
                <button type="submit" class="btn-blue">Publish</button>
                <button type="reset" class="btn-red">Clear</button>
            </div>
            <small>Log in to publish as a member</small>
        </section>


        <div class="donations-container">
            <div class="donations-form-section">
                <h2>Register to the event</h2>
                <div class="donation-info">
                    <p>Fill out the form below and register to the event.</p>
                </div>
                <form action="contact.php" method="post">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name">
                    <label for="contact_number">Contact Number</label>
                    <input type="text" id="contact_number" name="contact_number">
                    <label for="email_address">Email Address</label>
                    <input type="email" id="email_address" name="email_address">
                    <label for="description">Description</label>
                    <textarea id="description" name="description"></textarea>
                    <button type="submit" class="btn-blue">Register</button>
                </form>
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

