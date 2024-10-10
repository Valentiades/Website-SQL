<?php 

        ob_start();
        include 'Object.php';

    if (isset($_GET['id'])) {
        $return_url = isset($_GET['return_url']) ? $_GET['return_url'] : 'home.php';
        $postid = htmlspecialchars($_GET['id']);

        $sql = "SELECT categorys.category_id, categorys.category_post, text_post, image_post, date_post, users.username, users.image_user, users.id 
                FROM ((posts 
                INNER JOIN users ON posts.id = users.id )
                INNER JOIN categorys ON posts.category_id = categorys.category_id)
                WHERE post_id = :ID_post";
        $query = $conn->prepare($sql);
        $query->bindParam(':ID_post', $postid);
        $query->execute();
        $fetch = $query->fetch(PDO::FETCH_ASSOC);

        if (!empty($fetch)) {
            $headpost = $fetch['category_post'];
            $textpost = $fetch['text_post'];
            $date = $fetch['date_post'];
            $post_user_name = $fetch['username'];
            $post_user_id = $fetch['id'];
            $img_post = 'profile_img/' . $fetch['image_user'];
            $category_id = $fetch['category_id'];

            $date_time = date('d/m/Y  H:i', strtotime($date)) . 'น.';

            $image = "";

        } else {
            header("Location: home.php");
            ob_end_flush();
            exit;
        }
        ob_end_flush();
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
    <link rel="stylesheet" href="css/post-data.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="icon" href="/images/logonaf.png">
    <link rel="stylesheet" href="css/Object.css">
    <title>NAF Community</title>
</head>
<body>


<div class="tappostedit">
        <div class="fadeedit"></div>
        <div class="boxpostedit">
            <div class="boxedit">
                <span class = "post-titleedit">EDIT</span>
                <button class = "closeboxedit" id = "clospost" onclick="closePopup()"><span class="material-symbols-outlined">
                    cancel
                </span></button>
                <form id = "editForm" method = "POST" enctype="multipart/form-data">
                    <div class="dropdownedit">
                        <div class="selectoredit">
                        <input type="text" name="headpostedit" id="selectedCategory" readonly value="<?php echo htmlspecialchars($headpost); ?>" data-value="<?php echo htmlspecialchars($category_id); ?>">
                        </div>
                        <div class="listsedit">
                            <div class="liste" data-value="1">ทั่วไป</div>
                            <div class="liste" data-value="2">ข่าวสาร</div>
                            <div class="liste" data-value="3">อุบัติเหตุ</div>
                            <div class="liste" data-value="4">เทคโนโลยี</div>
                            <div class="liste" data-value="5">สถานที่ท่องเที่ยว</div>
                            <div class="liste" data-value="6">สุขภาพ</div>
                            <div class="liste" data-value="7">ความรัก</div>
                            <div class="liste" data-value="8">กีฬา</div>
                            <div class="liste" data-value="9">ภาพถ่าย</div>
                            <div class="liste" data-value="10">ตลาดมือสอง</div>
                            <div class="liste" data-value="11">รถยนต์</div>
                            <div class="liste" data-value="12">จักรยานยนต์</div>
                        </div>
                    </div>
                    <textarea rows="6" cols="50" name = "textedit" class = "textpostedit" placeholder="ใส่ข้อความเพื่อเริ่มพูดคุย . . ." ><?php echo htmlspecialchars($textpost); ?></textarea>
                    <span class = "warnedit"></span>
                    <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($postid); ?>">

                    
                    <label for = "imageedit" class = "im-uploadedit">
                        <span class="material-symbols-outlined">add_a_photo</span>
                    </label>
                    <input type="file" id="imageedit" name="imageedit" accept="image/*" onchange="preview_edit(this)">
                    <?php 
                        if (!empty($fetch['image_post'])) {  
                            $image = 'backupimage/' . htmlspecialchars($fetch['image_post']); ?>
                            <input type="hidden" name="backupedit" id = "backupedit" value="<?php echo htmlspecialchars($image); ?>">
                            <input type="hidden" name="image_deleted" id="image_deleted" value="0">
                    <?php }  ?>
                        
                    
                        <div class="show-previewedit" >    
                            <div class="image-containeredit" >
                                <div class="previewedit"> 
                                    <button type = "button" class = "de-previewedit" onclick = "remove_edit()" ><span class="material-symbols-outlined">delete</span></button>
                                    <img id="preview-edit" 
                                    <?php if (!empty($fetch['image_post'])) { 
                                        $image = 'backupimage/' . htmlspecialchars($fetch['image_post']); ?>
                                    src="<?php echo $image; ?>" style="display: block;" 
                                    <?php } ?> alt = "image">
                                </div>
                            </div>    
                        </div>

                    <button class="postedit" type="submit">EDIT<span class="material-symbols-outlined">
                        send
                    </span></button>
                </form>
            </div>
        </div>
</div>


<div class="gridpost">

    <div class="template">
        <div class="postform">
            <div class="posts">
                <div class="profile">
                    <img src = "<?php echo htmlspecialchars($img_post); ?>" class = "image-pf linkprofile" data-user-id="<?php echo htmlspecialchars($post_user_id); ?>">
                    <div class="profile-text">
                        <span class = "username linkprofile" data-user-id="<?php echo htmlspecialchars($post_user_id); ?>">
                            <?php echo htmlspecialchars($post_user_name); ?>
                        </span>
                        <span class = "datetime">
                            <?php echo htmlspecialchars($date_time);?>
                        </span>
                    </div>

                    <?php if (!empty($post_user_id)) { ?>
                    <?php if ($post_user_id == $_SESSION['current_user_id'] || $_SESSION['admin']) { ?>
                    
                        <div class="menu">
                            <button type = "button" class = "menu-bt" id = "menu-bt">
                                <span class="material-symbols-outlined menu-icon">more_vert</span>
                                <div class="dropmenu">
                                    <ul class="listmenu">
                                        <li class = "edit" id = "editpost" onclick="editpost()" >Edit Post<span class="material-symbols-outlined edi">edit</span></li>
                                        <li class = "delete" id = "delete" onclick="deletepost()" >Delete Post<span class="material-symbols-outlined di">delete_forever</span></li>
                                    </ul>
                                </div>
                            </button>
                            <div class="backdrop">
                                <div class="warns">
                                    <div class="delete-w">
                                        <div class="delete-h">
                                            <span class="material-symbols-outlined warn-icon">error</span>
                                            <span class = "h-warn">Delete Post</span>
                                        </div>
                                        <span class = "t-warn">การลบโพสต์จะไม่สามารถกู้คืนโพสต์ได้</span>
                                        <div class="warn-bt">
                                            <button type = "button" class = "cancel" id = "cancel" onclick="deletecancel()" >CANCEL</button>
                                            <form method="POST" class = "form-delete" onsubmit="deletePost(event, <?php echo htmlspecialchars($postid); ?>)">
                                                <input type="hidden" name="postID" value = "<?php echo htmlspecialchars($postid);?>">
                                                <button type = "submit" class = "detele-ok">DELETE</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                    <?php  }  ?>
                    <?php  }  ?>

                </div>
                <span class = "headtext">
                    <?php echo "#" . htmlspecialchars($headpost); ?>
                </span>
                <p class = "text">
                    <?php 
                        if(empty($textpost)) { ?> 
                            ไม่มีข้อมูล <?php 
                        } else { ?>
                            <?php echo nl2br(htmlspecialchars($textpost)); ?> 
                    <?php } ?></p>
                <?php 
                    if (!empty($fetch['image_post'])) {
                        $image = 'backupimage/'.$fetch['image_post']; ?>
                        <img src = "<?php echo htmlspecialchars($image); ?>" alt="Post Image" class = "image-ul" loading="lazy"> 
                    <?php }
                ?>
            </div>
        </div>
    </div>
<div class="comment-state">
    <div class="statuspost">
        <div class="status">
            <div class="likes">
                <button type = "button" class = "like-bt"><span class="material-symbols-outlined com-icon">tooltip</span></button>
                <span class = "com" id = "comss"></span>
            </div>
<?php if (isset($_SESSION['email'])) { ?>
            <div class="comment">
                <form id = "postcomment" name = "post-comment" method="post" class = "form-com">
                    <textarea name="comment_text" id="commentsend" class = "comment_text" placeholder="แสดงความคิดเห็น . . ."></textarea>
                    <input type="hidden" name="post_id_com" value = "<?php echo htmlspecialchars($postid);?>">
                    <button type = "submit" class = "com-bt" id = "sub_com" onclick = "submitCom(event)"><span class="material-symbols-outlined write-icon">draw</span></button>
                </form>
            </div>
        </div>
<?php } ?>
        <div class="comments-container" id = "realcom">

        </div>
    </div>
</div>


    <script src = "js/post-data.js"></script>
    <script src = "js/post-edit.js" ></script>
    <script src = "js/goto.js" ></script>
</body>

<script>

const post_user_id = <?php echo json_encode($post_user_id); ?>;
const postid = <?php echo json_encode($postid); ?>;

window.onload = function() {
        fetchComs();
    };


function deletePost(event, postId) {
    event.preventDefault();

    fetch('post-delete.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ postId: postId })
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            const returnUrl = '<?= $return_url ?>';
            window.location.href = returnUrl; 
        } 
    })
}
</script>
</html>

