<?php 

session_start();
require_once('config.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = intval($_POST['category']);
    $text = trim($_POST['text']);
    $useridpost = $_SESSION['current_user_id'];
    $image = null;
    $page = $_POST['page'];
    if (isset($_FILES['imageUpload']) && $_FILES['imageUpload']['error'] === 0) {
        $target_dir = "backupimage/";
        $image = uniqid() . '_' . $_FILES['imageUpload']['name'];
        $target_file = $target_dir . basename($image);
    

        if (move_uploaded_file($_FILES['imageUpload']['tmp_name'], $target_file)) {
            $insert_stmt = $conn->prepare("INSERT INTO posts (category_id, text_post, image_post, id) VALUES (:head_p, :text_p, :image_p, :userid_p)");
            $insert_stmt->bindParam(':image_p', $image);
        } 
    }else {
        $insert_stmt = $conn->prepare("INSERT INTO posts (category_id, text_post, id) VALUES (:head_p, :text_p, :userid_p)");
    } 

    $insert_stmt->bindParam(':head_p', $category);
    $insert_stmt->bindParam(':text_p', $text);
    $insert_stmt->bindParam(':userid_p', $useridpost);
    $postSuccess = $insert_stmt->execute();

    if ($postSuccess) {
            $response = array(
                'status' => 'success',
                'post' => array()
            );
    }
    header('Content-Type: application/json');
    echo json_encode($response);

} 


?>