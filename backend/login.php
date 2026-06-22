<?php
session_start();
include "config.php";

$email = $_POST['email'];
$password = $_POST['password'];

$query = "SELECT id, nama, email, password, role FROM users WHERE email = ?";
$stmt  = mysqli_prepare($config, $query);

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user   = mysqli_fetch_assoc($result);

if ($user) {
    if (password_verify($password, $user['password'])) {
        $_SESSION['id']    = $user['id'];
        $_SESSION['nama']  = $user['nama'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role']  = $user['role'];

         if ($user['role'] == "admin") {
            header("Location: ../admin/index.php");
        } else {
            header("Location: ../user/home.php");
        }
        exit;
    } else {
        header("Location: ../auth.php?msg=password");
    }

} else {
    header("Location: ../auth.php?msg=email");
}

?>