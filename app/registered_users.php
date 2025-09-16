<?php
session_start();
require_once '../database/db.php';

// Check if event_id is passed
if (!isset($_GET['event_id']) || empty($_GET['event_id'])) {
    die("Event ID not provided.");
}

$event_id = intval($_GET['event_id']);

// Optional: Fetch event details to show title
$stmt = $conn->prepare("SELECT title FROM events WHERE id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();
$stmt->close();

// Fetch registrations
$stmt = $conn->prepare("SELECT * FROM event_registrations WHERE event_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$registrations = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Users | <?php echo htmlspecialchars(isset($event['title']) ? $event['title'] : 'Event'); ?></title>
    <link rel="stylesheet" href="./app_styles.css">
    <link rel="stylesheet" href="../components/components_styles.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        tr:nth-child(even){
            background-color: #fafafa;
        }
    </style>
</head>
<body>
    <?php include '../components/header.php'; ?>

    <main class="admin-container">
        <h1>Registered Users for "<?php echo htmlspecialchars(isset($event['title']) ? $event['title'] : 'Event'); ?>"</h1>

        <?php if ($registrations && $registrations->num_rows > 0): ?>
            <table>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Contact Number</th>
                    <th>Email Address</th>
                    <th>Description</th>
                    <th>Registered At</th>
                </tr>
                <?php $i = 1; while($reg = $registrations->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo htmlspecialchars($reg['name']); ?></td>
                        <td><?php echo htmlspecialchars($reg['contact_number']); ?></td>
                        <td><?php echo htmlspecialchars($reg['email_address']); ?></td>
                        <td><?php echo htmlspecialchars($reg['description']); ?></td>
                        <td><?php echo htmlspecialchars($reg['created_at']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No users have registered for this event yet.</p>
        <?php endif; ?>
    </main>

    <?php include '../components/footer.php'; ?>
</body>
</html>
