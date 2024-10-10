<?php
session_start();
require_once('config.php');

if (empty($_SESSION['admin'])) {
    echo "<script>window.location.href = 'home.php';</script>";
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: admin_Account.php");
exit();