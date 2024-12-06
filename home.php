<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    header('location: login.php');
}
if (isset($_GET['logout'])) {
    unset($user_id);
    session_destroy();
    header('location: login.php');
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}


body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f9;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}


.container {
    width: 100%;
    max-width: 400px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 20px;
    text-align: center;
}


.profile img {
    width: 250px;
    border-radius: 70%;
    object-fit: cover;
    margin-bottom: 0px;
}

.profile h3 {
    font-size: 25px;
    color: #333;
    margin-bottom: 10px;
    font-weight: bold;
}

.profile p {
    font-size: 14px;
    color: #555;
    margin-bottom: 20px;
}


.profile .btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #6c63ff;
    color: #fff;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-bottom: 10px;
}

.profile .delete-btn {
    display: inline-block;
    padding: 10px 20px;
    background-color:red;
    color: #fff;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.profile .btn:hover {
    background-color: #5a53e5;
}
</style>
</head>

<body>
    <div class="container">
        <div class="profile">
            <?php

            $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id ='$user_id'") or die('query failed');
            if (mysqli_num_rows($select) > 0) {
                $fetch = mysqli_fetch_assoc($select);
            }
            if ($fetch['image'] == '') {
                echo '<img src="images/profile.jpg">';
            } else {
                echo '<img src="uploaded_img/' . $fetch['image'] . '">';
            }

            ?>
            <h3><?php echo $fetch['name']; ?></h3>
            <a href="update_profile.php" class="btn">Update Profile</a>
            <a href="home.php?logout=<?php echo $user_id; ?>" class="delete-btn">logout</a> 
            <p>new <a href="login.php">login</a> or <a href="register.php">register</a></p>

        </div>
    </div>

</body>

</html>