<?php
session_start();
$currentPage = basename($_SERVER['PHP_SELF']);
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
$query = "
SELECT 
    p.id,
    p.nama_paket,
    p.tipe,
    p.image,
    p.deskripsi,

    CASE 
        WHEN p.tipe = 'fixed' THEN (
            SELECT MIN(ph.harga)
            FROM paket_harga ph
            WHERE ph.id_paket = p.id
        )
        WHEN p.tipe = 'custom' THEN (
            SELECT MIN(hz.harga)
            FROM harga_zona hz
        )
    END as harga_mulai

FROM paket p
";
$result = mysqli_query($config, $query);
$queryAktif = mysqli_query($config, "
    SELECT b.*, 
           p.nama_paket, 
           s.nama_supir, s.no_hp AS hp_supir,
           m.merk_mobil, m.plat,
           CASE 
               WHEN b.id_paket IN (1, 2) THEN pr.kota_tujuan
               WHEN b.id_paket = 3 THEN k.kota
               ELSE p.nama_paket 
           END AS tujuan_tampil
    FROM booking b
    LEFT JOIN paket p ON b.id_paket = p.id
    LEFT JOIN paket_rute pr ON b.id_paket = pr.id_paket
    LEFT JOIN kota k ON b.id_kota_tujuan = k.id
    LEFT JOIN supir s ON b.id_supir = s.id
    LEFT JOIN mobil m ON b.id_mobil = m.id
    WHERE b.id_user = '$id_user' 
    AND CONCAT(b.tgl_berangkat, ' ', b.jam_berangkat) >= NOW()
    AND b.status != 'completed'
    ORDER BY CONCAT(b.tgl_berangkat, ' ', b.jam_berangkat) ASC
    LIMIT 1
");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Sinar Taxi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
        .destination-card {
            border-radius: 2.5rem;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .destination-card:hover {
            transform: translateY(-12px);
        }

        .glass-nav {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
        }

        .nav-link {
            position: relative;
            transition: all 0.3s;
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 100%;
            height: 2px;
            background: #FACC15;
            border-radius: 2px;
        }

        .destination-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
        }
    </style>
</head>

<body class="bg-[#FBFBFB] font-sans min-h-screen">

    <?php
    include "../components/nav.php";
    ?>

    <main class="container mx-auto px-6 py-10">

        <div class="mb-10 animate__animated animate__fadeInDown">
            <?php
            // Reset pointer result ke awal jika sebelumnya sudah digunakan dalam loop lain
            mysqli_data_seek($queryAktif, 0);

            if ($dataHighlight = mysqli_fetch_assoc($queryAktif)):
            ?>
                <div class="bg-black rounded-[2rem] p-6 text-white relative overflow-hidden flex flex-col md:flex-row justify-between items-center gap-6 shadow-xl shadow-black/10">
                    <div class="relative z-10 text-center md:text-left">
                        <span class="bg-yellow-400 text-black px-3 py-1 rounded-full text-[9px] font-black uppercase mb-3 inline-block tracking-widest">
                            Pesanan Aktif
                        </span>
                        <h2 class="text-xl md:text-2xl font-black italic tracking-tighter uppercase leading-tight">
                            Medan <span class="text-yellow-400">→</span> <?= $dataHighlight['tujuan_tampil']; ?>
                        </h2>
                        <p class="text-gray-400 text-[11px] mt-1 font-medium">
                            <?php if ($dataHighlight['id_supir']): ?>
                                Driver: <span class="text-white"><?= $dataHighlight['nama_supir']; ?></span> •
                                <?= $dataHighlight['merk_mobil']; ?> (<span class="text-yellow-400"><?= $dataHighlight['plat']; ?></span>)
                            <?php else: ?>
                                <span class="italic">Sedang mencari driver terbaik untuk Anda...</span>
                            <?php endif; ?>
                        </p>
                    </div>

                    <div class="relative z-10 flex items-center space-x-6">
                        <div class="text-right">
                            <p class="text-[9px] font-black text-yellow-400 uppercase tracking-[0.2em] mb-0.5">Penjemputan</p>
                            <p class="text-lg font-black italic"><?= substr($dataHighlight['jam_berangkat'], 0, 5); ?> <span class="text-xs not-italic text-gray-400">WIB</span></p>
                        </div>

                        <button onclick="window.location.href='order.php?id=<?= $dataHighlight['id']; ?>#SN-<?= $dataHighlight['id']; ?>'"
                            class="bg-white/10 hover:bg-yellow-400 hover:text-black backdrop-blur-md px-6 py-3 rounded-xl font-black text-[10px] transition-all duration-300 border border-white/10 uppercase tracking-widest">
                            Cek Detail
                        </button>
                    </div>

                    <div class="absolute right-0 top-0 h-full w-1/4 bg-yellow-400/5 skew-x-12 translate-x-12"></div>
                    <div class="absolute left-0 bottom-0 h-1 w-full bg-gradient-to-r from-yellow-400/50 to-transparent"></div>
                </div>
            <?php else: ?>
                <div class="bg-gray-100 rounded-[2rem] p-6 text-center border-2 border-dashed border-gray-200">
                    <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest">Siap untuk memulai perjalanan baru hari ini?</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 animate__animated animate__fadeIn">
            <div>
                <h1 class="text-4xl md:text-5xl font-black text-gray-900 italic tracking-tighter">PILIH <span class="text-yellow-500">RUTE ANDA.</span></h1>
                <p class="text-gray-500 font-medium mt-2">Semua paket sudah termasuk layanan antar jemput alamat.</p>
            </div>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="group relative bg-white rounded-[2.5rem] p-4 shadow-sm hover:shadow-2xl transition-all duration-500 border border-gray-100/50">
                    <div class="relative h-72 w-full overflow-hidden rounded-[2rem] mb-6">
                        <img src="<?= $row['image'] ?>"
                            class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700"
                            alt="<?= $row['nama_paket'] ?>">

                        <!-- <div class="absolute top-4 left-4">
                            <span class="bg-black/60 backdrop-blur-md text-white text-[10px] font-bold px-4 py-2 rounded-full uppercase tracking-widest border border-white/20">
                                Daily Trip
                            </span>
                        </div> -->

                        <div class="absolute bottom-4 right-4 bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-xl shadow-lg flex items-center gap-1">
                            <span class="text-yellow-500 text-xs">★</span>
                            <span class="text-[11px] font-black text-gray-800">4.9</span>
                        </div>
                    </div>

                    <div class="px-4 pb-4">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="text-2xl font-black text-gray-900 tracking-tight leading-tight">
                                    <?= $row['nama_paket'] ?>
                                </h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <svg class="w-3 h-3 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Antar Jemput Alamat</span>
                                </div>
                            </div>
                        </div>

                        <p class="text-gray-500 text-sm leading-relaxed line-clamp-2 mb-8 font-medium">
                            <?= $row['deskripsi'] ?>
                        </p>

                        <div class="flex items-center justify-between pt-6 border-t border-gray-50">
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-0.5">Mulai Dari</p>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-sm font-black text-gray-900">Rp</span>
                                    <span class="text-2xl font-black text-gray-900 tracking-tighter">
                                        <?= number_format($row['harga_mulai'], 0, ',', '.') ?>
                                    </span>
                                </div>
                            </div>

                            <a href="booking-form.php?paket=<?= $row['id'] ?>"
                                class="relative overflow-hidden group/btn bg-yellow-400 hover:bg-black text-black hover:text-white px-8 py-4 rounded-2xl transition-all duration-300 shadow-lg shadow-yellow-400/20 hover:shadow-black/20">
                                <span class="relative z-10 text-[11px] font-black uppercase tracking-widest">Booking</span>
                            </a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </main>

    <?php include '../components/footer.php'; ?>

</body>

</html>