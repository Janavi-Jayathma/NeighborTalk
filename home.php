<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group 77 - Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav>
            <ul class="nav-links">
                <li><a href="home.php">Home</a></li>
                <li><a href="#">Stay Informed</a></li>
                <li><a href="#">Support Us</a></li>
                <li><a href="#">Contact Us</a></li>
                <li><a href="about.php">About Us</a></li>
            </ul>
            <div class="right">
                <?php if (isset($_SESSION['username'])): ?>
                    <a href="profile.php" style="color:black;">
                        <?php echo $_SESSION['username']; ?>
                    </a>
                <?php else: ?>
                    <a href="login.php" class="btn">Login</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <div class="info">
        <h2>Home</h2>
        <p>Welcome to the home page!</p>
    </div>

    <div class="main">
        <?php if (isset($_SESSION['username']) && isset($_SESSION['role'])): ?>
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <p>You are logged in as <b><?php echo htmlspecialchars($_SESSION['role']); ?></b>.</p>

        <?php if ($_SESSION['role'] === 'admin') : ?>
            <p><a href="admin.php">Go to Admin Page</a></p>
        <?php else: ?>
            <p>You are a normal user. Limited access.</p>
        <?php endif; ?>

    <?php else: ?>
        <h2>Welcome, Guest!</h2>
        <p>You are not logged in. Please <a href="login.php">Login</a> to access more features.</p>
    <?php endif; ?>
    </div>

    <footer>
        <div class="footer-content">
            <div class="footer-links">
                <a href="#">Community</a>
                <a href="#">T&C</a>
                <a href="about.php">About Us</a>
                <a href="#">Contact Us</a>
            </div>
            <div class="right-buttons">
                <?php if(isset($_SESSION['username'])): ?>
                    <a href="profile.php" class="btn">Profile</a>
                    <a href="logout.php" class="btn">Logout</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="footer-bottom">
            2025 All Rights Reserved
        </div>
    </footer>

</body>
</html>
