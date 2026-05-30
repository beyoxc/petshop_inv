<?php
session_start();
include 'config/database.php';

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $query = "SELECT * FROM users 
              WHERE username='$username' 
              AND password='$password'";

    $result = mysqli_query($sales_conn, $query);

    if (mysqli_num_rows($result) > 0) {

        $_SESSION['username'] = $username;

        header("Location: dashboard.php");

    } else {

        echo "Invalid Login";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>

<div class="container">

    <h2>Login</h2>

    <form method="POST">

        <input type="text"
               name="username"
               placeholder="Username"
               required>

        <input type="password"
               name="password"
               placeholder="Password"
               required>

        <button type="submit"
                name="login"
                class="btn">

            Login

        </button>

    </form>

</div>

</body>
</html>