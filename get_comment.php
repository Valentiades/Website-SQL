<?php

session_start();
require_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $userid = $data['user_id']; //เจ้าของโพสต์
    $postid = $data['postid'];  //ไอดีโพสต์

    $comment = "SELECT 
                COUNT(comment_id) OVER() AS comment_count,
                comment_text, date_comment, comments.comment_id, users.username, users.image_user, users.id
                FROM comments 
                INNER JOIN users ON comments.id = users.id 
                WHERE post_id = :ID_post
                ORDER BY date_comment DESC";

    $query = $conn->prepare($comment);
    $query->bindParam(':ID_post', $postid);
    $query->execute();
    $coms = $query->fetchAll(PDO::FETCH_ASSOC);

    $com_count = isset($coms[0]) ? $coms[0]['comment_count'] : 0;
    $response = [];
    foreach($coms as $row) {
        $response[] = [
            'count' => $com_count,
            'text' => $row['comment_text'],
            'date' => date('d/m/Y  H:i', strtotime($row['date_comment'])) . 'น.',
            'image' => 'profile_img/' . $row['image_user'],
            'names' => $row['username'],
            'iduser' => $row['id'],
            'idcom' => $row['comment_id'],
            'check' => $userid == $row['id'] ? true : false,
            'delete' => $_SESSION['current_user_id'] == $row['id'] || $_SESSION['current_user_id'] == $userid ? true : false,
            'admin' => $_SESSION['admin']
        ];
    }
    echo json_encode($response);

}
?>