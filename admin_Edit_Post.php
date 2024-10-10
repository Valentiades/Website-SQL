<?php
    ob_start();
    include 'Object.php';
    require 'config.php';
    if (empty($_SESSION['admin'])) {
        echo "<script>window.location.href = 'home.php';</script>";
        exit();
    }

    if (isset($_GET['post_id'])) {
        $postid = htmlspecialchars($_GET['post_id']);
        $sql = "SELECT category_post,text_post,image_post,date_post,users.firstname,users.lastname,users.id,categorys.category_id FROM ((posts INNER JOIN users ON posts.id = users.id) INNER JOIN categorys ON posts.category_id = categorys.category_id) WHERE post_id = :ID_post;";
        $query = $conn->prepare($sql);
        $query->bindParam(':ID_post', $postid);
        $query->execute();
        $fetch = $query->fetch(PDO::FETCH_ASSOC);

        if (!empty($fetch)) {
            $headpost = $fetch['category_post'];
            $textpost = $fetch['text_post'];
            $date = $fetch['date_post'];
            $post_user_name = $fetch['firstname']." ".$fetch['lastname'];
            $post_user_id = $fetch['id'];
            $date_time = date('d/m/Y  H:i', strtotime($date)) . 'น.';
            $old_image = $fetch['image_post'];
            $old_category_id = $fetch['category_id'];
        } 
    } else {
        ob_end_clean();
        header("Location: admin_Postboard.php");
        exit();
    }   
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Edit Post</title>
    <link rel="stylesheet" href="css/adminEditPost.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="/images/logonaf.png">
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
</head>

<body>
    <div class="form-box">
        <a href="admin_Postboard.php" class="back">
            <button>
                <i class="fas fa-arrow-left"></i> Back
            </button>
        </a>
        <h1>Edit Post</h1>
        <form action="admin_Update_Post.php" id="editPostForm" method="POST" enctype="multipart/form-data" class="input-group">
            <label for="title" class="label-title">Head</label>
            <select class="input-field" name="category" id="category" required>
                <?php 
                echo "<option disabled hidden selected >".$headpost."</option>";
                ?>
                <?php
                    $sql = "SELECT * FROM categorys";
                    $query = $conn->prepare($sql);
                    $query->execute();
                    $fetch = $query->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($fetch as $row) {
                        $category_id = $row['category_id']; 
                        $category_post = $row['category_post']; 
                        if ($category_id != $headpost) {
                            echo "<option value='".$category_id."' class='category-option'>".$category_post."</option>";
                        }
                    }
                    $category_id = $old_category_id;
                ?>
            </select>
            <label for="content" class="label-title">Title</label>
            <textarea rows="6" cols="50" name="textedit" class="textpostedit" id="textedit" value="<?php echo htmlspecialchars($textpost); ?>"><?php echo htmlspecialchars($textpost); ?></textarea>
            <div class="image-container">
                <img src="backupimage/<?php echo htmlspecialchars($old_image); ?>" alt="Current Image" id="posted-image">
                <div class="file-input-wrapper">
                    <input type="file" class="post-image-uploaded" name="image_new_upload" id="image-new-upload" onchange="preview_pf(this)" accept="image/*">
                    <label for="image-new-upload">Change Image</label>
                </div>
                <img id="preview-image" style="display: none;">
            </div>
            <input type="hidden" name="old_image" value="<?php echo htmlspecialchars($old_image); ?>">
            <input type="hidden" name="post_id" value="<?php echo $postid; ?>">
            <input type="hidden" name="category_id" id="category_id" value="<?php echo $category_id; ?>">
            <button class="submit-btn" name="update" type="submit" data-post-id="<?php echo $postid; ?>">UPDATE</button>
        </form>
    </div>


    <!-- Script SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Preview Image Uploaded -->
    <script>
        function preview_pf(input) {
            const previewImage = document.getElementById('preview-image');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewImage.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

    <!-- show category_id -->
    <script>
        console.log("NEW_Category_ID: "+this.value);
        console.log("OLD_Category_ID: <?php echo $old_category_id; ?>");
    </script>

    <!-- update category_id -->
    <script>
        document.getElementById('category').addEventListener('change', function() {
            document.getElementById('category_id').value = this.value;
        });
    </script>

    <!-- output category_id -->
    <script>
        document.getElementById('category').addEventListener('change', function() {
            console.log("NEW_Category_ID: "+this.value);
            console.log("OLD_Category_ID: <?php echo $old_category_id; ?>");
        });
    </script>

    <!-- update post -->
    <script>
$(document).ready(function() {
    $("#editPostForm").submit(function(e) {
        e.preventDefault(); 
        let formUrl = $(this).attr("action");
        let reqMethod = $(this).attr("method");
        const img = $("#image-new-upload")[0].files[0];
        let formData = new FormData(this);
        
        if (img) {
            formData.append('image-new-upload', img);
        }

        $.ajax({
            url: formUrl,
            type: reqMethod,
            data: formData,
            processData: false, // ต้องเป็น false เพื่อไม่ให้ jQuery แปลงข้อมูล
            contentType: false, // ต้องเป็น false เพื่อให้ jQuery ตั้งค่า content type เป็น multipart/form-data
            success: function(data) {
                try {
                    let result = JSON.parse(data); //แปลงข้อมูลเป็นออบเจ็ค
                    if (result.status === "success") {
                        Swal.fire({
                            title: "สำเร็จ",
                            text: result.message,
                            icon: "success",
                            showConfirmButton: true,
                        }).then(() => {
                            window.location.href = "admin_Postboard.php"; // ย้ายไปที่ admin_Postboard.php หลังจากการแจ้งเตือนสำเร็จ
                        });
                    } else {
                        Swal.fire({
                            title: "ผิดพลาด",
                            text: result.message,
                            icon: "error",
                            showConfirmButton: true,
                        });
                    }
                } catch (e) {
                    Swal.fire({
                        title: "ผิดพลาด",
                        text: "เกิดข้อผิดพลาดในการแปลงข้อมูล",
                        icon: "error",
                        showConfirmButton: true,
                    });
                }
            },
            error: function(xhr, status, error) {
                // จัดการข้อผิดพลาดที่เกิดขึ้นระหว่างการส่งข้อมูล
                Swal.fire({
                    title: "ผิดพลาด",
                    text: "เกิดข้อผิดพลาดในการส่งข้อมูล: " + error,
                    icon: "error",
                    showConfirmButton: true,แ
                });
            }
        });
    });
});

    </script>

</body>

</html>
