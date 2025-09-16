<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header>
    <nav>
        <!-- Company Logo -->
        <div class="logo">
            <!-- Replace 'your-logo-image.png' with the path to your logo file -->
            <img src="..\images\logo.png" alt="Company Logo">
            ABC Company
        </div>

        <ul class="nav-links">
            <li><a href="home.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'home.php') ? 'active' : ''; ?>">Home</a></li>
            <li><a href="events.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'events.php') ? 'active' : ''; ?>">Events</a></li>
            <li><a href="contact.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'contact.php') ? 'active' : ''; ?>">Contact Us</a></li>
            <li><a href="about.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'about.php') ? 'active' : ''; ?>">About Us</a></li>
            <li><a href="help.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'help.php') ? 'active' : ''; ?>">Help</a></li>

            <?php
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'super_admin') {
                echo '<li><a href="./dashboard.php" class="'.(basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '').'">Dashboard</a></li>';
            }
            ?>
        </ul>

        <div class="right">
            <?php
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if (isset($_SESSION['username'])): ?>
                <a href="create_post_page.php" class="btn-blue">Create a Post</a>
                <!-- If logged in: show profile avatar -->
                <a href="./profile.php" class="user-avatar">
                    <div class="avatar-circle">
                        <?php
                        // Show first letter of username if no profile image
                        echo strtoupper(substr($_SESSION['username'], 0, 1));
                        ?>
                    </div>
                </a>
            <?php else: ?>
                <!-- If not logged in: show login button -->
                <a href="../auth/login.php" class="btn login-btn">Login</a>
            <?php endif; ?>
        </div>
    </nav>
</header>