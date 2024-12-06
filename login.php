<?php
include 'config.php';
session_start();

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, md5($_POST['password']));

    // التحقق من وجود المستخدم في قاعدة البيانات
    $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE email = '$email' AND password = '$pass'")
        or die('Query failed: ' . mysqli_error($conn));

    if (mysqli_num_rows($select) > 0) {
        $row = mysqli_fetch_assoc($select);

        // حفظ بيانات المستخدم في الجلسة
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_name'] = $row['name'];

        // إعادة التوجيه إلى صفحة المستخدم
        header('Location: home.php');
        exit();
    } else {
        $message[] = 'Incorrect email or password!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>

<body>
    <div class="form-container">
        <form action="" method="post">
            <h3>Login Now</h3>
            <?php
            if (isset($message)) {
                foreach ($message as $msg) {
                    echo '<div class="message">' . $msg . '</div>';
                }
            }
            ?>
            <input type="email" name="email" placeholder="Enter email" class="box" required>
            <input type="password" name="password" placeholder="Enter password" class="box" required>
            <input type="submit" name="submit" value="Login Now" class="btn">
            <p>Don't have an account? <a href="register.php">Register now</a></p>
        </form>
    </div>
</body>

</html>
