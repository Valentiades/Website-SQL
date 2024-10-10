<?php
ob_start();

include 'Object.php';

if (empty($_SESSION['admin'])) {
    echo "<script>window.location.href = 'home.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // จัดการอัปโหลดรูปภาพ
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'profile_img/';
        $temp_name = $_FILES['profile_image']['tmp_name'];
        $image_name = uniqid() . '_' . $_FILES['profile_image']['name'];
        if (move_uploaded_file($temp_name, $upload_dir . $image_name)) {
            // อัปเดตชื่อไฟล์รูปภาพในฐานข้อมูล
            $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, role = ?, image_user = ? WHERE id = ?");
            $stmt->execute([$username, $email, $role, $image_name, $id]);
        } else {
            echo "เกิดข้อผิดพลาดในการอัปโหลดไฟล์";
        }
    } else {
        // อัปเดตข้อมูลโดยไม่เปลี่ยนรูปภาพ
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
        $stmt->execute([$username, $email, $role, $id]);
    }
    if ($stmt) {
        header("location: admin_Account.php");
        exit;
        ob_end_flush();
    }
}


$id = $_GET['id'];
$stmt = $conn->prepare("SELECT id, username, email, role, image_user FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NAF Community</title>
    <link rel="stylesheet" href="css/adminEditaccount.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="admin-container">
        <div class="admin-content">
            <a href="admin_Account.php">
                <button class="back">
                    <i class="fas fa-arrow-left"></i> Back
                </button>
            </a>
            <div class="admin-content-header">
                <h2>Change User</h2>
            </div>
            <div class="admin-content-body">
                <form action="admin_Edit_Users.php" method="POST" class="admin-form" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                    <div class="form-group profile-image-container">
                        <img src="profile_img/<?php echo htmlspecialchars($user['image_user']); ?>" alt="Profile Image" id="profile-image-preview" class="profile-image-preview">
                        <label for="profile-image-upload" class="profile-image-upload-label">
                            <i class="material-icons">photo_camera</i>
                            Edit Profile
                        </label>
                        <input type="file" id="profile-image-upload" name="profile_image" accept="image/*" style="display: none;">
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select id="role" name="role">
                            <option value="2" <?php echo $user['role'] == 2 ? 'selected' : ''; ?>>User</option>
                            <option value="1" <?php echo $user['role'] == 1 ? 'selected' : ''; ?>>Admin</option>
                        </select>
                    </div>
                    <button type="submit" class="admin-btn">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('profile-image-upload').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile-image-preview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>
