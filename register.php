<?php
session_start();
require "db.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $role = trim($_POST['role']);

    // Validate role
    if (!in_array($role, array('user', 'admin'))) {
        $error = "Invalid role selected";
    }

    // Authentication part
    if ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } else {
        // Check if username exists
        $check = $conn->prepare("SELECT id FROM users WHERE username=?");
        if (!$check) {
            $error = "Database error: " . $conn->error;
        }else{
            $check->bind_param("s", $username);
            $check->execute();
            $check->store_result();

            if ($check->num_rows > 0) {
                $error = "Username already exists";
            } else {
                // Insert into the databaase
                $sql = "INSERT INTO users (name, email, username, password, role) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssss", $name, $email, $username, $password, $role);

                if ($stmt->execute()) {
                    $_SESSION['username'] = $username;
                    $_SESSION['role'] = $role;

                    // Redirect after loging in
                    header("Location: home.php");
                    exit();
                } else {
                    $error = "Error: " . $conn->error;
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group 77 - Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="register-page">
    <div class="register-container">
            <div class="register-left">
                <img src="images/register.png" alt="Security Illustration">
            </div>
            <div class="register-right">
                <div class="form-box">
                    <h2>Hello</h2>
                    <h3>Register to Get Started</h3>
                    <form method="POST">
                    <input type="text" name="name" placeholder="Full Name" required>
                    <input type="email" name="email" placeholder="Email Address" required>
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <input type="password" name="confirm_password" placeholder="Re-enter Password" required>
                    <select name="role" id="role" required>
                        <option value="">--Select Role--</option>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                    <button type="submit">Register</button>
                    </form>
                    <p class="error"><?php echo $error; ?></p>
                    <p class="success"><?php echo $success; ?></p>
                    <p>Already have an account? <a href="login.php">Login here</a></p>
                </div>
            </div>
    </div>
</body>
</html>