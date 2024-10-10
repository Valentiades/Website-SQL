<?php

include 'ObjectforAdmin.php'; //เอาไว้แก้สัญลักษณ์ background
require 'config.php';

if (empty($_SESSION['admin'])) {
    echo "<script>window.location.href = 'home.php';</script>";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NAF Community</title>
    <link rel="icon" href="/images/logonaf.png">
    <link rel="stylesheet" href="css/adminPosts.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Data Tables CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/v/bs5/dt-2.1.7/r-3.0.3/datatables.min.css" rel="stylesheet">

    <!-- Script -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.7/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/dt-2.1.7/r-3.0.3/datatables.min.js"></script>

</head>

<body>
    <div class="db-container">
    <a href="admin_Dashboard.php" class="back">
            <button>
                <i class="fas fa-arrow-left"></i> Back
            </button>
        </a>
        <h1>Manage Posts</h1>
        <div class="db-table-container">
            <table id="mytable" class="table table-striped " style="width:100%">
                <thead>
                    <th>Post ID</th>
                    <th>Head</th>
                    <th>Title</th>
                    <th>Picture</th>
                    <th>Date</th>
                    <th>Post By</th>
                    <th>Admin Tools</th>
                </thead>
                <tbody>
                    <?php
                    $stmt = $conn->query("SELECT `post_id`, `category_post`, `text_post`, `image_post`, `date_post`, `id`FROM posts INNER JOIN categorys ON posts.category_id = categorys.category_id;");
                    $stmt->execute();

                    $posts = $stmt->fetchAll();
                    foreach ($posts as $post) {
                    ?>
                        <tr>
                            <td><?php echo $post['post_id'] ?></td>
                            <td><?php echo $post['category_post'] ?></td>
                            <td>
                                <textarea disabled cols="40" style="height: 100px; width: 300px;"><?php echo $post['text_post'] ?></textarea>
                            </td>
                            <td>
                                <?php if (!empty($post['image_post'])) { ?>
                                    <img src="backupimage/<?php echo $post['image_post'] ?>" alt="img" id="posted-image" style="width: 100px; height: auto; border-radius: 8px; margin-top: 10px;">
                                <?php } else { ?>
                                    <p>ไม่พบรูปภาพ</p>
                                <?php } ?>
                            </td>
                            <td>
                                <?php
                                $date_time = new DateTime($post['date_post']);
                                echo $date_time->format('d/m/Y') . '<br>';
                                echo $date_time->format('H:i:s');
                                ?>
                            </td>
                            <td><?php echo $post['id'] ?></td>
                            <td>
                                <button class="db-button-edit db-button" onclick="window.location.href='admin_Edit_Post.php?post_id=<?php echo $post['post_id']; ?>'">แก้ไข</button>
                                <button class="db-button-delete db-button" data-post-id="<?php echo $post['post_id']; ?>">ลบ</button>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Script RUN -->
    <script>
        new DataTable('#mytable');
    </script>

    <!-- Script SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tableContainer = document.querySelector('.db-table-container');
            tableContainer.addEventListener('click', function(event) {
                if (event.target.classList.contains('db-button-delete')) {
                    const postId = event.target.getAttribute('data-post-id');
                    Swal.fire({
                        title: "คุณต้องการลบข้อมูลหรือไม่ ?",
                        text: "ข้อมูลจะถูกลบอย่างถาวรและไม่สามารถย้อนกลับได้ !",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "ตกลง, ลบข้อมูล!",
                        cancelButtonText: "ยกเลิก"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            console.log(result); // เพิ่มบรรทัดนี้เพื่อดีบัก
                            fetch(`admin_delete_post.php?post_id=${postId}`)
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire({
                                            title: "ลบข้อมูลสำเร็จ !",
                                            text: "ข้อมูลของคุณถูกลบแล้ว",
                                            icon: "success"
                                        }).then(() => {
                                            location.reload();
                                        });
                                    } else {
                                        Swal.fire({
                                            title: "เกิดข้อผิดพลาด !",
                                            text: "ไม่สามารถลบข้อมูลได้",
                                            icon: "error"
                                        });
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    Swal.fire({
                                        title: "เกิดข้อผิดพลาด !",
                                        text: "เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์",
                                        icon: "error"
                                    });
                                });
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>