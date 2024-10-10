<?php
include 'Object.php';
// // ตรวจสอบสิทธิ์แอดมิน (เพิ่มโค้ดตรวจสอบตรงนี้)
// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//     header("Location: home.php");
//     exit();
// }

$feedback_id = $_GET['id'];

// ย้ายฟังก์ชันการอัปเดตสถานะมาก่อนการดึงข้อมูล
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mark_checked'])) {
    $update_stmt = $conn->prepare("UPDATE feedback SET status = 'checked', checked_at = NOW() WHERE id = ?");
    $update_stmt->execute([$feedback_id]);
}

// ดึงข้อมูล feedback หลังจากอัปเดต (ถ้ามี)
$stmt = $conn->prepare("SELECT f.*, u.username, u.image_user FROM feedback f JOIN users u ON f.user_id = u.id WHERE f.id = ?");
$stmt->execute([$feedback_id]);
$feedback = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$feedback) {
    header("Location: admin_Feedback.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NAF Community</title>
    <link rel="icon" href="/images/logonaf.png">
    <link rel="stylesheet" href="css/adminFeedbackMessage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
<h1>Feedback Detail</h1>
    <div class="container" method="POST">
        <div class="feedback-details">
        <a href="admin_Feedback.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back</a>
            <form action="admin_Feedback_Update.php" method="POST">
                <input type="hidden" name="feedback_id" value="<?php echo $feedback_id; ?>">
                <img src="profile_img/<?php echo htmlspecialchars($feedback['image_user']); ?>" alt="Profile" class="profile-img">
                <p><strong>Username :</strong> <?php echo htmlspecialchars($feedback['username']); ?></p>
                <p><strong>Subject :</strong> <?php echo htmlspecialchars($feedback['subject']); ?></p>
                <p><strong>Message :</strong> <?php echo nl2br(htmlspecialchars($feedback['message'])); ?></p>
                <p><strong>Created At :</strong> <?php echo htmlspecialchars($feedback['created_at']); ?></p>
                <p><strong>Status :</strong>
                    <select name="status" id="status">
                        <option value='pending' <?php echo htmlspecialchars($feedback['status']) == 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value='in_progress' <?php echo htmlspecialchars($feedback['status']) == 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                        <option value='resolved' <?php echo htmlspecialchars($feedback['status']) == 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                    </select>
                </p>
                <?php if ($feedback['status'] == 'checked'): ?>
                    <p><strong>ตรวจสอบเมื่อ:</strong> <?php echo htmlspecialchars($feedback['checked_at']); ?></p>
                <?php endif; ?>
                <?php if ($feedback['status'] != 'checked'): ?>
                    <button type="submit" class="check-btn" id="check-btn" name="mark_checked" onclick="updateStatus()">Mark as Checked</button>
                <?php else: ?>
                    <p class="checked-message">Feedback นี้ได้รับการตรวจสอบแล้ว</p>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <script>
        function updateStatus() {
            var feedbackId = <?php echo $feedback_id; ?>;
            var status = <?php echo $feedback['status']; ?>;
            console.log("Feedback ID: " + feedbackId);
            console.log("Feedback Status: " + status);
        }
    </script>

    <!-- update feedback -->
    <script>
        document.getElementById('status').addEventListener('change', function() {
            var status = this.value;
            console.log("เลือกสถานะใหม่: " + status);

            // ส่งค่าไปยังเซิร์ฟเวอร์ผ่าน AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "admin_Feedback_Update.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log("สถานะได้รับการอัปเดตแล้ว");
                    document.querySelector('.feedback-details p:nth-child(5)').innerHTML = '<strong>Status</strong> ' + status;

                }
            };
            xhr.send("feedback_id=<?php echo $feedback_id; ?>&status=" + encodeURIComponent(status));
        });
    </script>

    <script>
        console.log("----------------------------------");
        console.log("Feedback ID: <?php echo $feedback_id; ?>");
        console.log("Feedback Status: <?php echo $feedback['status']; ?>");
        console.log("----------------------------------");
    </script>
</body>

</html>