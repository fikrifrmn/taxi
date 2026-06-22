<?php
session_start();
include "../backend/config.php";

// Ambil data pembayaran pending
$queryBayar = mysqli_query($config, "SELECT b.id as id_booking, b.nama_penumpang, b.total_harga, 
                                    p.bukti_pembayaran, p.tgl_bayar, p.id as id_pembayaran,
                                    m.metode, m.nama_pemilik
                                    FROM booking b 
                                    JOIN pembayaran p ON b.id = p.id_booking 
                                    JOIN metode_pembayaran m ON p.id_metode = m.id
                                    JOIN supir s ON b.id_supir = s.id
                                    WHERE b.status = 'pending' 
                                    AND p.status_pembayaran = 'pending'");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sinar Taxi - Konfirmasi Bayar</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#F3F4F6] font-sans">

<?php include "../components/admin-nav.php"; ?>

<main class="pl-0 md:pl-64 w-full min-h-screen p-4 md:p-8 overflow-x-hidden">

    <div class="max-w-6xl mx-auto mb-6">
        <h3 class="text-2xl font-black uppercase">
            Step 2: <span class="text-green-500">Payment Confirm</span>
        </h3>
        <p class="text-slate-400 text-xs font-bold uppercase">
            Validasi pembayaran pelanggan
        </p>
    </div>

    <div class="grid gap-6">

    <?php if (mysqli_num_rows($queryBayar) > 0): 
        while($pay = mysqli_fetch_assoc($queryBayar)): ?>

        <!-- CARD LIST -->
        <div class="bg-white p-6 rounded-3xl shadow-sm flex justify-between items-center border">

            <div>
                <p class="text-xs text-gray-400 uppercase font-bold">Pelanggan</p>
                <h4 class="text-lg font-black"><?= $pay['nama_penumpang'] ?></h4>
                <p class="text-sm text-green-600 font-bold">
                    Rp <?= number_format($pay['total_harga'], 0, ',', '.') ?>
                </p>
            </div>

            <button onclick="openModal(<?= $pay['id_booking'] ?>)" 
            class="bg-blue-500 text-white px-6 py-3 rounded-xl text-xs font-bold uppercase">
                Review
            </button>
        </div>

        <!-- MODAL DETAIL -->
        <div id="modal-<?= $pay['id_booking'] ?>" 
        class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">

            <div class="bg-white w-full max-w-2xl p-6 rounded-3xl relative">

                <!-- CLOSE -->
                <button onclick="closeModal(<?= $pay['id_booking'] ?>)" 
                class="absolute top-4 right-4 text-gray-400 hover:text-black text-xl">
                    ✕
                </button>

                <!-- TITLE -->
                <h3 class="text-xl font-black mb-4">Review Pembayaran</h3>

                <!-- WARNING -->
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                    <p class="text-xs font-bold text-yellow-700 uppercase">
                        ⚠️ WAJIB: Periksa bukti pembayaran sebelum konfirmasi!
                    </p>
                </div>

                <!-- BUKTI BAYAR -->
                <div class="mb-4">
                    <p class="text-xs font-bold text-gray-400 uppercase mb-2">
                        Bukti Pembayaran
                    </p>
                    <a href="../assets/uploads/bukti/<?= $pay['bukti_pembayaran'] ?>" target="_blank">
                        <img src="../assets/uploads/bukti/<?= $pay['bukti_pembayaran'] ?>" 
                        class="w-full rounded-xl border shadow hover:scale-105 transition">
                    </a>
                </div>

                <!-- DETAIL -->
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-400 text-xs">Nama</p>
                        <p class="font-bold"><?= $pay['nama_penumpang'] ?></p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs">Nominal</p>
                        <p class="font-bold text-green-600">
                            Rp <?= number_format($pay['total_harga'], 0, ',', '.') ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs">Metode</p>
                        <p class="font-bold"><?= $pay['metode'] ?></p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs">Atas Nama</p>
                        <p class="font-bold"><?= $pay['nama_pemilik'] ?></p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-gray-400 text-xs">Tanggal Bayar</p>
                        <p class="font-bold">
                            <?= date('d M Y, H:i', strtotime($pay['tgl_bayar'])) ?>
                        </p>
                    </div>
                </div>

                <!-- ACTION -->
                <div class="flex justify-end gap-3 mt-6">

                    <!-- REJECT -->
                    <form action="../backend/confirm_payment.php" method="POST" 
                    onsubmit="return confirm('Tolak pembayaran ini?')">
                        <input type="hidden" name="id_booking" value="<?= $pay['id_booking'] ?>">
                        <input type="hidden" name="action" value="reject">
                        <button class="text-red-500 px-6 py-2 text-xs font-bold">
                            Tolak
                        </button>
                    </form>

                    <!-- APPROVE -->
                    <form action="../backend/confirm_payment.php" method="POST" 
                    onsubmit="return confirm('Sudah yakin bukti pembayaran valid?')">
                        <input type="hidden" name="id_booking" value="<?= $pay['id_booking'] ?>">
                        <input type="hidden" name="id_pembayaran" value="<?= $pay['id_pembayaran'] ?>">
                        <input type="hidden" name="action" value="approve">
                        <button class="bg-black text-white px-6 py-2 rounded-xl text-xs font-bold hover:bg-green-500">
                            Konfirmasi Lunas
                        </button>
                    </form>

                </div>

            </div>
        </div>

    <?php endwhile; else: ?>

        <div class="text-center py-20 bg-white rounded-3xl border-2 border-dashed">
            <p class="text-slate-400 font-bold uppercase">
                ☕ Semua beres! Tidak ada pembayaran pending.
            </p>
        </div>

    <?php endif; ?>

    </div>
</main>

<!-- SCRIPT MODAL -->
<script>
function openModal(id) {
    document.getElementById('modal-' + id).classList.remove('hidden');
}

function closeModal(id) {
    document.getElementById('modal-' + id).classList.add('hidden');
}
</script>

</body>
</html>
