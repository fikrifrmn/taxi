<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_travel";

$config = mysqli_connect($host, $user, $pass, $db);

if (!$config) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
