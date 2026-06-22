<?php
include "config.php";
$id_booking = $_POST['id_booking'];
$id_supir = $_POST['id_supir'];

// Update id_supir dan ubah status ke 'confirmed'
$sql = "UPDATE booking SET id_supir = '$id_supir', status = 'pending' WHERE id = '$id_booking'";
mysqli_query($config, $sql);

header("Location: ../admin/payment.php?msg=driver_ok");
?>