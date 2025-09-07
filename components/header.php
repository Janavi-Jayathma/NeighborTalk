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
            <li><a href="stay-informed.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'stay-informed.php') ? 'active' : ''; ?>">Stay Informed</a></li>
            <li><a href="support.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'support.php') ? 'active' : ''; ?>">Support Us</a></li>
            <li><a href="contact.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'contact.php') ? 'active' : ''; ?>">Contact Us</a></li>
            <li><a href="about.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'about.php') ? 'active' : ''; ?>">About Us</a></li>
        </ul>

        <div class="right">
            <!-- Always show Create Post button -->
            <a href="create-post.php" class="btn create-btn">Create a Post</a>

            <?php if (isset($_SESSION['username'])): ?>
                <!-- If logged in: show profile avatar -->
                <a href="profile.php" class="user-avatar">
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