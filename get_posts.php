<?php
header('Content-Type: application/json');
session_start();
require_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("SELECT post_id, categorys.category_post, text_post, image_post, date_post, 
                            users.username, users.image_user, users.id
                            FROM ((posts
                            INNER JOIN users ON posts.id = users.id)
                            INNER JOIN categorys ON categorys.category_id = posts.category_id)
                            ORDER BY date_post DESC");
    $stmt->execute();
    $post = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $response = [];
    foreach($post as $row) {
        $response[] = [
            'category' => $row['category_post'],
            'text' => $row['text_post'],
            'date' => date('d/m/Y  H:i', strtotime($row['date_post'])) . 'น.',
            'image' => $row['image_post'] ? 'backupimage/' . $row['image_post'] : null,
            'username' => $row['username'],
            'user_image' => 'profile_img/' . $row['image_user'],
            'post_id' => $row['post_id'],
            'user_id' => $row['id']
        ];
    }

    echo json_encode($response);
}
?>