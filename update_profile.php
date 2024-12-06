<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location: login.php');
}

if (isset($_POST['update'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $old_password = mysqli_real_escape_string($conn, md5($_POST['old_password']));
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);
    
    if ($password != $cpassword) {
        $message[] = 'Password and confirm password do not match!';
    } else {
        $select_old_pass = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id = '$user_id' AND password = '$old_password'") or die('Query failed: ' . mysqli_error($conn));
        if (mysqli_num_rows($select_old_pass) == 0) {
            $message[] = 'Old password is incorrect!';
        } else {
            $hashed_password = md5($password);

            $image = $_FILES['image']['name'];
            $image_tmp_name = $_FILES['image']['tmp_name'];
            $image_folder = 'uploaded_img/' . $image;

            if ($image == '') {
                $update = mysqli_query($conn, "UPDATE `user_form` SET name = '$name', email = '$email', password = '$hashed_password' WHERE id = '$user_id'") or die('Query failed: ' . mysqli_error($conn));
                if ($update) {
                    $message[] = 'Profile updated successfully!';
                }
            } else {
                move_uploaded_file($image_tmp_name, $image_folder);
                $update = mysqli_query($conn, "UPDATE `user_form` SET name = '$name', email = '$email', password = '$hashed_password', image = '$image' WHERE id = '$user_id'") or die('Query failed: ' . mysqli_error($conn));
                if ($update) {
                    $message[] = 'Profile updated successfully!';
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="inputs.css">
    <title>Update Profile</title>
</head>

<body>
    <div class="container">
        <div class="profile">
            <?php
            $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id ='$user_id'") or die('query failed');
            if (mysqli_num_rows($select) > 0) {
                $fetch = mysqli_fetch_assoc($select);
            }
            ?>
            <h3>Update Profile</h3>
            <?php
            if (isset($message)) {
                foreach ($message as $msg) {
                    echo '<div class="message">' . $msg . '</div>';
                }
            }
            ?>
            <form action="" method="post" enctype="multipart/form-data">
                <img src="<?php echo ($fetch['image'] == '') ? 'images/profile.jpg' : 'uploaded_img/' . $fetch['image']; ?>" alt="Profile Avatar">
                <input type="text" name="name" value="<?php echo $fetch['name']; ?>" placeholder="Enter username" class="box" required>
                <input type="email" name="email" value="<?php echo $fetch['email']; ?>" placeholder="Enter email" class="box" required>
                <input type="password" name="old_password" placeholder="Old password" class="box" required>
                <input type="password" name="password" placeholder="Enter new password" class="box">
                <input type="password" name="cpassword" placeholder="Confirm new password" class="box">
                <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png">
                <input type="submit" name="update" value="Update Profile" class="btn">
                <a href="home.php" class="back-btn">Back</a>
            </form>
        </div>
    </div>
</body>

</html>
