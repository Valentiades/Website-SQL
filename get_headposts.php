<?php

session_start();
require_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$list = $conn->prepare("SELECT COUNT(posts.category_id) AS count, categorys.category_post
        FROM categorys
        iNNER JOIN posts ON posts.category_id = categorys.category_id
        GROUP BY categorys.category_post
        ORDER BY count DESC
        LIMIT 10");
$list->execute();
$trend = $list->fetchAll(PDO::FETCH_ASSOC);

foreach($trend as $row) {
        $category = urlencode($row['category_post']);
        echo '<li><a href="search.php?category=' . $category . '">' . "#" . htmlspecialchars($row['category_post']) . 
        '<span class = "count">'. " " . htmlspecialchars($row['count']) . '</span></a></li>';
}
}

?>