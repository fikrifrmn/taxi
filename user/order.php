<?php
session_start();
include "../backend/config.php";

if (!isset($_SESSION['id'])) {
    header("Location: ../auth.php");
    exit;
}
if (!empty($_SESSION['booking_in_progress']) && $_SESSION['booking_in_progress'] === true) {
    if ($currentPage !== 'booking-form.php') {

        $paket = $_SESSION['booking_paket'] ?? '';

        header("Location: booking-form.php?paket=" . $paket);
        exit;
    }
}
$id_user = $_SESSION['id'];

$queryAktif = mysqli_query($config, "
    SELECT b.*, 
           p.nama_paket, 
           -- Ambil kota_tujuan dari paket_rute jika paket 1 atau 2
           -- Ambil kota dari tabel kota jika paket 3 (Custom)
           CASE 
               WHEN b.id_paket IN (1, 2) THEN pr.kota_tujuan
               WHEN b.id_paket = 3 THEN k.kota
               ELSE p.nama_paket 
           END AS tujuan_tampil,
           s.nama_supir,s.no_hp AS hp_supir,
           m.merk_mobil, m.plat,
           kls.nama_kelas
    FROM booking b
    LEFT JOIN paket p ON b.id_paket = p.id
    LEFT JOIN paket_rute pr ON b.id_paket = pr.id_paket -- Join lewat id_paket
    LEFT JOIN kota k ON b.id_kota_tujuan = k.id
    LEFT JOIN supir s ON b.id_supir = s.id
    LEFT JOIN mobil m ON b.id_mobil = m.id
    LEFT JOIN kelas kls ON b.id_kelas = kls.id
    WHERE b.id_user = '$id_user' 
    AND b.status != 'completed'
    AND CONCAT(b.tgl_berangkat, ' ', b.jam_berangkat) >= NOW()
    ORDER BY b.id DESC
");

$queryRiwayat = mysqli_query($config, "
    SELECT 
        b.*, 
        p.nama_paket, 
        kls.nama_kelas,
        CASE 
            WHEN b.id_paket IN (1, 2) THEN 
                (SELECT pr2.kota_tujuan 
                 FROM paket_rute pr2 
                 WHERE pr2.id_paket = b.id_paket 
                 LIMIT 1)
            WHEN b.id_paket = 3 THEN k.kota
            ELSE p.nama_paket 
        END AS tujuan_tampil
    FROM booking b
    LEFT JOIN paket p ON b.id_paket = p.id
    LEFT JOIN kota k ON b.id_kota_tujuan = k.id
    LEFT JOIN kelas kls ON b.id_kelas = kls.id
    WHERE b.id_user = '$id_user' 
    AND b.status IN ('paid', 'cancelled')
    ORDER BY b.id DESC
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

        @keyframes shimmer {
            100% {
                transform: translateX(300%);
            }
        }
    </style>
</head>

<body class="bg-[#FBFBFB] font-sans min-h-screen" x-data="{ tab: 'aktif' }"> <?php include "../components/nav.php"; ?>
    <main class="container mx-auto px-6 py-12">
        <div class="flex flex-col md:flex-row justify-between items-center mb-12 gap-6">
            <h1 class="text-4xl font-black italic tracking-tighter text-gray-900 uppercase">
                Perjalanan <span class="text-yellow-500">Saya.</span>
            </h1>
            <div class="bg-white p-1.5 rounded-2xl flex shadow-sm border border-gray-100">
                <button @click="tab = 'aktif'" :class="tab === 'aktif' ? 'bg-black text-white' : 'text-gray-400'" class="px-8 py-3 rounded-xl text-xs font-black uppercase tracking-widest transition-all duration-300">
                    Aktif
                </button>
                <button @click="tab = 'riwayat'" :class="tab === 'riwayat' ? 'bg-black text-white' : 'text-gray-400'" class="px-8 py-3 rounded-xl text-xs font-black uppercase tracking-widest transition-all duration-300">
                    Riwayat
                </button>
            </div>
        </div>

        <div x-show="tab === 'aktif'" class="animate__animated animate__fadeInUp space-y-8">
            <?php
            if (mysqli_num_rows($queryAktif) > 0):
                while ($dataAktif = mysqli_fetch_assoc($queryAktif)):
            ?>
                    <div class="bg-white rounded-[2.5rem] p-8 md:p-12 border border-gray-100 card-shadow relative overflow-hidden mb-6">
                        <div class="flex flex-col lg:flex-row justify-between gap-10 relative z-10">
                            <div class="space-y-6">
                                <div class="flex items-center space-x-4">
                                    <?php
                                        $status = $dataAktif['status'];

                                        $badgeClass = 'bg-gray-100 text-gray-600';

                                        if ($status == 'pending') {
                                            $badgeClass = 'bg-yellow-100 text-yellow-700';
                                        } elseif ($status == 'paid') {
                                            $badgeClass = 'bg-green-100 text-green-700';
                                        } elseif ($status == 'completed') {
                                            $badgeClass = 'bg-blue-100 text-blue-700';
                                        } elseif ($status == 'cancelled') {
                                            $badgeClass = 'bg-red-100 text-red-700';
                                        }
                                    ?>
                                    <span class="<?= $badgeClass ?> px-4 py-1.5 rounded-full text-[10px] font-black uppercase">
                                        <?= $status; ?>
                                    </span>
                                    <span class="text-gray-300 text-xs font-bold uppercase">ID: #SN-<?= $dataAktif['id']; ?></span>
                                </div>

                                <div class="space-y-2">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Rute Perjalanan</p>
                                    <h2 class="text-3xl font-black italic text-gray-900 uppercase tracking-tighter">
                                        Medan <span class="text-yellow-400">→ </span><?= $dataAktif['tujuan_tampil']; ?>
                                    </h2>
                                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest"><?= $dataAktif['nama_paket']; ?></p>
                                </div>

                                <div class="grid grid-cols-2 gap-8">
                                    <div>
                                        <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Tanggal & Waktu</p>
                                        <p class="font-black text-gray-900">
                                            <?= date('d M Y', strtotime($dataAktif['tgl_berangkat'])); ?> • <?= substr($dataAktif['jam_berangkat'], 0, 5); ?>
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Tipe Armada</p>
                                        <p class="font-black text-gray-900 italic"><?= $dataAktif['nama_kelas']; ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-[2rem] p-8 min-w-[320px] border border-gray-100">
                                <?php if ($dataAktif['id_supir']): ?>
                                    <div class="flex items-center space-x-4 mb-6">
                                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($dataAktif['nama_supir']); ?>&background=000&color=fff" class="w-16 h-16 rounded-2xl shadow-lg" alt="Driver">
                                        <div>
                                            <p class="text-lg font-black italic text-gray-900 leading-none"><?= $dataAktif['nama_supir']; ?></p>
                                            <p class="text-[10px] font-black text-yellow-600 uppercase mt-1 italic tracking-widest">Sinar Driver Elite</p>
                                        </div>
                                    </div>
                                    <div class="space-y-4">
                                        <div class="flex justify-between items-center bg-white/50 p-3 rounded-xl border border-gray-100">
                                            <span class="text-[10px] font-black text-gray-900 italic uppercase tracking-tighter">
                                                <span class="text-gray-400 not-italic mr-1">Armada:</span> <?= $dataAktif['merk_mobil']; ?>
                                            </span>
                                            <span class="text-[10px] font-black text-white bg-black px-2 py-1 rounded-md tracking-widest uppercase">
                                                <?= $dataAktif['plat']; ?>
                                            </span>
                                        </div>

                                        <?php
                                        $wa_number = preg_replace('/[^0-9]/', '', $dataAktif['hp_supir']);
                                        if (substr($wa_number, 0, 1) === '0') {
                                            $wa_number = '62' . substr($wa_number, 1);
                                        }
                                        ?>

                                        <a href="https://wa.me/<?= $wa_number ?>?text=Halo%20Pak%20<?= urlencode($dataAktif['nama_supir']) ?>%2C%20saya%20penumpang%20Sinar%20Taxi%20dengan%20ID%20Booking%20%23SN-<?= $dataAktif['id'] ?>"
                                            target="_blank"
                                            class="group relative flex items-center justify-center gap-3 w-full bg-[#25D366] text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-black hover:text-yellow-400 transition-all duration-500 shadow-[0_10px_20px_-10px_rgba(37,211,102,0.5)] hover:shadow-yellow-400/20 overflow-hidden">

                                            <div class="absolute inset-0 w-1/2 h-full bg-white/20 skew-x-[-25deg] -translate-x-full group-hover:animate-[shimmer_0.75s_infinite]"></div>

                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 fill-current" viewBox="0 0 24 24">
                                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.484 8.412 0 6.556-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.309 1.656zm6.224-3.92s.297.176.79.462c1.403.812 3.074 1.24 4.804 1.242 5.403 0 9.803-4.398 9.803-9.802 0-2.625-1.022-5.093-2.877-6.948-1.855-1.855-4.323-2.877-6.949-2.877-5.404 0-9.803 4.4-9.803 9.804 0 2.039.63 3.992 1.813 5.61l-.117-.188-1.07 3.909 4.009-1.052z" />
                                            </svg>

                                            <span class="relative z-10">Hubungi Driver</span>
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-10">
                                        <p class="text-gray-400 italic font-bold">Menunggu Konfirmasi Driver...</p>
                                        <p class="text-[9px] text-gray-300 uppercase mt-2">Pesanan Anda sedang diproses admin</p>
                                    </div>
                                <?php endif; ?>
                                <?php if ($dataAktif['status'] == 'pending'): ?>
                                    <form method="POST" action="../backend/cancel_booking.php"
                                        onsubmit="return confirm('Yakin ingin membatalkan booking ini?')">
                                        <input type="hidden" name="id_booking" value="<?= $dataAktif['id']; ?>">

                                        <button type="submit"
                                            class="w-full mt-6 bg-red-500 text-white py-3 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-black hover:text-yellow-400 transition-all duration-300">
                                            Batalkan Booking
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="absolute -right-10 -bottom-10 text-[15rem] font-black italic text-gray-50 opacity-50 pointer-events-none uppercase tracking-tighter">ACTIVE</div>
                    </div>
                <?php
                endwhile;
            else:
                ?>
                <div class="bg-white rounded-[2.5rem] p-20 text-center border border-dashed border-gray-200">
                    <p class="text-gray-400 font-bold uppercase tracking-widest">Tidak ada perjalanan aktif saat ini</p>
                </div>
            <?php endif; ?>
        </div>

        <div x-show="tab === 'riwayat'" x-cloak class="animate__animated animate__fadeInUp">
            <?php if (mysqli_num_rows($queryRiwayat) > 0): ?>
                <div class="grid grid-cols-1 gap-6 max-w-4xl mx-auto">
                    <?php while ($row = mysqli_fetch_assoc($queryRiwayat)): ?>
                        <div class="bg-white rounded-[2rem] p-6 md:p-8 flex flex-col md:flex-row justify-between items-center border border-gray-100 hover:border-yellow-200 transition-all card-shadow group relative overflow-hidden">

                            <div class="flex items-center gap-6 w-full md:w-auto">
                                <div class="bg-gray-50 p-4 rounded-2xl group-hover:bg-yellow-400 transition-colors shrink-0">
                                    <?php
                                    if ($row['status'] == 'selesai') echo '🏁';
                                    elseif ($row['status'] == 'paid') echo '💰';
                                    else echo '❌';
                                    ?>
                                </div>
                                <div>
                                    <h4 class="text-xl font-black italic text-gray-900 uppercase leading-tight">
                                        Medan → <?= $row['tujuan_tampil']; ?>
                                    </h4>
                                    <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mt-1">
                                        <?= date('d M Y', strtotime($row['tgl_berangkat'])); ?> • <?= $row['nama_kelas']; ?>
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between md:justify-end gap-6 w-full md:w-auto mt-6 md:mt-0 pt-4 md:pt-0 border-t md:border-none border-gray-50">
                                <div class="text-left md:text-right">
                                    <p class="text-[9px] font-black text-gray-400 uppercase">Total Biaya</p>
                                    <p class="font-black text-gray-900 italic text-lg">Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?></p>
                                </div>

                                <div class="flex items-center gap-3">
                                    <?php
                                    $statusClass = 'bg-gray-100 text-gray-600';
                                    if ($row['status'] == 'selesai') $statusClass = 'bg-blue-50 text-blue-600';
                                    if ($row['status'] == 'paid') { $statusClass = 'bg-green-500 text-white'; }
                                    if ($row['status'] == 'cancelled') $statusClass = 'bg-red-50 text-red-600';
                                    ?>
                                    <span class="<?= $statusClass; ?> px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest">
                                        <?= $row['status']; ?>
                                    </span>

                                    <?php if ($row['status'] == 'paid'): ?>
                                        <a href="print_ticket.php?id=<?= $row['id']; ?>" target="_blank"
                                            class="bg-black text-white p-3 rounded-xl hover:bg-yellow-400 hover:text-black transition-all shadow-lg shadow-black/5">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="py-20 text-center bg-white rounded-[2.5rem] border border-dashed border-gray-200">
                    <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">Belum ada riwayat perjalanan.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
    <?php include '../components/footer.php'; ?>
    <Script>
        window.onload = function() {
            const hash = window.location.hash;
            if (hash) {
                const element = document.querySelector(hash);
                if (element) {
                    setTimeout(() => {
                        element.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }, 500); // Delay sedikit agar render selesai
                }
            }
        };
    </Script>
</body>

</html>