<?php

session_start();
require_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comde_id = $_POST['comde_id'];

    $query = "DELETE FROM comments WHERE comment_id = :comde_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':comde_id', $comde_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    }

}



?>