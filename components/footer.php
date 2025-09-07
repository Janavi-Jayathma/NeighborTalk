<?php
// You can set a page title dynamically from any page using:
// $page_title = "Home | ABC Company";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'ABC Company'; ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    
    <main class="main-content">
    
    </main>

    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <ul>
                    <li><a href="#">Community T &amp; Cs</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="#">Contact Us</a></li>
                </ul>
            </div>
            <div class="footer-section footer-center">
                <div class="logo">
                    <img src="../images/logo.png" alt="Company Logo">
                    <span>ABC Company</span>
                </div>
            </div>
            <div class="footer-section footer-right">
                <?php if (isset($_SESSION['username'])): ?>
                    <a href="logout.php" class="btn logout-btn">Log Out</a>
                    <a href="profile.php" class="btn profile-btn">Profile</a>
                <?php else: ?>
                    <a href="../auth/login.php" class="btn login-btn">Login</a>
                    <a href="../auth/register.php" class="btn register-btn">Register</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="footer-bottom">
            All rights reserved | 2025
        </div>
    </footer>
</body>
</html>
