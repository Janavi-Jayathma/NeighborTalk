<?php
session_start();
require_once '../database/db.php';

// Check if event ID is passed
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Event ID not provided.");
}

$event_id = intval($_GET['id']);

// You can set a page title dynamically from any page using:
$page_title = "Event page | ABC Community";

// Fetch event details along with community admin info
$stmt = $conn->prepare("SELECT e.*, c.name AS community_name, c.admin_id, u.username AS admin_username
                        FROM events e
                        JOIN communities c ON e.community_id = c.id
                        JOIN users u ON c.admin_id = u.user_id
                        WHERE e.id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();
$stmt->close();

if (!$event) {
    die("Event not found.");
}

// Determine event image
$files = glob("../event_uploads/{$event['id']}.*");
$imagePath = count($files) ? $files[0] : "../images/avatars/default.jpg";

// Determine admin avatar
$avatarFiles = glob("../images/avatars/{$event['admin_username']}.*");
$adminAvatar = count($avatarFiles) ? $avatarFiles[0] : "../images/avatars/default.jpg";

// Handle event registration form submission
$registrationMessage = "";
if (isset($_POST['register_submit'])) {
    $name = trim($_POST['name']);
    $contact_number = trim($_POST['contact_number']);
    $email_address = trim($_POST['email_address']);
    $description = trim($_POST['description']);

    // Basic validation
    if (empty($name) || empty($contact_number) || empty($email_address)) {
        $registrationMessage = "Please fill in all required fields.";
    } else {
        $stmt = $conn->prepare("INSERT INTO event_registrations (event_id, name, contact_number, email_address, description, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("issss", $event_id, $name, $contact_number, $email_address, $description);

        if ($stmt->execute()) {
            $registrationMessage = "Registration successful!";
        } else {
            $registrationMessage = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Handle comment deletion
if (isset($_POST['delete_comment']) && isset($_POST['delete_comment_id'])) {
    $delete_id = intval($_POST['delete_comment_id']);

    // Fetch comment and event info to check permissions
    $stmt = $conn->prepare("
        SELECT ec.user_id AS comment_user_id, c.admin_id AS community_admin_id
        FROM event_comments ec
        JOIN events e ON ec.event_id = e.id
        JOIN communities c ON e.community_id = c.id
        WHERE ec.id = ?
    ");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if ($row) {
        $canDelete = false;

        // super_admin can delete any comment
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'super_admin') {
            $canDelete = true;
        }

        // comment owner
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['comment_user_id']) {
            $canDelete = true;
        }

        // event owner (community admin)
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['community_admin_id']) {
            $canDelete = true;
        }

        if ($canDelete) {
            $stmt = $conn->prepare("DELETE FROM event_comments WHERE id = ?");
            $stmt->bind_param("i", $delete_id);
            $stmt->execute();
            $stmt->close();
            $commentMessage = "Comment deleted successfully!";
        } else {
            $commentMessage = "You do not have permission to delete this comment.";
        }
    }
}


$commentMessage = "";
if (isset($_POST['comment_submit'])) {
    $comment = trim($_POST['comment']);
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    if (!empty($comment)) {
        $stmt = $conn->prepare("INSERT INTO event_comments (event_id, user_id, username, comment) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $event_id, $user_id, $username, $comment);

        if ($stmt->execute()) {
            $commentMessage = "Comment posted!";
        } else {
            $commentMessage = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $commentMessage = "Please write a comment before submitting.";
    }
}

$commentsResult = $conn->query("SELECT * FROM event_comments WHERE event_id = $event_id ORDER BY created_at DESC");


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
            <h1>Events</h1>
            <p>I'm a paragraph. Click here to add your own text and edit me. I'm a great place for you to tell a story and let your users know a little more about you.</p>
        </div>

        <!--  Post Section -->
        <section class="post-card">
            <div class="post-container">
            <div class="post-header">
                <div class="community-info">
                    <img src="<?php echo $adminAvatar; ?>" alt="Community Logo" class="avatar">
                    <div>
                        <h3><?php echo htmlspecialchars($event['community_name']); ?></h3>
                        <p><?php echo htmlspecialchars($event['created_at']); ?></p>
                    </div>
                </div>
                <span class="tag"><?php echo htmlspecialchars($event['type']); ?></span>
            </div>

            <div class="post-content">
                <h2><?php echo htmlspecialchars($event['title']); ?></h2>
                <img src="<?php echo $imagePath; ?>" alt="Event Image" class="post-image">
                <p>
                    <?php echo nl2br(htmlspecialchars($event['description'])); ?>
                </p>
                <p><?php echo nl2br(htmlspecialchars($event['content'])); ?></p>
            </div>
</div>
        </section>
        <div class="event-bottom-wrap">
            <div class="donations-container center-card-wrap" >
            <div class="donations-form-section">
                <h2>Register to the event</h2>
                <div class="donation-info">
                    <p>Fill out the form below and register to the event.</p>
                </div>
                <form action="event_page.php?id=<?php echo $event_id; ?>" method="post">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name">
                    <label for="contact_number">Contact Number</label>
                    <input type="text" id="contact_number" name="contact_number">
                    <label for="email_address">Email Address</label>
                    <input type="email" id="email_address" name="email_address">
                    <label for="description">Description</label>
                    <textarea id="description" name="description"></textarea>
                    <button type="submit" class="btn-blue" name="register_submit">Register</button>
                    <div class="post-actions" style="margin-top: 1rem;">
                    <?php
                    // Show "View Registered Users" button if logged-in admin owns this event
                    if (isset($_SESSION['role'], $_SESSION['user_id']) 
                        && $_SESSION['role'] === 'admin' 
                        && $_SESSION['user_id'] == $event['admin_id']) {
                        echo '<a href="registered_users.php?event_id=' . $event_id . '" class="btn-blue">View Registered Users</a>';
                    }
                    ?>
</div>

                </form>
            </div>
             </div>
        <!-- Separate Comments Section -->
            <section class="comments-section">
                <h3>Comments</h3>
                <?php if (!empty($commentMessage)) : ?>
                    <p class="comment-message"><?php echo htmlspecialchars($commentMessage); ?></p>
                <?php endif; ?>

                <form action="event_page.php?id=<?php echo $event_id; ?>" method="post">
                    <textarea name="comment" placeholder="Write your comment..."></textarea>
                    <button type="submit" name="comment_submit" class="btn-blue">Publish</button>
                </form>

                <div class="comments-list">
                <?php
                if ($commentsResult && $commentsResult->num_rows > 0) {
                    while ($comment = $commentsResult->fetch_assoc()) {
                        echo '<div class="comment">';
                        echo '<strong>' . htmlspecialchars($comment['username']) . '</strong>';
                        echo '<h6> - ' . htmlspecialchars($comment['created_at']) . '</h6>';
                        echo '<p>' . nl2br(htmlspecialchars($comment['comment'])) . '</p>';

                        // Show delete button only for super_admin or the owner of the comment
                        if ((isset($_SESSION['role']) && $_SESSION['role'] === 'super_admin') ||
                            (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $comment['user_id'])) {
                            echo '<form method="post" style="display:inline-block; margin-left:10px;">
                                    <input type="hidden" name="delete_comment_id" value="' . $comment['id'] . '">
                                    <button type="submit" name="delete_comment" class="btn-red" onclick="return confirm(\'Are you sure you want to delete this comment?\')">Delete</button>
                                </form>';
        }

                        echo '</div>';
                    }

                } else {
                    echo "<p>No comments yet.</p>";
                }
                ?>
                </div>
            </section>


        
        </div>
    </main>

    <!-- Footer -->

</body>

</html>

<?php
// Include footer
include '../components/footer.php';
?>

