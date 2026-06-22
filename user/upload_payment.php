<?php
session_start();
include "../backend/config.php";

$id_booking = $_POST['id_booking'] ?? null;
$method_id  = $_POST['method_id'] ?? null;
$total_bayar = $_POST['total_harga'] ?? 0;

if (!$id_booking || !$method_id) {
    die("Data tidak lengkap");
}

// Ambil metode dari DB
$query = mysqli_query($config, "SELECT * FROM metode_pembayaran WHERE id = '$method_id'");
$method = mysqli_fetch_assoc($query);

if (!$method) {
    die("Metode tidak ditemukan");
}

$info = $method;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Bukti - Sinar Taxi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
</head>

<body class="bg-[#FBFBFB] font-sans min-h-screen">

    <main class="container mx-auto px-6 py-10">
        <div class="max-w-3xl mx-auto">

            <div class="text-center mb-10 animate__animated animate__fadeInDown">
                <h1 class="text-4xl font-black text-gray-900 italic tracking-tighter">SELESAIKAN <span class="text-yellow-500">PEMBAYARAN.</span></h1>
                <p class="text-gray-400 font-bold text-xs uppercase tracking-widest mt-2">Sisa waktu pembayaran: <span class="text-red-500">23:59:59</span></p>
            </div>

            <!-- Instruksi Pembayaran -->
            <div class="bg-black rounded-[2.5rem] p-8 text-white mb-8 shadow-2xl animate__animated animate__fadeIn">
                <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                    <div>
                        <p class="text-[10px] font-black text-yellow-400 uppercase tracking-[0.2em] mb-2">Transfer Ke <?= $info['metode'] ?></p>
                        <h2 class="text-2xl font-black italic mb-1"><?= $info['nomor_rekening'] ?></h2>
                        <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">A/N <?= $info['nama_pemilik'] ?></p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Total Nominal</p>
                        <p class="text-3xl font-black text-yellow-400 italic">Rp <?= number_format($total_bayar, 0, ',', '.') ?></p>
                    </div>
                </div>
            </div>

            <!-- Form Upload -->
            <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100 animate__animated animate__fadeInUp">
                <form action="../backend/proceed_payment.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                    <input type="hidden" name="id_booking" value="<?= $id_booking ?>">
                    <input type="hidden" name="method_id" value="<?= $method_id ?>">
                    <input type="hidden" name="total" value="<?= $total_bayar ?>">


                    <div class="border-4 border-dashed border-gray-50 rounded-[2rem] p-10 text-center hover:border-yellow-400 transition-all group">
                        <div class="mb-4 flex justify-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center group-hover:bg-yellow-100 transition-colors">
                                <svg class="w-8 h-8 text-gray-300 group-hover:text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                        <label class="cursor-pointer">
                            <span class="block text-sm font-black text-gray-900 italic">Klik untuk pilih foto bukti transfer</span>
                            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Format: JPG, PNG (Maks 2MB)</span>
                            <input type="file" name="bukti_pembayaran" class="hidden" required accept="image/*" onchange="previewImage(this)">
                        </label>
                    </div>

                    <!-- Preview Area -->
                    <div id="preview-container" class="hidden">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Preview Foto:</p>
                        <img id="image-preview" src="#" alt="Preview" class="w-full h-48 object-contain rounded-2xl border border-gray-100">
                    </div>

                    <button type="submit" class="w-full bg-yellow-400 hover:bg-black text-black hover:text-white py-6 rounded-3xl font-black uppercase tracking-[0.2em] text-xs transition-all duration-300 shadow-xl shadow-yellow-400/20">
                        Konfirmasi Pembayaran
                    </button>
                </form>
            </div>
        </div>
    </main>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('image-preview');
            const container = document.getElementById('preview-container');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>

</html>