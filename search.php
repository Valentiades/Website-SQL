<?php
ob_start();
include 'Object.php';

function Keywords($search)
{
    $search = mb_strtolower($search, 'UTF-8');;

    $keywords = preg_split('/\s+/ ', $search);
    $keywords = array_unique($keywords);

    $stopWords = [
        'และ',
        'คือ',
        'ที่',
        'เป็น',
        'กับ',
        'โดย',
        'จาก',
        'ถึง',
        'มี',
        'หรือ',
        'ใน',
        'ให้',
        'จะ',
        'ของ',
        'ได้',
        'ไม่มี',
        'ทำ',
        'กับ',
        'แต่',
        'ซึ่ง',
        'สำหรับ',
        'ไม่',
        'ไป',
        'มา',
        'ให้',
        'ต้อง',
        'ดังนั้น',
        'มี',
        'ใช่',
        'บ้าง'
    ];

    $keywords = array_diff($keywords, $stopWords);

    sort($keywords);

    return $keywords;
}

$rowCount = "";


if (isset($_GET['search'])) {
    $search = trim($_GET['search']);

    $keywords = Keywords($search);
    if (!empty($keywords)) {
        $sql = "SELECT post_id, categorys.category_post, text_post, image_post, date_post, 
                    users.id, users.username, users.image_user 
                    FROM ((posts
                    INNER JOIN users ON posts.id = users.id)
                    INNER JOIN categorys ON categorys.category_id = posts.category_id)
                    WHERE ";
        $conditions = [];
        $params = [];

        foreach ($keywords as $keyword) {
            $conditions[] = "(posts.text_post LIKE :keyword" . count($params) . " OR categorys.category_post LIKE :keyword" . count($params) . ")";
            $params[':keyword' . count($params)] = '%' . $keyword . '%';
        }

        $sql .= implode(' OR ', $conditions);
        $sql .= " ORDER BY posts.date_post DESC";
        $stmt = $conn->prepare($sql);

        foreach ($params as $param => $value) {
            $stmt->bindValue($param, $value);
        }
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $rowCount = count($results);
    }
} else if (isset($_GET['category'])) {
    $search = $_GET['category'];
    $sql = $conn->prepare("SELECT post_id, categorys.category_post, text_post, image_post, date_post, 
                    users.id, users.username, users.image_user 
                    FROM ((posts
                    INNER JOIN users ON posts.id = users.id)
                    INNER JOIN categorys ON categorys.category_id = posts.category_id)
                    WHERE categorys.category_post = ?
                    ORDER BY posts.date_post DESC");
    $sql->execute([$search]);
    $results = $sql->fetchAll(PDO::FETCH_ASSOC);

    $rowCount = count($results);
} else {
    header("Location: home.php");
    ob_end_flush();
    exit;
}
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NAF Community</title>
    <link rel="stylesheet" href="css/search.css">
    <link rel="icon" href="/images/logonaf.png">
</head>

<body>

    <div class="grid-container">
        <?php
        if ($rowCount > 0) {
            foreach ($results as $index => $posts) {
                $headpost = $posts['category_post'];
                $textpost = $posts['text_post'];
                $date = $posts['date_post'];
                $post_user_name = $posts['username'];
                $post_id = $posts['post_id'];
                $img_user = 'profile_img/' . $posts['image_user'];
                $id_user = $posts['id'];

                $date_time = date('d/m/Y  H:i', strtotime($date)) . 'น.'; ?>

                <div class="grid-item">
                    <div class="feed-ps">
                        <div class="feed">
                            <div class="box-feed">
                                <img src="<?php echo htmlspecialchars($img_user); ?>" class="image-pf linkprofile" data-user-id="<?php echo htmlspecialchars($id_user); ?>" loading="lazy">
                                <div class="detail-post linkprofile" data-user-id="<?php echo htmlspecialchars($id_user); ?>">
                                    <span class="post-name"><?php echo htmlspecialchars($post_user_name); ?></span>
                                    <span class="post-date"><?php echo htmlspecialchars($date_time); ?></span>
                                </div>
                            </div>
                            <a href="post-data.php?id=<?php echo htmlspecialchars(urldecode($post_id)); ?>" class="link-post">
                                <div class="text-post">
                                    <div class="grid-2">
                                        <span>#<?php echo htmlspecialchars($headpost); ?></span>
                                        <p class="t-post"><?php echo htmlspecialchars($textpost); ?></p>
                                    </div>
                                    <?php
                                    if (!empty($posts['image_post'])) {
                                        $image = 'backupimage/' . $posts['image_post']; ?>
                                        <div class="grid-1">
                                            <img src="<?php echo htmlspecialchars($image); ?>" alt="post-img" class="imgs" loading="lazy">
                                        </div>
                                    <?php } ?>
                                </div>
                            </a>
                            <div class="ar-icon"></div>
                            <div class="radius">
                                <div class="radiu"></div>
                                <div class="radiu"></div>
                                <div class="radiu"></div>
                            </div>
                        </div>
                    </div>
                </div>

        <?php
            }
        }
        ?>


        <script src="js/goto.js"></script>
</body>

</html>