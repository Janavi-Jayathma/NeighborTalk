<?php
// --- Include Styles & Header ---
require_once '../components_styles.css';
include '../components/header.php';

// Page title
$page_title = "Support | ABC Company";

// Database connection
$host = "localhost";
$username = "root";
$password = ""; // your DB password
$dbname = "group_77_db"; // change to your DB name

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $amount = $_POST['amount'];

    // File upload
    $attachmentPath = null;
    if (!empty($_FILES['attachment']['name']) && $_FILES['attachment']['error'] === 0) {
        $uploadDir = "../uploads/";
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $attachmentPath = $uploadDir . basename($_FILES["attachment"]["name"]);
        move_uploaded_file($_FILES["attachment"]["tmp_name"], $attachmentPath);
    }

    // Insert into DB
    $stmt = $conn->prepare("INSERT INTO donations (name, description, amount, attachment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $name, $description, $amount, $attachmentPath);

    if ($stmt->execute()) {
        echo "<script>alert('Donation submitted successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'ABC Company'; ?></title>
    <link rel="stylesheet" href="app_styles.css">
</head>
<body>
    <main class="main-content">
        <div class="support-header">
            <h1>Support</h1>
            <p>I'm a paragraph. Click here to add your own text and edit me. I'm a great place for you to tell a story and let your users know a little more about you.</p>
        </div>

        <div class="donations-container">
            <div class="donations-form-section">
                <h2>Donations</h2>
                <div class="donation-info">
                    <p>This is your campaign description. It's a great place to tell visitors what this campaign is about, connect with your donors and draw attention to your cause.</p>
                    <p>Account Number: 123456789<br>Branch: Colombo Branch<br>Bank: ABC Bank</p>
                </div>
                <form action="support.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <input type="text" id="description" name="description">
                    </div>
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" id="amount" name="amount">
                    </div>
                    <div class="form-group file-upload">
                        <label for="attachment">Attachment</label>
                        <div class="file-input-wrapper">
                            <input type="file" id="attachment" name="attachment" accept="image/*">
                        </div>
                    </div>
                    <div class="form-buttons">
                        <button type="submit" class="btn donate-btn">Donate</button>
                        <button type="reset" class="btn clear-btn">Clear</button>
                    </div>
                </form>
            </div>
            <div class="donations-image-section">
                <img src="../images/support.png" alt="Charity and Donation Illustration">
            </div>
        </div>
    </main>
</body>
</html>

<?php
// Include footer
include '../components/footer.php';
?>
