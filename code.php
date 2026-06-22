<?php
session_start();
include "../backend/config.php";

if (!isset($_SESSION['id'])) {
    header("Location: ../auth.php");
    exit;
}
$id_user = $_SESSION['id'];
$queryAktif = mysqli_query($config, "
    SELECT * FROM booking 
    WHERE id_user = '$id_user' 
    AND status IN ('pending','confirmed')
    ORDER BY id DESC LIMIT 1
");

$dataAktif = mysqli_fetch_assoc($queryAktif);

$queryRiwayat = mysqli_query($config, "
    SELECT * FROM booking 
    WHERE id_user = '$id_user' 
    AND status = 'selesai'
    ORDER BY id DESC
");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perjalanan Saya - CV Sinar Taxi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
        .glass-nav {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
        }

        .tab-active {
            border-bottom: 4px solid #FACC15;
            color: #111827 !important;
        }

        .card-shadow {
            box-shadow: 0 20px 50px -12px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body class="bg-[#FBFBFB] font-sans min-h-screen" x-data="{ tab: 'aktif' }"> <?php include "../components/nav.php"; ?> <main class="container mx-auto px-6 py-12">
        <div class="flex flex-col md:flex-row justify-between items-center mb-12 gap-6">
            <h1 class="text-4xl font-black italic tracking-tighter text-gray-900 uppercase">Perjalanan <span class="text-yellow-500">Saya.</span></h1>
            <div class="bg-white p-1.5 rounded-2xl flex shadow-sm border border-gray-100"> <button @click="tab = 'aktif'" :class="tab === 'aktif' ? 'bg-black text-white' : 'text-gray-400'" class="px-8 py-3 rounded-xl text-xs font-black uppercase tracking-widest transition-all duration-300">Aktif (1)</button> <button @click="tab = 'riwayat'" :class="tab === 'riwayat' ? 'bg-black text-white' : 'text-gray-400'" class="px-8 py-3 rounded-xl text-xs font-black uppercase tracking-widest transition-all duration-300">Riwayat</button> </div>
        </div>
        <div x-show="tab === 'aktif'" class="animate__animated animate__fadeInUp">
            <?php if($dataAktif): ?>
            <div class="bg-white rounded-[2.5rem] p-8 md:p-12 border border-gray-100 card-shadow relative overflow-hidden">
                <div class="flex flex-col lg:flex-row justify-between gap-10 relative z-10">
                    <div class="space-y-6">
                        <div class="flex items-center space-x-4"> 
                            <?php if($dataAktif['status'] == 'pending'): ?>
                                <span class="bg-yellow-100 text-yellow-600 px-4 py-1.5 rounded-full text-[10px] font-black uppercase">
                                    Pending
                                </span>
                            <?php else: ?>
                                <span class="bg-green-100 text-green-600 px-4 py-1.5 rounded-full text-[10px] font-black uppercase">
                                    Confirmed
                                </span>
                            <?php endif; ?>
                            <span class="text-gray-300 text-xs font-bold uppercase">
                                ID: #SN-<?= $dataAktif['id']; ?>
                            </span>
                        </div>
                        <div class="space-y-2">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none">Rute Perjalanan</p>
                            <h2 class="text-3xl font-black italic text-gray-900 uppercase">
                                <?= $dataAktif['rute_asal']; ?> → Tujuan
                            </h2>
                        </div>
                        <div class="grid grid-cols-2 gap-8">
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Tanggal & Waktu</p>
                                <p class="font-black text-gray-900">
                                    <?= date('d M Y', strtotime($dataAktif['tgl_berangkat'])); ?> • 
                                    <?= $dataAktif['jam_berangkat']; ?>
                                </p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Tipe Armada</p>
                                <p class="font-black text-gray-900 italic">
                                    <?= $dataAktif['tipe']; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-[2rem] p-8 min-w-[320px] border border-gray-100">
                        <div class="flex items-center space-x-4 mb-6"> 
                            <img src="https://ui-avatars.com/api/?name=Irpan+Sinar&background=000&color=fff" class="w-16 h-16 rounded-2xl shadow-lg" alt="Driver">
                            <div>
                                <p class="text-lg font-black italic text-gray-900 leading-none">Irpan Saputra</p>
                                <p class="text-[10px] font-black text-yellow-600 uppercase mt-1 italic tracking-widest">Sinar Driver Elite</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="flex justify-between"> 
                                <span class="text-xs font-bold text-gray-400 uppercase">Kendaraan</span> 
                                <span class="text-xs font-black text-gray-900 italic uppercase">Toyota Avanza (Hitam)</span> </div>
                            <div class="flex justify-between"> <span class="text-xs font-bold text-gray-400 uppercase">Plat Nomor</span> <span class="text-xs font-black text-gray-900 uppercase tracking-widest">BK 1234 AB</span> </div> <button class="w-full bg-black text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-yellow-400 hover:text-black transition-all mt-4">Hubungi Driver</button>
                        </div>
                    </div>
                </div>
                <div class="absolute -right-10 -bottom-10 text-[15rem] font-black italic text-gray-50 opacity-50 pointer-events-none uppercase tracking-tighter">ACTIVE</div>
            </div>
        </div>
        <div x-show="tab === 'riwayat'" class="animate__animated animate__fadeInUp space-y-6">
            <div class="bg-white rounded-[2rem] p-6 md:px-10 flex flex-col md:flex-row justify-between items-center border border-gray-100 hover:border-yellow-200 transition-all card-shadow group">
                <div class="flex flex-col md:flex-row items-center gap-8 text-center md:text-left">
                    <div class="bg-gray-50 p-4 rounded-2xl group-hover:bg-yellow-400 transition-colors"> 🏁 </div>
                    <div>
                        <h4 class="text-xl font-black italic text-gray-900 uppercase">Medan → Parapat</h4>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">12 Feb 2026 • Toyota Innova Reborn</p>
                    </div>
                </div>
                <div class="flex items-center gap-10 mt-6 md:mt-0">
                    <div class="text-right">
                        <p class="text-[10px] font-black text-gray-400 uppercase">Biaya</p>
                        <p class="font-black text-gray-900 italic">Rp 150.000</p>
                    </div> <span class="bg-blue-50 text-blue-600 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest">Selesai</span>
                </div>
            </div>
            <div class="bg-white rounded-[2rem] p-6 md:px-10 flex flex-col md:flex-row justify-between items-center border border-gray-100 hover:border-yellow-200 transition-all card-shadow group">
                <div class="flex flex-col md:flex-row items-center gap-8 text-center md:text-left">
                    <div class="bg-gray-50 p-4 rounded-2xl group-hover:bg-yellow-400 transition-colors"> 🏔️ </div>
                    <div>
                        <h4 class="text-xl font-black italic text-gray-900 uppercase">Medan → Berastagi</h4>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">28 Jan 2026 • Premium Hiace</p>
                    </div>
                </div>
                <div class="flex items-center gap-10 mt-6 md:mt-0">
                    <div class="text-right">
                        <p class="text-[10px] font-black text-gray-400 uppercase">Biaya</p>
                        <p class="font-black text-gray-900 italic">Rp 80.000</p>
                    </div> <span class="bg-blue-50 text-blue-600 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest">Selesai</span>
                </div>
            </div>
            <p class="text-center text-[10px] font-black text-gray-300 uppercase tracking-widest pt-10">Menampilkan 2 perjalanan terakhir</p>
        </div>
    </main>
    <footer class="py-20 text-center">
        <p class="text-[9px] font-bold text-gray-300 uppercase tracking-[0.4em]">© 2026 CV. SINAR TAXI • MANAGE YOUR TRIP</p>
    </footer>
</body>
<?php else: ?>
    <p class="text-center text-gray-400">Tidak ada perjalanan aktif</p>
<?php endif; ?>
</html>