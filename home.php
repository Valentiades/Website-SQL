<?php

include 'Object.php';

$homepost = "SELECT post_id, categorys.category_post, text_post, image_post, date_post, 
                    users.id, users.username, users.image_user 
                    FROM ((posts
                    INNER JOIN users ON posts.id = users.id)
                    INNER JOIN categorys ON categorys.category_id = posts.category_id)
                    ORDER BY posts.date_post DESC";

$query = $conn->prepare($homepost);
$query->execute();
$fetch = $query->fetchAll(PDO::FETCH_ASSOC);
$rowCount = count($fetch);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/home.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="icon" href="/images/logonaf.png">
    <title>NAF Community</title>
</head>

<body>


    <?php if (!isset($_SESSION['email'])) { ?>
        <div class="section">
            <div class="section_data">
                <div class="text_section">
                    <p class="title">ยินดีต้อนรับ</p>
                    <p class="subtitle">เข้าสู่ระบบเพื่อเริ่มพูดคุยใน Community ใหม่ๆ
                        <br>กับเพื่อนอีกมากมายที่รอคุณอยู่
                    </p>
                    <div class="button">
                        <a href="login.php"><button class="button_signin">Sign In</button></a>
                        <a href="register.php"><button class="button_signup">Sign Up</button></a>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>


    <div class="showfeed">
        <?php if (isset($_SESSION['email'])) { ?>
            <div class="welcome">
                <h1>NAF</h1>
                <p>WELCOME TO THE COMMUNITY</p>
            </div>
        <?php } ?>
    </div>


    <div class="grid-container" id="realtime">

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/home.js"></script>
</body>
<script>
    window.onload = function() {
        loadMorePosts();
    };
</script>

</html>