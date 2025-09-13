<?php
include '../database/db.php';
session_start();

$username = $_SESSION['username'];
$community = null;
$community_id = null;
$principles = [];
$msg = '';

// Fetch existing community
$stmt = $conn->prepare("SELECT id, name, vision, mission FROM communities WHERE admin_username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$community = $stmt->get_result()->fetch_assoc();
$stmt->close();
$community_id = $community['id'] ?? null;

// Fetch existing principles
if ($community_id) {
    $stmt = $conn->prepare("SELECT id, principle, description FROM community_principles WHERE community_id = ?");
    $stmt->bind_param("i", $community_id);
    $stmt->execute();
    $principles = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

// Initialize principle count for form display
$principle_count = max(count($principles), 1);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Add blank principle
    if (isset($_POST['add'])) {
        $_SESSION['principle_count'] = $principle_count + 1;
        $principle_count = $_SESSION['principle_count'];
    }

    // Remove last principle
    if (isset($_POST['remove']) && count($principles) > 0) {
        $last_id = $principles[count($principles) - 1]['id'];
        $stmt = $conn->prepare("DELETE FROM community_principles WHERE id=?");
        $stmt->bind_param("i", $last_id);
        $stmt->execute();
        $stmt->close();
        array_pop($principles);
    }
    $principle_count--;
    $_SESSION['principle_count'] = $principle_count;

    // Save community and principles
    if (isset($_POST['submit'])) {
        $name = trim($_POST['name'] ?? '');
        $vision = trim($_POST['vision'] ?? '');
        $mission = trim($_POST['mission'] ?? '');

        if ($name) {
            // Update or insert community
            if ($community_id) {
                $stmt = $conn->prepare("UPDATE communities SET name=?, vision=?, mission=? WHERE id=?");
                $stmt->bind_param("sssi", $name, $vision, $mission, $community_id);
                $stmt->execute();
                $stmt->close();
                $msg = "Community updated successfully!";
            } else {
                $stmt = $conn->prepare("INSERT INTO communities (admin_username, name, vision, mission) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $username, $name, $vision, $mission);
                $stmt->execute();
                $community_id = $stmt->insert_id;
                $stmt->close();
                $msg = "Community created successfully!";
            }

            // Update or insert principles
            if (!empty($_POST['principle'])) {
                foreach ($_POST['principle'] as $i => $principle) {
                    $description = $_POST['description'][$i] ?? '';
                    $pid = $_POST['principle_id'][$i] ?? null;

                    if ($pid) {
                        // Update existing principle
                        $stmt = $conn->prepare("UPDATE community_principles SET principle=?, description=? WHERE id=?");
                        $stmt->bind_param("ssi", $principle, $description, $pid);
                        $stmt->execute();
                        $stmt->close();
                    } else {
                        // Insert new principle
                        $stmt = $conn->prepare("INSERT INTO community_principles (community_id, principle, description) VALUES (?, ?, ?)");
                        $stmt->bind_param("iss", $community_id, $principle, $description);
                        $stmt->execute();
                        $stmt->close();
                    }
                }
            }


            // Reload principles after save
            $stmt = $conn->prepare("SELECT id, principle, description FROM community_principles WHERE community_id=? ORDER BY id ASC");
            $stmt->bind_param("i", $community_id);
            $stmt->execute();
            $principles = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();

            $_SESSION['principle_count'] = count($principles);

            // Redirect to avoid form resubmission
            header("Location: community-profile-edit.php");
            exit;
        
        }
    }
}

?>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group 77 - Community Profile</title>
    <link rel="stylesheet" href="./app_styles.css">
    <link rel="stylesheet" href="../components/components_styles.css">
</head>
<?php
$page_title = "Home - Group 77";
include '../components/header.php';
?>

<body>
    <main class="center-card-wrap">
        <div class="topic-unwrap">
            <h1>Edit Your Community</h1>
            <p>Make changes to your community profile information below.</p>
        </div>
        <div class="profile-information">

            <form action="" method="post">
                <label for="name">Community Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($community['name'] ?? ''); ?>">
                <label for="vision">Community Vision</label>
                <textarea id="vision" name="vision"><?php echo htmlspecialchars($community['vision'] ?? ''); ?></textarea>
                <label for="mission">Community Mission</label>
                <textarea type="text" id="mission" name="mission"><?php echo htmlspecialchars($community['mission'] ?? ''); ?></textarea>
                <label>Core Principles:</label>
                <?php for ($i = 0; $i < $_SESSION['principle_count']; $i++): 
                    $p_name = $principles[$i]['principle'] ?? '';
                    $p_desc = $principles[$i]['description'] ?? '';
                    $p_id   = $principles[$i]['id'] ?? '';
                ?>
                    <input type="hidden" name="principle_id[]" value="<?php echo htmlspecialchars($p_id); ?>">
                    <input type="text" name="principle[]" value="<?php echo htmlspecialchars($p_name); ?>" placeholder="Principle Topic">
                    <textarea name="description[]" placeholder="Enter description"><?php echo htmlspecialchars($p_desc); ?></textarea>
                <?php endfor; ?>
                <div>
                    <button class="btn-blue" type="submit" name="add">Add Principle</button>
                    <button class="btn-red" type="submit" name="remove">Remove Last</button>
                </div>
                <div style="margin-top: 1rem;">
                    <a href="./community-profile.php" class="btn-blue"> Visit Community</a>
                    <button type="submit" name="submit" class="btn-red">Save Changes</button>
                </div>
            </form>

        </div>
    </main>
</body>
<?php
include '../components/footer.php';
?>

</html>