<?php
include "config.php";

$id_booking = $_POST['id_booking'] ?? null;
$id_payment = $_POST['id_pembayaran'] ?? null;
$action     = $_POST['action'] ?? null;

if (!$id_booking || !$action) {
    die("Data tidak lengkap");
}

if ($action == "approve") {

    mysqli_query($config, "UPDATE booking SET status = 'paid' WHERE id = '$id_booking'");
    mysqli_query($config, "UPDATE pembayaran SET status_pembayaran = 'paid' WHERE id = '$id_payment'");

    header("Location: ../admin/payment.php?msg=approved");

} elseif ($action == "reject") {

    mysqli_query($config, "UPDATE pembayaran SET status_pembayaran = 'rejected' WHERE id = '$id_payment'");

    header("Location: ../admin/payment.php?msg=rejected");

} else {
    die("Action tidak valid");
}
?>
