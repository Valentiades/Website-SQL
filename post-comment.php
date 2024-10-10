<?php

        header('Content-Type: application/json');
        
        session_start();
        require_once('config.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $comment = trim($_POST['comment_text']);
        $user_com = $_SESSION['current_user_id'];
        $post_com = $_POST['post_id_com'];
        
        $comment_stmt = $conn->prepare("INSERT INTO comments (comment_text, post_id, id) VALUES (:comment_c, :post_c, :id_c)");
        $comment_stmt->bindParam(':comment_c', $comment);
        $comment_stmt->bindParam(':post_c', $post_com);
        $comment_stmt->bindParam(':id_c', $user_com);
        $comment_stmt->execute();

        if ($comment_stmt) {
            $response = array(
                'status' => 'success',
                'post' => array()
            );
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

?>