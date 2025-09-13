<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Community|Moderator Page</title>
  <link rel="stylesheet" href="app_styles.css">
  <link rel="stylesheet" href="../components/components_styles.css">
</head>

<body>
  <?php
  $page_title = "ABC Community|Moderator";
  include '../components/header.php';
  ?>
  <main>
    <!-- About Header Section -->
    <div class="topic-header">
      <h1>ABC Community</h1>
      <p>I'm a paragraph. Click here to add your own text and edit me.
        I'm a great place for you to tell a story and let your users know a little more about you.</p>
    </div>

    <!-- Vision and Mission -->
    <section class="vision-mission">
        <a href="" class="btn-blue">Edit Community Details</a>
        <a href="community_events.php" class="btn-blue">Current Events</a>
        <a href="" class="btn-blue">Add New Event</a>
      <div class="outlined-card-wrap card-wrap">
        <h2>Our Vision</h2>
        <p>I'm a paragraph. Click here to add your own text and edit me.
          I'm a great place for you to tell a story and let your users know a little more about you.</p>
      </div>
      <div class="outlined-card-wrap card-wrap">
        <h2>Our Mission</h2>
        <p>I'm a paragraph. Click here to add your own text and edit me.
          I'm a great place for you to tell a story and let your users know a little more about you.</p>
      </div>
    </section>

    <!-- Community Events -->
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

    <!-- Event Form -->
    <section class="event-form">
        <form action="moderator_community_page.php" method="post" enctype="multipart/form-data">
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
</body>
<?php
// Include footer
include '../components/footer.php';
?>

</html>