<?php
session_start();
include "../backend/config.php";

if (!isset($_SESSION['id']) || !isset($_GET['id'])) {
    header("Location: ../auth.php");
    exit;
}

$id_booking = $_GET['id'];
$id_user = $_SESSION['id'];

// Query Detail Tiket dengan tambahan JOIN Supir dan Mobil
$query = mysqli_query($config, "
    SELECT b.*, 
           u.nama AS nama_user,
           p.nama_paket, 
           kls.nama_kelas,
           s.nama_supir,
           s.no_hp AS hp_supir,
           m.merk_mobil,
           m.plat,
           CASE 
               WHEN b.id_paket IN (1, 2) THEN pr.kota_tujuan
               WHEN b.id_paket = 3 THEN k.kota
               ELSE p.nama_paket 
           END AS tujuan_tampil,
           CASE 
               WHEN b.id_paket IN (1, 2) THEN pr.kota_asal
               ELSE 'Medan' 
           END AS asal_tampil
    FROM booking b
    LEFT JOIN users u ON b.id_user = u.id
    LEFT JOIN paket p ON b.id_paket = p.id
    LEFT JOIN paket_rute pr ON b.id_paket = pr.id_paket
    LEFT JOIN kota k ON b.id_kota_tujuan = k.id
    LEFT JOIN kelas kls ON b.id_kelas = kls.id
    LEFT JOIN supir s ON b.id_supir = s.id
    LEFT JOIN mobil m ON b.id_mobil = m.id
    WHERE b.id = '$id_booking' AND b.id_user = '$id_user' AND b.status = 'paid'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Tiket tidak ditemukan atau belum dibayar.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tiket_#SN-<?= $data['id']; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {

            /* Sembunyikan semua elemen di body */
            body * {
                visibility: hidden;
            }

            /* Tampilkan kembali hanya kontainer tiket dan isinya */
            #printable-ticket,
            #printable-ticket * {
                visibility: visible;
            }

            /* Atur posisi tiket agar berada di pojok kiri atas kertas saat diprint */
            #printable-ticket {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                border: 2px dashed #000 !important;
                /* Pastikan garis putus-putus muncul */
                box-shadow: none !important;
                margin: 0 !important;
                border-radius: 0 !important;
                /* Opsional: kotak sempurna saat diprint */
            }

            /* Sembunyikan elemen dengan class no-print secara eksplisit */
            .no-print {
                display: none !important;
            }

            /* Hilangkan margin default browser */
            @page {
                margin: 0.5cm;
            }
        }

        .font-mono {
            font-family: 'Courier New', Courier, monospace;
        }
    </style>
</head>

<body class="bg-gray-100 p-4 md:p-10 font-sans">

    <div class="max-w-2xl mx-auto mb-6 no-print flex justify-between items-center">
        <a href="order.php" class="text-sm font-bold text-gray-500 hover:text-black">← Kembali</a>
        <button onclick="window.print()" class="bg-black text-white px-6 py-2 rounded-full font-bold text-sm uppercase tracking-widest hover:bg-yellow-400 hover:text-black transition-all">Cetak Sekarang</button>
    </div>

    <div id="printable-ticket" class="max-w-2xl mx-auto bg-white border-2 ticket-border rounded-3xl overflow-hidden shadow-2xl">

        <div class="bg-black p-8 text-white flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-black italic tracking-tighter uppercase">CV. SINAR <span class="text-yellow-400">TAXI</span></h1>
                <p class="text-[10px] text-gray-400 uppercase tracking-[0.3em]">Official Boarding Pass</p>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-bold uppercase text-gray-400">ID Pesanan</p>
                <p class="text-xl font-mono font-bold text-yellow-400">#SN-<?= $data['id']; ?></p>
            </div>
        </div>

        <div class="p-10 space-y-8 relative">
            <div class="grid grid-cols-2 gap-10">
                <div class="col-span-2 flex justify-between items-center border-b pb-6">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Berangkat Dari</p>
                        <p class="text-2xl font-black uppercase italic"><?= $data['asal_tampil']; ?></p>
                    </div>
                    <div class="text-yellow-500 text-3xl font-black italic">→</div>
                    <div class="text-right">
                        <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Tujuan</p>
                        <p class="text-2xl font-black uppercase italic text-gray-900"><?= $data['tujuan_tampil']; ?></p>
                    </div>
                </div>

                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Nama Penumpang</p>
                    <p class="font-bold text-lg uppercase"><?= $data['nama_penumpang']; ?></p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Tipe Layanan</p>
                    <p class="font-bold text-lg uppercase italic text-yellow-600"><?= $data['nama_kelas']; ?></p>
                </div>

                <div class="col-span-2 grid grid-cols-2 gap-10 bg-gray-50 p-6 rounded-2xl border border-gray-100">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Supir Anda</p>
                        <p class="font-black text-gray-900 uppercase italic leading-tight">
                            <?= $data['nama_supir'] ? $data['nama_supir'] : 'Menunggu Konfirmasi'; ?>
                        </p>
                        <?php if ($data['hp_supir']): ?>
                            <p class="text-[10px] font-mono text-gray-500 italic"><?= $data['hp_supir']; ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Armada & Plat</p>
                        <p class="font-black text-gray-900 uppercase italic leading-tight">
                            <?= $data['merk_mobil'] ? $data['merk_mobil'] : '-'; ?>
                        </p>
                        <p class="text-[10px] font-mono text-yellow-600 font-bold"><?= $data['plat'] ? $data['plat'] : 'TBA'; ?></p>
                    </div>
                </div>

                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Tanggal Keberangkatan</p>
                    <p class="font-bold"><?= date('d F Y', strtotime($data['tgl_berangkat'])); ?></p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Jam Boarding</p>
                    <p class="font-bold text-xl"><?= substr($data['jam_berangkat'], 0, 5); ?> WIB</p>
                </div>
            </div>

            <div class="pt-8 border-t-2 border-dashed flex justify-between items-end">
                <div class="space-y-1">
                    <p class="text-[10px] font-black text-gray-400 uppercase">Status Pembayaran</p>
                    <p class="text-green-600 font-black uppercase italic tracking-widest text-sm">LUNAS / PAID</p>
                </div>
                <div class="text-right">
                    <p class="font-mono text-xs text-gray-400 mb-1">SN-<?= md5($data['id']); ?></p>
                    <div class="bg-black text-white px-4 py-1 text-[10px] font-bold">VALID TICKET</div>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 p-6 text-[9px] text-gray-400 uppercase leading-relaxed text-center">
            Harap tunjukkan tiket ini kepada supir saat penjemputan • Datang 30 menit sebelum jadwal • CV. Sinar Taxi - Perjalanan Aman & Nyaman.
        </div>
    </div>
</body>

</html>