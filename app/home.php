<?php
session_start();
require_once '../database/db.php';
$page_title = "Home - Group 77";

// Fetch latest 5 posts for Featured Topics
$postsQuery = "SELECT p.id, p.title, p.content, u.username AS author 
               FROM posts p
               JOIN users u ON p.user_id = u.user_id
               ORDER BY p.created_at DESC
               LIMIT 5";
$postsResult = $conn->query($postsQuery);

// Fetch latest 5 events for Recent Updates
$eventsQuery = "SELECT e.id, e.title, e.description, e.type, c.name AS community_name 
                FROM events e
                JOIN communities c ON e.community_id = c.id
                ORDER BY e.created_at DESC
                LIMIT 5";
$eventsResult = $conn->query($eventsQuery);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group 77 - Home</title>
    <link rel="stylesheet" href="./app_styles.css">
    <link rel="stylesheet" href="../components/components_styles.css">
</head>
<?php
$page_title = "Home - Group 77";
include '../components/header.php';
?>

<body>

    <main>
        <div class="hero-section">
            <div class="hero-content">
                <h1>Welcome to our community</h1>
                <p>Learn. Share. Grow with us.</p>
                <div class="search-bar">
                    <input type="text" placeholder="Search a topic, a user, a post, etc">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </div>
            </div>
        </div>
        <div class="main-content">
            <div class="topics-bar">
                <a href="communities.php" class="btn-blue">Communities</a>
                <a href="events.php" class="btn-blue">Events</a>
                <a href="support.php" class="btn-blue">Support</a>
                <a href="learn_and_share.php" class="btn-blue">Learn and Share</a>
            </div>

            <div class="content-section">
                <div class="featured-topics">
                    <h2>Recent Updates</h2>
                    <div class="topic-cards">
                            <?php if ($eventsResult && $eventsResult->num_rows > 0): ?>
                                <?php while ($event = $eventsResult->fetch_assoc()): ?>
                                    <?php
                                        $imagePath = "../event_uploads/{$event['id']}.*";
                                        $files = glob($imagePath);
                                        $eventImage = count($files) ? $files[0] : "../images/avatars/default.jpg";
                                    ?>
                                    <a class="card" href="event_page.php?id=<?= $event['id'] ?>">
                                      
                                            <img src="<?= $eventImage ?>" alt="Event Image">
                                            <div class="update-content">
                                                <h4><?= htmlspecialchars($event['title']) ?></h4>
                                                <p><?= htmlspecialchars($event['description']) ?></p>
                                                <small>Community: <?= htmlspecialchars($event['community_name']) ?></small>
                                            </div>
                                  
                                    </a>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p>No events found.</p>
                            <?php endif; ?>
                      
                 </div>
            </div>

                <div class="recent-updates">
                    <h2>Featured Topics</h2>
                    <div class="update-list">
                  
                                <?php if ($postsResult && $postsResult->num_rows > 0): ?>
                                    <?php while ($post = $postsResult->fetch_assoc()): ?>
                                        <a class="card" href="post_page.php?id=<?= $post['id'] ?>">

                                            <div class="card-content">
                                                <h3><?= htmlspecialchars($post['title']) ?></h3>
                                                <p><?= htmlspecialchars(substr($post['content'], 0, 150)) ?>...</p>
                                                <small>By: <?= htmlspecialchars($post['author']) ?></small>
                                            </div>
                                        </a>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <p>No posts found.</p>
                                <?php endif; ?>
                
                    </div>
                </div>
            </div>
        </div>

    </main>

</body>
<?php
include '../components/footer.php';
?>

</html>