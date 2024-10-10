<?php
include 'config.php';

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $postId = $_GET['post_id'];
    
    $stmt = $conn->prepare("DELETE FROM posts WHERE post_id = ?");
    $stmt->execute([$postId]);
    
    if ($stmt->rowCount() > 0) {
        $response['success'] = true;
    } 

    header('Content-Type: application/json');
    echo json_encode($response);
}

?>