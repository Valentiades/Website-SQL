<?php

session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NAF Community</title>
    <link rel="icon" href="/images/logonaf.png">
    <link rel="stylesheet" href="css/signin.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
</head>

<body>
<div class="background">
        <ul class="circles">
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
    </div>
    <div class="form-box">
        <h1>Hello Friend!</h1>
        <form action="login_db.php" id="loginForm" method="POST"
            id="login" class="input-group">

            <input type="email"
                class="input-field"
                name="email"
                placeholder="Email">
            <input type="password"
                class="input-field"
                name="password"
                placeholder="Password">
            <p class="link_sign-up">Not yet a member? <a href="register.php">Sign up</a></p>
            <button class="submit-btn" name="login" type="submit">Sign In</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $("#loginForm").submit(function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: $(this).attr("action"),
                    type: $(this).attr("method"),
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        try {
                            let result = JSON.parse(data);
                            if (result.status == "success") {
                                console.log("Success", result);
                                Swal.fire("สำเร็จ!", result.msg, result.status).then(function(){
                                    window.location.href = "home.php";
                                });
                            } else {
                                console.log("Error", result);
                                Swal.fire("ล้มเหลว!", result.msg, result.status);
                            }
                        } catch (e) {
                            console.error("Error parsing JSON", e);
                            Swal.fire("ข้อผิดพลาด!", "เกิดข้อผิดพลาดในการประมวลผลข้อมูล", "error");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error", status, error);
                        Swal.fire("ข้อผิดพลาด!", "เกิดข้อผิดพลาดในการส่งข้อมูล", "error");
                    }
                });
            });
        });
    </script>
</body>

</html>