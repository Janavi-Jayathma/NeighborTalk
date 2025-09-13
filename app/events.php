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

        <ul class="nav-links-events">
            <li><a href="" >Upcoming Events</a></li>
            <li><a href="" >Past Events</a></li>
        </ul>

        <div class="content-section">
                    <div class="topic-cards">
                        <div class="card">
                            <div class="card-image"></div>
                            <div class="card-content">
                                <h3>Ready to give your forms a post-holiday power up? We've got you covered! 🪄</h3>
                                <p>Summer's here, and it's time to power up your forms. Join us on August 26 for our Summer Release event, a webinar filled with insights, live demos, and an exclusive Q&A session to help you make your forms more powerful and more personalized.</p>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-image"></div>
                            <div class="card-content">
                                <h3>Ready to give your forms a post-holiday power up? We've got you covered! 🪄</h3>
                                <p>Summer's here, and it's time to power up your forms. Join us on August 26 for our Summer Release event, a webinar filled with insights, live demos, and an exclusive Q&A session to help you make your forms more powerful and more personalized.</p>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-image"></div>
                            <div class="card-content">
                                <h3>Ready to give your forms a post-holiday power up? We've got you covered! 🪄</h3>
                                <p>Summer's here, and it's time to power up your forms. Join us on August 26 for our Summer Release event, a webinar filled with insights, live demos, and an exclusive Q&A session to help you make your forms more powerful and more personalized.</p>
                            </div>
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

