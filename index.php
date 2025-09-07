<?php
session_start();

if (isset($_SESSION['user'])) {
    echo "✅ Logged in as: " . $_SESSION['user'];
    header("Location: app/home.php");
} else {
    echo "❌ Not logged in!";
    header("Location: auth/login.php");
}
?>