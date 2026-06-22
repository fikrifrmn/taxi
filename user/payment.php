<?php
include "../backend/config.php";
session_start();

$id_booking = $_GET['id_booking'] ?? null;

if (!$id_booking) {
    header("Location: home.php"); 
    exit();
}

$query = mysqli_query($config, "SELECT b.*, p.nama_paket 
                                FROM booking b 
                                JOIN paket p ON b.id_paket = p.id 
                                WHERE b.id = '$id_booking'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Data booking tidak ditemukan.";
    exit();
}

$total_bayar = $data['total_harga'];
$nama_paket = $data['nama_paket'];
$order_id = "STX-" . $id_booking . "-" . time();

$query_methods = mysqli_query($config, "SELECT * FROM metode_pembayaran WHERE status = 'active'");
$grouped_methods = [];

while ($row = mysqli_fetch_assoc($query_methods)) {
    $cat_label = $row['kategori'];
    if ($cat_label == 'transfer') $cat_label = 'Bank Transfer';
    if ($cat_label == 'ewallet') $cat_label = 'E-Wallet';
    if ($cat_label == 'qris') $cat_label = 'QRIS';
    
    $grouped_methods[$cat_label][] = $row;
}

// Helper untuk style kategori sesuai desain premium
function getCategoryStyle($kategori) {
    switch (strtolower($kategori)) {
        case 'bank transfer': return 'text-blue-600 bg-blue-50 border-blue-100';
        case 'e-wallet': return 'text-pink-600 bg-pink-50 border-pink-100';
        case 'qris': return 'text-teal-600 bg-teal-50 border-teal-100';
        default: return 'text-gray-600 bg-gray-50 border-gray-100';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Sinar Taxi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        .category-trigger[aria-expanded="true"] .chevron-icon { transform: rotate(180deg); }
        .category-content { 
            display: grid;
            grid-template-rows: 0fr;
            transition: grid-template-rows 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .category-trigger[aria-expanded="true"] + .category-content { grid-template-rows: 1fr; }
        .inner-content { overflow: hidden; }

        .payment-radio:checked + .payment-card { 
            border-color: #FACC15; 
            background-color: #FFFDF2;
            transform: scale(1.01);
        }
        .payment-radio:checked + .payment-card .check-circle { background-color: #FACC15; border-color: #FACC15; }
        .payment-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        
        .custom-shadow { shadow-lg shadow-gray-200/50; }
    </style>
</head>

<body class="bg-[#FBFBFD] text-gray-900 min-h-screen">

    <main class="max-w-6xl mx-auto px-4 py-8 md:py-12">
        <div class="grid lg:grid-cols-12 gap-8 items-start">
            
            <!-- LEFT: PAYMENT SELECTION -->
            <div class="lg:col-span-7 space-y-8">
                <header class="animate__animated animate__fadeIn">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="h-1 w-12 bg-yellow-400 rounded-full"></div>
                        <span class="text-xs font-bold tracking-[0.3em] text-gray-400 uppercase">Secure Checkout</span>
                    </div>
                    <h1 class="text-4xl font-extrabold tracking-tighter">Pilih <span class="text-yellow-500 italic">Metode.</span></h1>
                    <p class="text-gray-400 font-bold text-[10px] mt-2 tracking-widest uppercase">Order Ref: <?= $order_id ?></p>
                </header>

                <form action="upload_payment.php" method="POST" class="space-y-6">
                    <input type="hidden" name="id_booking" value="<?= $id_booking ?>">
                    <input type="hidden" name="total_harga" value="<?= $total_bayar ?>">
                    
                    <div class="space-y-4 pr-1">
                        <?php foreach($grouped_methods as $kategori => $items): 
                            $catStyle = getCategoryStyle($kategori);
                        ?>
                            <div class="group border border-gray-100 bg-white rounded-[2rem] overflow-hidden shadow-sm transition-all hover:shadow-md">
                                <button type="button" onclick="toggleCategory(this)" aria-expanded="false" 
                                    class="category-trigger w-full px-6 py-5 flex justify-between items-center outline-none">
                                    <div class="flex items-center gap-3">
                                        <span class="px-3 py-1 rounded-full text-[10px] font-extrabold tracking-widest border <?= $catStyle ?>">
                                            <?= strtoupper($kategori) ?>
                                        </span>
                                    </div>
                                    <div class="chevron-icon transition-transform duration-500 bg-gray-50 p-2 rounded-full">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </button>

                                <div class="category-content">
                                    <div class="inner-content">
                                        <div class="px-6 pb-6 grid gap-3">
                                            <?php foreach($items as $method): ?>
                                                <label class="relative block group/item cursor-pointer">
                                                    <input type="radio" name="method_id" value="<?= $method['id'] ?>" class="payment-radio hidden" required>
                                                    <div class="payment-card bg-gray-50/50 border-2 border-transparent p-4 rounded-2xl flex items-center justify-between">
                                                        <div class="flex items-center gap-4">
                                                            <div class="w-12 h-12 rounded-xl bg-white shadow-sm flex items-center justify-center border border-gray-100 group-hover/item:rotate-3 transition-transform">
                                                                <?php 
                                                                $logo = isset($method['logo']) ? trim($method['logo']) : '';
                                                                if($logo !== ''): ?>
                                                                    <img src="../assets/payment/<?= htmlspecialchars($logo) ?>" class="w-8 h-8 object-contain">
                                                                <?php else: ?>
                                                                    <span class="text-[10px] font-black text-gray-400 uppercase"><?= substr($method['metode'], 0, 3) ?></span>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div>
                                                                <p class="font-bold text-gray-800 text-sm"><?= $method['metode'] ?></p>
                                                                <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-tight">AN/<?= $method['nama_pemilik'] ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="check-circle h-6 w-6 rounded-full border-2 border-gray-200 flex items-center justify-center transition-all">
                                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                                                        </div>
                                                    </div>
                                                </label>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="submit" class="group relative w-full bg-gray-900 text-white p-5 rounded-2xl font-bold overflow-hidden transition-all hover:bg-yellow-400 hover:text-black active:scale-[0.98] shadow-xl shadow-gray-200">
                        <span class="relative z-10 flex items-center justify-center gap-2 tracking-widest text-xs uppercase">
                            Konfirmasi Pembayaran
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </span>
                    </button>
                </form>
            </div>

            <!-- RIGHT: SUMMARY CARD -->
            <div class="lg:col-span-5 lg:sticky lg:top-12 mt-4 lg:mt-20">
                <div class="bg-white rounded-[2.5rem] p-2 border border-gray-100 shadow-2xl shadow-gray-200/50 animate__animated animate__fadeInRight">
                    <div class="bg-gray-50 rounded-[2.2rem] p-8">
                        <div class="flex justify-between items-start mb-8">
                            <div class="flex-1">
                                <h2 class="text-2xl font-extrabold italic leading-tight text-gray-900"><?= $nama_paket ?></h2>
                                <p class="text-[10px] font-bold text-yellow-600 uppercase mt-2 tracking-widest bg-yellow-50 inline-block px-2 py-1 rounded-md">Priority Booking</p>
                            </div>
                            <div class="bg-white p-3 rounded-2xl shadow-sm border border-gray-100 ml-4">
                                <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 24 24"><path d="M18.8 4H5.2c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h13.6c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm-5.8 11H7v-2h6v2zm3-4H7V9h9v2z"/></svg>
                            </div>
                        </div>

                        <div class="space-y-4 border-t border-dashed border-gray-300 pt-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500 font-medium">Subtotal</span>
                                <span class="font-bold text-gray-900">Rp <?= number_format($total_bayar, 0, ',', '.') ?></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500 font-medium">Biaya Layanan</span>
                                <span class="font-extrabold text-green-600 uppercase text-xs">Gratis</span>
                            </div>
                            
                            <div class="bg-white rounded-3xl p-6 mt-6 shadow-sm border border-gray-100 ring-4 ring-gray-50">
                                <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Total Pembayaran</p>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-sm font-bold text-yellow-500 uppercase">IDR</span>
                                    <span class="text-4xl font-black tracking-tighter text-gray-900"><?= number_format($total_bayar, 0, ',', '.') ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex items-center gap-3 px-2 border-t border-gray-200 pt-6">
                            <div class="flex -space-x-2">
                                <div class="w-6 h-6 rounded-full bg-blue-600 border-2 border-white shadow-sm"></div>
                                <div class="w-6 h-6 rounded-full bg-red-500 border-2 border-white shadow-sm"></div>
                                <div class="w-6 h-6 rounded-full bg-green-500 border-2 border-white shadow-sm"></div>
                            </div>
                            <p class="text-[9px] font-bold text-gray-400 leading-tight uppercase flex-1">Sistem terenkripsi & diawasi secara resmi</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <script>
        function toggleCategory(btn) {
            const isExpanded = btn.getAttribute('aria-expanded') === 'true';
            
            // Accordion Effect: Tutup kategori lain
            document.querySelectorAll('.category-trigger').forEach(otherBtn => {
                if (otherBtn !== btn) otherBtn.setAttribute('aria-expanded', 'false');
            });

            btn.setAttribute('aria-expanded', !isExpanded);
        }
    </script>
</body>
</html>