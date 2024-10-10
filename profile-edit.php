<?php

session_start();
require_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = trim($_POST['firstname']);
    $user_id = $_SESSION['current_user_id'];

    if (isset($_FILES['img_user']) && $_FILES['img_user']['error'] === 0) {
        $image = uniqid() . '_' . $_FILES['img_user']['name'];
        $target_dir = "profile_img/";
        $target_file = $target_dir . basename($image);

        if (move_uploaded_file($_FILES['img_user']['tmp_name'], $target_file)) {
            if (!empty($user_name)) {
                $edit_pf = $conn->prepare("UPDATE users SET username = :name_p , image_user = :image_p WHERE id = :id_p");
                $edit_pf->bindParam(':name_p', $user_name);
            }else {
                $edit_pf = $conn->prepare("UPDATE users SET image_user = :image_p WHERE id = :id_p");
            }
            $edit_pf->bindParam(':image_p', $image);
            $edit_pf->bindParam(':id_p', $user_id);
            $edit_pf->execute();
        }
    } else {
        $edit_pf = $conn->prepare("UPDATE users SET username = :name_p WHERE id = :id_p");
    }

    $edit_pf->bindParam(':name_p', $user_name);
    $edit_pf->bindParam(':id_p', $user_id);
    $edit_pf->execute();

}


?>