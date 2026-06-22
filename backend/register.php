<?php
include "config.php";

$nama = $_POST['nama'];
$no_hp = $_POST['no_hp'];
$email = $_POST['email'];
$password = $_POST['password'];
$role     = "user";

$stmtCek = mysqli_prepare($config, "SELECT id FROM users WHERE email = ?");
mysqli_stmt_bind_param($stmtCek, "s", $email);
mysqli_stmt_execute($stmtCek);
mysqli_stmt_store_result($stmtCek);

if (mysqli_stmt_num_rows($stmtCek) > 0) {
    header("Location: ../auth.php?msg=email_exists");
    exit;
}
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$queryInsert = "INSERT INTO users (nama, no_hp, email, password, role) VALUES (?, ?, ?, ?, ?)";
$stmtInsert  = mysqli_prepare($config, $queryInsert);

mysqli_stmt_bind_param($stmtInsert, "sssss", $nama, $no_hp, $email, $passwordHash, $role);

if (mysqli_stmt_execute($stmtInsert)) {
    header("Location: ../auth.php?msg=registered");
} else {
    header("Location: ../auth.php?msg=error");
}
    

?>