<?php

ob_start();
include 'Object.php';

// // ตรวจสอบสิทธิ์แอดมิน (ให้เพิ่มโค้ดตรวจสอบตรงนี้)
// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//     header("Location: home.php");
//     exit();
// }

$stmt = $conn->query("SELECT feedback.id,feedback.user_id,feedback.subject,feedback.message,feedback.status,feedback.created_at,users.username,users.image_user FROM feedback feedback JOIN users users ON feedback.user_id = users.id ORDER BY feedback.created_at DESC");
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NAF Community</title>
    <link rel="stylesheet" href="css/adminFeedback.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="db-container" >
        <a href="admin_Dashboard.php" class="back">
            <button>
                <i class="fas fa-arrow-left"></i> Back
            </button>
        </a>
        <h1>Manage Feedback</h1>
        <table class="feedback-table">
            <thead>
                <tr>
                    <th>Profile</th>
                    <th>Username</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Tools</th>
                </tr>
            </thead>
            <tbody></tbody>
                <?php foreach ($feedbacks as $feedback): ?>
                    <tr>
                        <td><img src="profile_img/<?php echo htmlspecialchars($feedback['image_user']); ?>" alt="Profile" class="profile-img"></td>
                        <td><?php echo htmlspecialchars($feedback['username']); ?></td>
                        <td><?php echo htmlspecialchars($feedback['subject']); ?></td>
                        <td class="feedback-status"><?php echo htmlspecialchars($feedback['status']); ?></td>
                        <td><?php echo htmlspecialchars($feedback['created_at']); ?></td>
                        <td>
                            <a href="admin_Viewfeedback.php?id=<?php echo $feedback['id']; ?>" class="btn view-btn">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
