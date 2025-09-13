<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us</title>
  <link rel="stylesheet" href="app_styles.css">
  <link rel="stylesheet" href="../components/components_styles.css">
</head>

<body>
  <?php
  $page_title = "ABC Community";
  include '../components/header.php';
  ?>
  <main>
    <!-- About Header Section -->
    <div class="topic-header">
      <h1>ABC Community</h1>
      <p>I'm a paragraph. Click here to add your own text and edit me.
        I'm a great place for you to tell a story and let your users know a little more about you.</p>
        <a href="community_events.php" class="btn-blue">Community Events</a>
        <a href="" class="btn-blue">Join Community</a>
    </div>


    <!-- Vision and Mission -->
    <section class="vision-mission">
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

    <!-- Core Principles -->
    <section class="outlined-card-wrap">
      <h2>Core Principles</h2>
      <div class="principles-grid ">
        <div class="principle-p-wrap">
          <h2>Core Principles</h2>
          <p>I'm a paragraph. Click here to add your own text and edit me.</p>
        </div>
        <div class="principle-p-wrap">
          <h2>Core Principles</h2>
          <p>I'm a paragraph. Click here to add your own text and edit me.</p>
        </div>
        <div class="principle-p-wrap">
          <h2>Core Principles</h2>
          <p>I'm a paragraph. Click here to add your own text and edit me.</p>
        </div>
        <div class="principle-p-wrap">
          <h2>Core Principles</h2>
          <p>I'm a paragraph. Click here to add your own text and edit me.</p>
        </div>
        <div class="principle-p-wrap">
          <h2>Core Principles</h2>
          <p>I'm a paragraph. Click here to add your own text and edit me.</p>
        </div>
        <div class="principle-p-wrap">
          <h2>Core Principles</h2>
          <p>I'm a paragraph. Click here to add your own text and edit me.</p>
        </div>
      </div>
    </section>
  </main>
</body>
<?php
// Include footer
include '../components/footer.php';
?>

</html>