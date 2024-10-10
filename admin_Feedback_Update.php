
<?php

include 'config.php';

// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//     header("Location: home.php");
//     exit();
// }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['feedback_id']) ? intval($_POST['feedback_id']) : 0;
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    $stmt = $conn->prepare("SELECT * FROM feedback WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $sql = "UPDATE feedback SET status = :status WHERE feedback.id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    if ($stmt->execute()) {
        header('Location: admin_Feedback.php');
        exit();
    } else {
        header('Location: admin_Viewfeedback.php');
        exit();
    }
} else {
    header('Location: admin_Viewfeedback.php');
    exit();
}
?>


