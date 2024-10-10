<?php

    session_start();
    require "config.php";

    // if(isset($_POST['login'])){
        $email = $_POST['email'];
        $password =  $_POST['password'];
    // }


    if (!filter_var($email,FILTER_VALIDATE_EMAIL)){
        echo json_encode(array("status" => "error","msg" => "กรุณากรอกอีเมล"));
    } else {
        try {
            
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $userData = $stmt->fetch();

            if($userData && password_verify($password,$userData['password'])){
                echo json_encode(array("status" => "success","msg" => "ลงชื่อเข้าใข้สำเร็จ"));
                $_SESSION['email'] = $email;
            } else {
                echo json_encode(array("status" => "error","msg" => "ไม่พบผูู้ใช้"));
            }

        } catch(PDOException $e) {
            echo json_encode(array("status" => "error","msg" => "เกิดข้อผิดพลาด"));
        }
    }

?>