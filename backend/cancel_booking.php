<?php
session_start();
include "../backend/config.php";

if (!isset($_SESSION['id'])) {
    header("Location: ../auth.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_booking = $_POST['id_booking'];
    $id_user = $_SESSION['id'];

    // Pastikan booking milik user & masih pending
    $cek = mysqli_query($config, "
        SELECT * FROM booking 
        WHERE id = '$id_booking' 
        AND id_user = '$id_user'
        AND status = 'pending'
    ");

    if (mysqli_num_rows($cek) > 0) {
        // Update jadi cancelled
        mysqli_query($config, "
            UPDATE booking 
            SET status = 'cancelled' 
            WHERE id = '$id_booking'
        ");
    }

    header("Location: ../user/home.php");
    exit;
}
?>
