<?php

session_start();
require_once('config.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = intval($_POST['category']);
    $text = trim($_POST['textedit']);
    $post_id = $_POST['post_id'];
    $image_deleted = isset($_POST['image_deleted']) ? $_POST['image_deleted'] : '0';
    
    if (isset($_FILES['imageedit']) && $_FILES['imageedit']['error'] === 0) {
        $image = uniqid() . '_' . $_FILES['imageUpload']['name'];
        $target_dir = "backupimage/";
        $target_file = $target_dir . basename($image);
    

        if (move_uploaded_file($_FILES['imageedit']['tmp_name'], $target_file)) {
            $edit_stmt = $conn->prepare("UPDATE posts SET category_id = :head_p , text_post = :text_p , image_post = :image_p WHERE post_id = :postid_p");
            $edit_stmt->bindParam(':image_p', $image);
        }
    } else if ($image_deleted === '1') {
        $edit_stmt = $conn->prepare("UPDATE posts SET category_id = :head_p , text_post = :text_p, image_post = NULL  WHERE post_id = :postid_p ");
    }  else {
        $edit_stmt = $conn->prepare("UPDATE posts SET category_id = :head_p , text_post = :text_p WHERE post_id = :postid_p ");
    } 

    $edit_stmt->bindParam(':head_p', $category);
    $edit_stmt->bindParam(':text_p', $text);
    $edit_stmt->bindParam(':postid_p', $post_id);
    $edit_stmt->execute();

}



?>