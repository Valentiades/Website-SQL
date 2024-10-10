<?php

include 'Object.php';

if (empty($_SESSION['admin'])) {
    echo "<script>window.location.href = 'home.php';</script>";
    exit();
}

$stmt = $conn->prepare("SELECT users.id, users.email, users.role, users.username, users.image_user, role.role FROM users INNER JOIN role ON users.role = role.role_id;");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NAF Community</title>
    <link rel="stylesheet" href="css/adminAccount.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" href="/images/logonaf.png">
</head>

<body>
    <div class="container">
        <a href="admin_Dashboard.php" class="back">
            <button>
                <i class="fas fa-arrow-left"></i> Back
            </button>
        </a>
        <h1>Manage Users</h1>
        <div class="table-responsive">
            <table class="user-table">
                <thead>
                    <tr>
                        <th>Profile</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Tools</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><img src="profile_img/<?php echo htmlspecialchars($user['image_user']); ?>" alt="Profile" class="profile-img"></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td class="user-role"><?php echo htmlspecialchars($user['role']); ?></td>
                            <td>
                                <a href="admin_Edit_Users.php?id=<?php echo $user['id']; ?>" class="btn edit-btn">Edit</a>
                                <a href="admin_Delete_Account.php?id=<?php echo $user['id']; ?>" class="btn delete-btn" data-id="<?php echo $user['id']; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const userId = this.getAttribute('data-id');
                    Swal.fire({
                        title: 'คุณแน่ใจหรือไม่?',
                        text: "คุณจะไม่สามารถย้อนกลับการกระทำนี้ได้!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'ใช่, ลบเลย!',
                        cancelButtonText: 'ยกเลิก'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = `admin_Delete_Account.php?id=${userId}`;
                        }
                    });
                });
            });

            
            <?php if (isset($_GET['updated']) && $_GET['updated'] == 1) { ?>
                Swal.fire(
                    'อัปเดตสำเร็จ!',
                    'ข้อมูลผู้ใช้ถูกอัปเดตเรียบร้อยแล้ว',
                    'success'
                ).then(() => {
                    unset($_GET['updated']);
                });
            <?php } ?>
            
        });
    </script>

</body>
</html>