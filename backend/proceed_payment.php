<?php
include "config.php";

$id_booking = $_POST['id_booking'] ?? null;
$method     = $_POST['method_id'] ?? null;
$total_bayar = $_POST['total'] ?? 0;
$tgl_bayar  = date('Y-m-d H:i:s');

// Validasi penting
if (!$id_booking || !$method) {
    die("Data tidak lengkap");
}

// Proses Upload Foto
$folder = "../assets/uploads/bukti/";

if (!is_dir($folder)) {
    mkdir($folder, 0777, true);
}

$file_name = time() . "_" . $_FILES['bukti_pembayaran']['name'];
$tmp_name  = $_FILES['bukti_pembayaran']['tmp_name'];

if (move_uploaded_file($tmp_name, $folder . $file_name)) {

    $query_pay = "INSERT INTO pembayaran 
    (id_booking, id_metode, total, bukti_pembayaran, status_pembayaran, tgl_bayar) 
    VALUES 
    ('$id_booking', '$method', '$total_bayar', '$file_name', 'pending', '$tgl_bayar')";

    if (mysqli_query($config, $query_pay)) {

        echo "<script>
                alert('Bukti pembayaran berhasil diupload!');
                window.location.href = '../user/order.php';
              </script>";

    } else {
        echo "Gagal insert: " . mysqli_error($config);
    }

} else {
    echo "Gagal upload gambar.";
}
?>
