<?php
    session_start();
    require 'config.php';

    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $postid = isset($_POST['post_id']) ? htmlspecialchars($_POST['post_id']) : null;
        $category_id = isset($_POST['category_id']) ? htmlspecialchars($_POST['category_id']) : null;
        $textedit = isset($_POST['textedit']) ? htmlspecialchars($_POST['textedit']) : null;
        $oldImage = isset($_POST['old_image']) ? htmlspecialchars($_POST['old_image']) : null;
        $image = isset($_POST['image_new_upload']) ? htmlspecialchars($_POST['image_new_upload']) : null;
        
        if (isset($_FILES['image_new_upload']) && $_FILES['image_new_upload']['error'] == 0) {
            if ($oldImage && file_exists("backupimage/" . $oldImage) && !is_dir("backupimage/" . $oldImage)) {
                unlink("backupimage/" . $oldImage);
            }
            $image = uniqid() . '_' . $_FILES['image_new_upload']['name'];
            $target_dir = "backupimage/";   
            $target_file = $target_dir . basename($image);
            move_uploaded_file($_FILES['image_new_upload']['tmp_name'], $target_file);
        } else {
            $image = $oldImage;
        }
        if ($postid && $category_id && $textedit && $image) {
            $sql = "UPDATE posts SET category_id = :category_id, text_post = :textedit, image_post = :image WHERE post_id = :postid";
            $query = $conn->prepare($sql);
            $query->bindParam(':category_id', $category_id);
            $query->bindParam(':textedit', $textedit);
            $query->bindParam(':image', $image);
            $query->bindParam(':postid', $postid);
            $query->execute();
            if ($query->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'อัพเดตข้อมูลสำเร็จ']);
            } 
        } 
    }
?>