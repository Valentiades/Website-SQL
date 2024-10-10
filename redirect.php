<?php

session_start();
require_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (isset($data['user_id'])) {
        $user_id = htmlspecialchars($data['user_id']);
        
        $redirect_url = 'profile.php?user_id=' . urlencode($user_id);
        echo json_encode(['redirect' => $redirect_url]);
    }
}


?>