<?php


require "config.php";
session_start();

if (isset($_SESSION['email'])) {
    $showemail = $_SESSION['email'];
    $sql = "SELECT id, username, image_user, role FROM users WHERE email = :email";
    $query = $conn->prepare($sql);
    $query->bindParam(':email', $showemail);
    $query->execute();
    $fetch = $query->fetch(PDO::FETCH_ASSOC);

    $show_name = $fetch['username'];
    $show_id = $fetch['id'];
    $show_img = 'profile_img/' . $fetch['image_user'];
    $_SESSION['current_user_id'] = $show_id;

    if ($fetch['role'] == 1) {
        $_SESSION['admin'] = $fetch['role'];
    }
} else {
    $_SESSION['current_user_id'] = "";
    $_SESSION['admin'] = "";
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/ObjectforAdmin.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="icon" href="/images/logonaf.png">
</head>

<body>
    <div class="background-admin">
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

    <div class="searchbar">
        <div class="tapsearch">
            <span class="s">SEARCH</span>
            <span class="material-symbols-outlined search-icon">search</span>

            <div class="search-bt">
                <form action="search.php" method="GET" class="search">
                    <input type="text" name="search" autocomplete="off" placeholder="Search . . .">
                    <button type="submit"><span class="material-symbols-outlined search-iconin">search</span></button>
                </form> 
                <div class="list-head">
                    <ul class="trends" id ="headpost">

                    </ul>
                </div>
            </div>
        </div>
    </div>



    <div class="sidebar-m">
        <div class="sidebar">
            <div class="sidebar-header">
                <img src="images/logonaf.png" alt="Logo">
                <span class="menu-h">Menu</span>
            </div>

            <ul class="sidebar-link">
                <span class="span-h">General</span>
                <li onclick="window.location='home.php'">
                    <span class="material-symbols-outlined">groups</span>
                    <a href="home.php">Feed Community</a>
                </li>
                

                <?php if (isset($_SESSION['email'])) { ?>
                    <li id="post_popup" class="post_popup" onclick = "openPopup()">
                        <span class="material-symbols-outlined flip">maps_ugc</span>
                        <a class="post-a">Post</a>
                    </li>
                

                <li>
                    <span class="material-symbols-outlined">feedback</span>
                    <a href="feedback.php">Feedback</a>
                </li>
                <?php } ?>
                    <span class="span-h">Account</span>
                <?php if (isset($_SESSION['email'])) { ?>
                    <li onclick="window.location='profile.php'">
                        <span class="material-symbols-outlined">account_circle</span>
                        <a href="profile.php">Profile</a>
                    </li>
                    <li onclick="submitlogout()">
                        <span class="material-symbols-outlined">logout</span>
                        <a class="logout-a">Logout</a>
                    </li>
                <?php } else { ?>
                    <li onclick="window.location='login.php'">
                        <span class="material-symbols-outlined">person</span>
                        <a href="login.php">Sign In</a>
                    </li>
                    <li onclick="window.location='register.php'">
                        <span class="material-symbols-outlined">badge</span>
                        <a href="register.php">Sign Up</a>
                    </li>
                <?php } ?>
                <?php if ($_SESSION['admin']) { ?>
                    <li onclick="window.location='admin_Dashboard.php'">
                    <span class="material-symbols-outlined">shield_person</span>
                        <a class="admin-a">Admin</a>
                    </li>
                <?php } ?>
            </ul>

            <div class="user-profile">
                <img src="<?php if (isset($_SESSION['email'])) {
                                    echo $show_img;
                                } else { ?>
                                    images/avatar.png
                            <?php } ?> " alt="profile-img" class = "img-detial">
                <div class="user-detail">
                    <p>
                        <?php
                        if (isset($_SESSION['email'])) {
                            echo $show_name;
                        } else { ?>
                            Guest
                        <?php  }  ?>
                    </p>
                    <span>
                        <?php
                        if (isset($_SESSION['email'])) {
                            echo "#" . $show_id;
                        }
                        ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="logout-drop">
        <div class="logout">
            <div class="logout-w">
                <div class="logout-h">
                    <span class="material-symbols-outlined out-icon">error</span>
                    <span class = "h-out">Logout</span>
                </div>
                <span class = "t-out">ยืนยันการออกจากระบบ</span>
                <div class="out-bt">
                    <button type = "button" class = "cancels" id = "cancel" onclick="logcancel()" >CANCEL</button>
                    <button type = "submit" class = "detele-oks" onclick="window.location.href='logout.php';">LOGOUT</button>
                </div>
            </div>
        </div>
    </div>

    <div class="tappost">
        <div class="fade"></div>
        <div class="boxpost">
            <div class="box">
                <span class="post-title">POST</span>
                <button class="closebox" id="clospost"><span class="material-symbols-outlined" onclick = "closePopups()">
                        cancel
                    </span></button>
                <form id="postForm" method="POST" enctype="multipart/form-data" >
                    <div class="dropdown">
                        <div class="selector">
                            <input type="text" name="headposts" id="headpost" readonly value="เลือกกระทู้">
                        </div>
                        <div class="lists">
                            <div class="list" data-value="1">ทั่วไป</div>
                            <div class="list" data-value="2">ข่าวสาร</div>
                            <div class="list" data-value="3">อุบัติเหตุ</div>
                            <div class="list" data-value="4">เทคโนโลยี</div>
                            <div class="list" data-value="5">สถานที่ท่องเที่ยว</div>
                            <div class="list" data-value="6">สุขภาพ</div>
                            <div class="list" data-value="7">ความรัก</div>
                            <div class="list" data-value="8">กีฬา</div>
                            <div class="list" data-value="9">ภาพถ่าย</div>
                            <div class="list" data-value="10">ตลาดมือสอง</div>
                            <div class="list" data-value="11">รถยนต์</div>
                            <div class="list" data-value="12">จักรยานยนต์</div>
                        </div>
                    </div>
                    <textarea rows="6" cols="50" name="text" class="textpost" placeholder="ใส่ข้อความเพื่อเริ่มพูดคุย . . ."></textarea>
                    <span class="warn"></span>
                    <label for="imageUpload" class="im-upload">
                        <span class="material-symbols-outlined">add_a_photo</span>
                    </label>
                    <input type="file" id="imageUpload" name="imageUpload" accept="image/*" onchange="previewImg(this)">
                    <div class="show-preview" id="show-preview">
                        <div class="image-container">
                            <div class="preview" id="preview">
                                <button type="button" class="de-preview" onclick="removeImg()"><span class="material-symbols-outlined">delete</span></button>
                                <img id="previewImage" alt="image">
                            </div>
                        </div>
                    </div>

                    <button class="post" type="submit">POST<span class="material-symbols-outlined">
                            send
                        </span></button>
                </form>
            </div>
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/script.js"></script>
</body>

<script>
        function fetchPosts() {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'get_headposts.php', true);

            xhr.onload = function() {
                if (xhr.status === 200) {
                    document.getElementById('headpost').innerHTML = xhr.responseText;
                } else {
                    console.error('Error:', xhr.statusText);
                }
            };

            xhr.send();
        }
        setInterval(fetchPosts, 10000);
        window.onload = fetchPosts();
    </script>
</html>