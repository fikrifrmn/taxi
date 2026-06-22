<?php
session_start();
include "../backend/config.php";

$querySupir = mysqli_query($config, "
    SELECT b.*, 
           u.nama, 
           p.nama_paket,
           CASE 
               WHEN b.id_paket IN (1, 2) THEN pr.kota_tujuan
               WHEN b.id_paket = 3 THEN k.kota
               ELSE 'Medan' 
           END AS tujuan_tampil
    FROM booking b 
    JOIN users u ON b.id_user = u.id 
    LEFT JOIN paket p ON b.id_paket = p.id
    LEFT JOIN paket_rute pr ON b.id_paket = pr.id_paket
    LEFT JOIN kota k ON b.id_kota_tujuan = k.id
    WHERE b.status = 'pending' AND b.id_supir IS NULL
");

$supirList = mysqli_fetch_all(mysqli_query($config, "SELECT * FROM supir"), MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Sinar Taxi - Penugasan Driver</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#F3F4F6] font-sans">
    <?php include "../components/admin-nav.php"; ?>
    <main class="pl-0 md:pl-64 w-full min-h-screen p-4 md:p-8 overflow-x-hidden">

        <div class="max-w-6xl mx-auto">

            <!-- Header -->
            <div class="mb-8">
                <h3 class="text-xl md:text-2xl font-black italic uppercase">
                    Step 1: <span class="text-yellow-500">Assign Driver.</span>
                </h3>
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">
                    Tentukan supir untuk pesanan masuk
                </p>
            </div>

            <!-- List -->
            <div class="space-y-4">
                <?php if (mysqli_num_rows($querySupir) > 0):
                    while ($book = mysqli_fetch_assoc($querySupir)): ?>

                        <div class="bg-white p-4 md:p-6 rounded-2xl shadow-sm flex flex-col md:flex-row md:items-center justify-between border border-gray-100 hover:shadow-md transition-all gap-4">

                            <!-- Info -->
                            <div>
                                <span class="text-[10px] font-black text-gray-400 uppercase">
                                    ORDER #SN-<?= $book['id'] ?>
                                </span>

                                <h4 class="text-lg md:text-xl font-black text-gray-800">
                                    <?= $book['nama'] ?>
                                </h4>

                                <p class="text-xs font-bold text-yellow-600 uppercase italic tracking-tight">
                                    Medan
                                    <span class="text-gray-400 mx-1">→</span>
                                    <?= $book['tujuan_tampil'] ?>
                                </p>
                            </div>

                            <!-- Form -->
                            <form action="../backend/confirm_driver.php" method="POST"
                                class="flex flex-col md:flex-row gap-3 w-full md:w-auto">

                                <input type="hidden" name="id_booking" value="<?= $book['id'] ?>">

                                <select name="id_supir" required
                                    class="w-full md:w-52 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm font-bold outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100 transition-all">

                                    <option value="" disabled selected>Pilih Supir</option>
                                    <?php foreach ($supirList as $s): ?>
                                        <option value="<?= $s['id'] ?>">
                                            <?= $s['nama_supir'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <button type="submit"
                                    class="w-full md:w-auto bg-black text-white hover:bg-yellow-400 hover:text-black px-6 py-2 rounded-xl text-[10px] font-black uppercase transition-all duration-300 shadow-md active:scale-95">

                                    Tugaskan Supir
                                </button>
                            </form>

                        </div>

                    <?php endwhile;
                else: ?>

                    <div class="flex flex-col items-center justify-center py-20 bg-white rounded-3xl border-2 border-dashed border-slate-200">
                        <p class="text-slate-400 font-bold italic uppercase">
                            Tidak ada antrean supir.
                        </p>
                    </div>

                <?php endif; ?>
            </div>

        </div>
    </main>

</body>

</html>