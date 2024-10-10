<?php
    ob_start();
    include 'Object.php'; 

    if (isset($_GET['user_id'])) {

        $user_id = htmlspecialchars($_GET['user_id']);
        $query = $conn->prepare("SELECT id, username, image_user FROM users WHERE id = :ID_user");

    } else if (isset($_SESSION['email'])) {

        $user_id = $_SESSION['current_user_id'];
        $query = $conn->prepare("SELECT id, username, image_user FROM users WHERE id = :ID_user");

    } else {
        header("Location: login.php");
        ob_end_flush();
        exit;
    }
    ob_end_flush();

    $query->bindParam(':ID_user', $user_id);
    $query->execute();
    $user = $query->fetch(PDO::FETCH_ASSOC);


    if($user) {
        $id_user = $user['id'];
        $name_user = $user['username'];
        $img_user = 'profile_img/' . $user['image_user'];

        $query = $conn->prepare("SELECT COUNT(post_id) OVER() AS post_count,
                                post_id, categorys.category_post, text_post, image_post, date_post 
                                FROM posts
                                INNER JOIN categorys ON posts.category_id = categorys.category_id
                                WHERE id = :ID_user
                                ORDER BY date_post DESC");

        $query->bindParam(':ID_user', $user_id);
        $query->execute();

        $post_user = $query->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = count($post_user);

        $post_count = isset($post_user[0]) ? $post_user[0]['post_count'] : 0;
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NAF Community</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/profile.css">
    <link rel="icon" href="/images/logonaf.png">
</head>
<body>
    <div class="pf-gridprofile">
        <div class="pf-position">
            <div class="pf-profile">
                <div class="pf-detail">
                <i class="fa fa-cloud" style="font-size:120px;color:#cfcefd;"></i>
                    <img class="pf-picture" name="picture-data" id = "userimage" src="<?php echo htmlspecialchars($img_user)?>" alt="img">
                    <h1 class="pf-nameprofile" id = "username-new"><?php echo htmlspecialchars($name_user); ?></h1>  
                    <p class="pf-idprofile">#<?php echo htmlspecialchars($id_user); ?></p>
                    <div class="pf-status">
                        <span class="material-symbols-outlined pf-p-icon">assignment</span>
                    </div>
                    <span class="pf-totalpost" id = "pf-totalpost">
                        <?php echo htmlspecialchars($post_count); ?> Post.
                    </span>
                </div>

                <?php if ($id_user == $_SESSION['current_user_id']) { ?>
                    <button class="pf-submit-btn" name="editprofile" type="button" onclick = "openEdit()">Edit Profile</button>
                <?php  }  ?>

            </div>
        </div>

        <div class="pf-mypost">
            <div class="pf-posts-container">
                <?php
                if ($rowCount > 0) {
                    foreach ($post_user as $posts) {
                        $category = $posts['category_post'];
                        $text = $posts['text_post'];
                        $date = $posts['date_post'];
                        $id_post = $posts['post_id'];
                        $date_time = date('d/m/Y  H:i', strtotime($date)) . 'น.';
                ?>
            <div class="realtime">
                <div class="pf-post">
                <a href="post-data.php?id=<?php echo urlencode($id_post); ?>&return_url=<?php echo urlencode('profile.php'); ?>" class="link-post">
                        <div class="pf-feed">
                            <div class="pf-box-feed">
                                <img src="<?php echo htmlspecialchars($img_user)?>" class="pf-image-pf" id="userimage2">
                                <div class="pf-detail-post">
                                    <span class="pf-post-name" id="username-new2"><?php echo htmlspecialchars($name_user); ?></span>
                                    <span class="pf-post-date"><?php echo htmlspecialchars($date_time); ?></span>
                                </div>
                            </div>
                            <div class="pf-text-post">
                                <div class="pf-grid-2">
                                    <span><?php echo "#" . htmlspecialchars($category); ?></span>
                                    <p class="pf-t-post"><?php echo nl2br(htmlspecialchars($text)); ?></p>
                                </div>
                                <?php 
                                if (!empty($posts['image_post'])) {
                                    $image = 'backupimage/' . $posts['image_post'];
                                ?>
                                <div class="pf-grid-1">
                                    <img src="<?php echo htmlspecialchars($image); ?>" alt="post-img" class="pf-imgs">
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>

    <div class="edit-profile">
        <div class="fade-edit"></div>
        <div class="edit-form">
            <div class="form-box">
                <button type="button" class = "close-bt" onclick = "closeEdit()"><span class="material-symbols-outlined close-edit">close</span></button>
                <h1>PROFILE</h1>
                <form id="edit" method="POST"class="input-group">
                    <div class="picture">
                    <img class="picture-data" id="picture-data" name="picture-data" src="<?php echo htmlspecialchars($img_user) ?>" alt="img">
                    <label for="file-upload" class="custom-file-upload" name="img_user">
                        <input id="file-upload" type="file" accept="image/*" name="img_user" onchange="preview_pf(this)">
                        <span>Custom Upload</span>
                    </label>
                    </div>
                    <h4>Edit Profile</h4><br>
                    <span class = "warn-pf"></span>
                    <div class="name-users">
                        <input type="text" class="input_fname" name="firstname" placeholder="<?php echo htmlspecialchars($name_user); ?>" autocomplete="off" >
                    </div>
                    <div class="ps-botton">
                        <button type="submit" class="submit-btn" onclick = "submitprofile(event)">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src = "js/profile-edit.js"></script>

</body>
</html>