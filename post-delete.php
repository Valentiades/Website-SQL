<?php 
        session_start();

        require_once('config.php');

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $data = json_decode(file_get_contents('php://input'), true);
            $post_id = $data['postId'];

            if (!empty($post_id)) {
                $delete = "DELETE FROM posts WHERE post_id = :ID_post";
                $query = $conn->prepare($delete);
                $query->bindParam(':ID_post', $post_id, PDO::PARAM_INT);
                $result = $query->execute();

                if ($result) {
                    echo json_encode(['success' => true]);
                }

            }
        }
?>