<?php
session_start();
require "config.php";
$minLength = 8;

$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirmpassword = $_POST['confirm_password'];

if (!$firstname) {
    echo json_encode(array("status" => "error","msg" => "กรุณากรอกชื่อ"));
    exit();
} else if (!$lastname) {
    echo json_encode(array("status" => "error","msg" => "กรุณากรอกนามสกุล"));
    exit();
} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(array("status" => "error","msg" => "กรุณากรอกอีเมลให้ถูกต้อง"));
    exit();
} else if (!$password){
    echo json_encode(array("status" => "error","msg" => "กรุณากรอกรหัสผ่าน"));
    exit();
} else if (strlen($password) < $minLength) {
    echo json_encode(array("status" => "error","msg" => "รหัสผ่านต้องมีความยาวมากกว่า 8 ตัว"));
    exit();
} else if (!$confirmpassword){
    echo json_encode(array("status" => "error","msg" => "กรุณากรอกรหัสยืนยัน"));
    exit();
} else if ($password !== $confirmpassword) {
    echo json_encode(array("status" => "error","msg" => "รหัสผ่านไม่ตรงกัน"));
    exit();
} else {
    $stmt = $conn->prepare('SELECT COUNT(*) FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $userExists = $stmt->fetchColumn();

    if ($userExists) {
        echo json_encode(array("status" => "error","msg" => "มีผู้ใช้อีเมลล์นี้แล้ว"));
        exit();
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        try {
            $username = $firstname . " " . $lastname;
            $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, email, password, role, username, image_user) VALUES (?, ?, ?, ?, '2', ?, 'avatar.png')");
            $stmt->execute([$firstname, $lastname, $email, $hashedPassword, $username]);
            echo json_encode(array("status" => "success","msg" => "สมัครใช้งานสำเร็จ"));
        } catch (PDOException $e) {
            echo json_encode(array("status" => "error","msg" => "มีบางอย่างผิดพลาด"));
            exit();
        }
    }
}