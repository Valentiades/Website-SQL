<?php

    ob_start();
    include 'Object.php';

    if (!isset($_SESSION['email'])) {
        header("Location: home.php");
        ob_end_flush();
        exit;
    }
    ob_end_flush();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['current_user_id'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO feedback (user_id, subject, message) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $subject, $message]);

    if ($stmt->rowCount() > 0) {
        $success_message = "ส่งคำร้องเรียนสำเร็จ";
    } else {
        $error_message = "เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NAF Community</title>
    <link rel="stylesheet" href="css/Feedback.css">
    <link rel="icon" href="/images/logonaf.png">
</head>
<body>
    <div class="container">
        <h1>Send Feedback</h1>
        <div class="form-box">
        <?php if (isset($success_message)): ?>
            <p class="success"><?php echo $success_message; ?></p>
        <?php endif; ?>
        <?php if (isset($error_message)): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="form-group">
                <label for="subject">Head Feedback</label>
                <input type="text" id="subject" name="subject" required>
            </div>
            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" required></textarea>
            </div>
            <button class="submit-feedback" type="submit">Send Feedback</button>
        </form>
        </div>
    </div>
</body>
</html>
