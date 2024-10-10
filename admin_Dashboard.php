<?php
require 'config.php';
include 'Object.php';

if (empty($_SESSION['admin'])) {
    echo "<script>window.location.href = 'home.php';</script>";
    exit();
}

// นับจำนวนโพสต์ทั้งหมด
$sql_posts = "SELECT COUNT(*) as total_posts FROM posts";
$result_posts = $conn->query($sql_posts);
$row_posts = $result_posts->fetch(PDO::FETCH_ASSOC);
$total_posts = $row_posts['total_posts'];

// นับจำนวนผู้ใช้งานทั้งหมด
$sql_users = "SELECT COUNT(*) as total_users FROM users";
$result_users = $conn->query($sql_users);
$row_users = $result_users->fetch(PDO::FETCH_ASSOC);
$total_users = $row_users['total_users'];

?>

<!DOCTYPE html>
<html>
<head>
    <title>NAF Community</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/adminDashboard.css">
    <link rel="icon" href="/images/logonaf.png">
</head>
<body>
    <div class="dashboard-container">
        <div class="chart-container">
            <canvas id="myChart"></canvas>
        </div>
        <div class="form-box-container">
            <div class="form-box-post" onclick="window.location='admin_Postboard.php'">
                <i class="fas fa-file-alt post-icon"></i>
                <h2>Manage Posts</h2>
            </div>
            <div class="form-box-users" onclick="window.location='admin_Account.php'">
                <i class="fas fa-users user-icon"></i>
                <h2>Manage Users</h2>
            </div>
            <div class="form-box-feedback" onclick="window.location='admin_feedback.php'">
                <i class="fas fa-comments feedback-icon"></i>
                <h2>Manage Feedback</h2>
            </div>
        </div>
    </div>

    <script>
    var xValues = ["Posts", "Users"];
    var yValues = [<?php echo $total_posts; ?>, <?php echo $total_users; ?>];
    var barColors = [
      "#9291fd",
      "#d291fd"
    ];

    new Chart("myChart", {
      type: "doughnut",
      data: {
        labels: xValues,
        datasets: [{
          backgroundColor: barColors,
          data: yValues
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        title: {
          display: true,
          text: "System statistics",
          fontSize: 50,
          fontColor: "#ecf4ff",
          fontFamily: "Kanit",
          fontStyle: "bold",
          fontWeight: "bold"
        },
        legend: {
          display: true,
          position: 'bottom',
          labels: {
            fontSize: 20 
          }
        }
      }
    });
    </script>
</body>
</html>