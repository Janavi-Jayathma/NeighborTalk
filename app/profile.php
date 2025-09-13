</html>
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
    <main class="profile-grid">
        <div class="profile-head">
            <div class="user-avatar" style="width: 250px; height: 250px;">
                <img src="../images/avatars/<?php echo htmlspecialchars($_SESSION['username']); ?>.png" alt="User Avatar">
            </div>
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>

            <?php
            if (htmlspecialchars($_SESSION['role']) == 'user') {
                echo `<div class="outlined-card-wrap">
                <h2>Your Communities</h2>
                <div class="outlined-card">
                    <p>Community 1</p>
                    <p>Community 1</p>
                    <p>Community 1</p>
                    <p>Community 1</p>
                </div>
            </div>`;
            }
            ?>
        </div>
        <div class="profile-information">
            <div style="display: flex; flex-direction: row; gap: 1rem; justify-content: space-between; align-items: center;" class="section-header">
                <h2>Profile Information</h2>
                <?php
                if (htmlspecialchars($_SESSION['role']) == 'admin') {
                    echo '<a href="./community-profile.php" class="btn-blue" >Community Edit</a>';
                }
                ?>
            </div>
            <div class="section-header">

                <form action="" method="post" enctype="multipart/form-data">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name">
                    <label for="contact_number">Contact Number</label>
                    <input type="text" id="contact_number" name="contact_number">
                    <label for="email_address">Email Address</label>
                    <input type="email" id="email_address" name="email_address">
                    <label for="address">Address</label>
                    <textarea id="address" name="address"></textarea>
                    <label for="gender">Gender</label>
                    <input type="gender" id="gender" name="gender">
                    <label for="age">Age</label>
                    <input type="age" id="age" name="age">
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