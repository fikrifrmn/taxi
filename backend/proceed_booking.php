<?php
include "config.php";
session_start();

$id_user          = $_POST['id_user'];
$id_paket         = $_POST['id_paket'];
$nama_penumpang   = $_POST['nama_penumpang'];
$no_hp            = $_POST['no_hp'];
$tgl_berangkat    = $_POST['tgl_berangkat'];
$jam_berangkat    = $_POST['jam_berangkat'];
$status           = $_POST['status'];
$total_harga      = $_POST['total_harga']; 

$id_kota_tujuan   = !empty($_POST['id_kota_tujuan']) ? $_POST['id_kota_tujuan'] : null;

$id_kelas_pilihan = $_POST['armada']; 

$query_mobil = mysqli_query($config, "SELECT id FROM mobil WHERE id_kelas = '$id_kelas_pilihan' AND status = 'ready' LIMIT 1");
$data_mobil = mysqli_fetch_assoc($query_mobil);

if (!$data_mobil) {
    die("Maaf, armada untuk kelas ini sedang tidak tersedia atau sudah penuh!");
}

$id_mobil = $data_mobil['id'];
$id_kota_sql = ($id_kota_tujuan) ? "'$id_kota_tujuan'" : "NULL";
$id_mobil_sql = ($id_mobil) ? "'$id_mobil'" : "NULL";

$query = "INSERT INTO booking 
(id_user, id_paket, id_kota_tujuan, id_kelas, id_supir, id_mobil, nama_penumpang, no_hp, tgl_berangkat, jam_berangkat, total_harga, status)
VALUES
('$id_user', '$id_paket', $id_kota_sql, '$id_kelas_pilihan', NULL, $id_mobil_sql, '$nama_penumpang', '$no_hp', '$tgl_berangkat', '$jam_berangkat', '$total_harga', '$status')";

if (mysqli_query($config, $query)) {
    $last_id = mysqli_insert_id($config);
    unset($_SESSION['booking_in_progress']);
    header("Location: ../user/payment.php?id_booking=" . $last_id);
} else {
    echo "Gagal menyimpan booking: " . mysqli_error($config);
}
?>