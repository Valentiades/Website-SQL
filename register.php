<?php

session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="css/signup.css">
    <link rel="icon" href="/images/logonaf.png">
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
        <h1>Create Account!</h1>
        <form action="register_db.php" id="registerForm" method="POST"
            enctype="multipart/form-data"
            id="register" class="input-group">

            <?php if (isset($_SESSION['success'])) { ?>
                <div class="alert alert-success" role="alert">
                    <?php
                    echo $_SESSION['success'];
                    unset($_SESSION['success']);
                    ?>
                </div>
            <?php } ?>

            <?php if (isset($_SESSION['error'])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php } ?>

            <input type="text"
                class="input-field"
                name="firstname"
                placeholder="Firstname">

            <input type="text"
                class="input-field"
                name="lastname"
                placeholder="Lastname">

            <input type="email"
                class="input-field"
                name="email"
                placeholder="Email">

            <input type="password"
                class="input-field"
                name="password"
                placeholder="Password">

            <input type="password"
                class="input-field"
                name="confirm_password"
                placeholder="Confirm Password">

            <p>Already a member? <a href="login.php">Sign in</a></p>
            <button class="submit-btn" name="register" type="submit">Sign Up</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $("#registerForm").submit(function(e) {
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
                                Swal.fire("สำเร็จ!", result.msg, result.status).then(function() {
                                    window.location.href = "login.php";
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