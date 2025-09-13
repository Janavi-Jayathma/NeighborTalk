<?php
include '../database/db.php';

$page_title = "Home - Group 77";
include '../components/header.php';

// Get logged in username
$username = $_SESSION['username'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['username'] ?? null;

    if ($username) {
        $name = $_POST['name'] ?? '';
        $contact_number = $_POST['contact_number'] ?? '';
        $email_address = $_POST['email_address'] ?? '';
        $address = $_POST['address'] ?? '';
        $gender = $_POST['gender'] ?? '';
        $age = (int)($_POST['age'] ?? 0); // convert to integer

        $sql = "UPDATE users 
                SET name = ?, contact_number = ?, email_address = ?, address = ?, gender = ?, age = ?
                WHERE username = ?";

        $stmt = $conn->prepare($sql);
        if (!$stmt) die("Prepare failed: " . $conn->error);

        $stmt->bind_param("sssssis", $name, $contact_number, $email_address, $address, $gender, $age, $username);

        if ($stmt->execute()) {
            $success_message = "Profile updated successfully!";
        } else {
            $error_message = "Error updating profile: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Fetch user data from database
$sql = "SELECT name, contact_number, email_address, address, gender, age 
        FROM users 
        WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$user = $result->fetch_assoc(); // fetch user data as associative array

//user-profile image
$avatar_path = "../images/avatars/" . $_SESSION['username'] . ".jpg";
if (!file_exists($avatar_path)) {
    $avatar_path = "../images/avatars/default.jpg"; // default avatar
}
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

<body>
    <main class="profile-grid">
        <div class="profile-head">
            <div class="user-avatar" style="width: 250px; height: 250px;">
                <img src="<?php echo $avatar_path; ?>" alt="User Avatar">

            </div>
            <input type="file" id="avatar" name="avatar" accept="image/*">
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
            <?php
            if (htmlspecialchars($_SESSION['role']) == 'admin') {
                echo '<a href="./community-profile.php" class="btn-blue" >Visit Your Community</a>';
            }
            ?>

        </div>
        <div class="profile-information">
            <div style="display: flex; flex-direction: row; gap: 1rem; justify-content: space-between; align-items: center;" class="section-header">
                <h2>Profile Information</h2>
                <?php
                if (htmlspecialchars($_SESSION['role']) == 'admin') {
                    echo '<a href="./community-profile-edit.php" class="btn-blue" >Community Edit</a>';
                }
                ?>
            </div>
            <div class="section-header">

                <form action="" method="post" enctype="multipart/form-data">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>">
                    <label for="contact_number">Contact Number</label>
                    <input type="text" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($user['contact_number']); ?>">
                    <label for="email_address">Email Address</label>
                    <input type="email" id="email_address" name="email_address" value="<?php echo htmlspecialchars($user['email_address']); ?>">
                    <label for="address">Address</label>
                    <textarea id="address" name="address"><?php echo htmlspecialchars($user['address']); ?></textarea>
                    <label for="gender">Gender</label>
                    <input type="text" id="gender" name="gender" value="<?php echo htmlspecialchars($user['gender']); ?>">
                    <label for="age">Age</label>
                    <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($user['age']); ?>">
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