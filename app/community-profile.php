</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group 77 - Community Profile</title>
    <link rel="stylesheet" href="./app_styles.css">
    <link rel="stylesheet" href="../components/components_styles.css">
</head>
<?php
$page_title = "Home - Group 77";
include '../components/header.php';
?>

<body>
    <main>

        <h2>Community Profile</h2>
        <div class="profile-grid">
            <div class="profile-head">
                <div class="user-avatar" style="width: 250px; height: 250px;">
                    <img src="../images/avatars/<?php echo htmlspecialchars($_SESSION['username']); ?>.png" alt="User Avatar">
                </div>

            </div>
            <div class="profile-information">
                <a href="./community-profile.php" class="btn-blue" >Community Edit</a>
                <form action="" method="post" >
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name">
                    <label for="contact_number">Contact Number</label>
                    <input type="text" id="contact_number" name="contact_number">
                    <label for="email_address">Email Address</label>
                    <input type="email" id="email_address" name="email_address">
                    <label for="address">Address</label>
                    <textarea id="address" name="address"></textarea>
                    <label for="moderator">Moderator</label>
                    <input type="moderator" id="moderator" name="moderator">
                    <label for="category">Category</label>
                    <input type="category" id="category" name="category">
                    <button type="submit" class="btn-blue">Save Changes</button>
                </form>
            </div>
        </div>
    </main>
</body>
<?php
include '../components/footer.php';
?>

</html>