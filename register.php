<?php
include 'config.php';

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
    $cpass = mysqli_real_escape_string($conn, md5($_POST['cpassword']));
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/' . $image;

    // التحقق من وجود المستخدم مسبقًا
    $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE email = '$email'")
        or die('Query failed: ' . mysqli_error($conn));

    if (mysqli_num_rows($select) > 0) {
        $message[] = 'User already exists!';
    } else {
        if ($pass != $cpass) {
            $message[] = 'Confirm password not matched!';
        } elseif ($image_size > 2000000) {
            $message[] = 'Image size is too large!';
        } else {
            // إدخال البيانات في الجدول
            $insert = mysqli_query($conn, "INSERT INTO `user_form` (name, email, password, image) 
                VALUES ('$name', '$email', '$pass', '$image')")
                or die('Insert query failed: ' . mysqli_error($conn));

            if ($insert) {
                move_uploaded_file($image_tmp_name, $image_folder);
                $message[] = 'Registered successfully!';
                header('Location: login.php');
                exit();
            } else {
                $message[] = 'Registration failed!';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="style.css">
    <title>Register</title>
</head>

<body>
    <div class="form-container">
    <form action="" method="post" enctype="multipart/form-data">
            <h3>Register Now</h3>
            <?php
            if(isset($message)){
                foreach ($message as $message){
                    echo '<div class="message">'.$message.'</div>';
                }
            }
            ?>
            <input type="text" name="name" placeholder="enter username" class="box" required>
            <input type="email" name="email" placeholder="enter email" class="box" required>
            <input type="password" name="password" placeholder="enter password" class="box" required>
            <input type="password" name="cpassword" placeholder="confirm password" class="box" required>
            <input type="file" class="box" accept="image/jpg, image/jpeg, image/png"> 
            <input type="submit" name="submit" value="register now" class="btn">
            <p>already have an account? <a href="login.php">login now</a></p>
        </form>
    </div>
</body>

</html>