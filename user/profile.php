<?php
session_start();
$currentPage = basename($_SERVER['PHP_SELF']);
include "../backend/config.php";

// Proteksi halaman
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

// Ambil data user dari database
$queryUser = mysqli_query($config, "SELECT * FROM users WHERE id = '$id_user'");
$userData = mysqli_fetch_assoc($queryUser);

// Ambil statistik ringkasan (opsional, biar gak sepi)
$queryCount = mysqli_query($config, "SELECT 
    (SELECT COUNT(*) FROM booking WHERE id_user = '$id_user' AND status = 'pending') as total_aktif,
    (SELECT COUNT(*) FROM booking WHERE id_user = '$id_user' AND status IN ('paid', 'selesai')) as total_riwayat
");
$stats = mysqli_fetch_assoc($queryCount);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - CV Sinar Taxi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
        .profile-card {
            box-shadow: 0 20px 50px -12px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="bg-[#FBFBFB] font-sans min-h-screen">

    <?php include "../components/nav.php"; ?>

    <main class="container mx-auto px-6 py-16">
        <div class="max-w-4xl mx-auto">
            
            <div class="mb-12 animate__animated animate__fadeIn">
                <h1 class="text-4xl font-black italic tracking-tighter text-gray-900 uppercase">
                    Profil <span class="text-yellow-500">Pengguna.</span>
                </h1>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mt-2">Informasi akun dan aktivitas Anda</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 animate__animated animate__fadeInUp">
                
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 profile-card text-center">
                        <div class="relative inline-block mb-6">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($userData['nama']); ?>&background=000&color=fff&size=128" 
                                 class="w-32 h-32 rounded-[2rem] shadow-xl border-4 border-white" alt="Avatar">
                            <div class="absolute -bottom-2 -right-2 bg-yellow-400 p-2 rounded-xl shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                        <h2 class="text-xl font-black italic text-gray-900 uppercase tracking-tight"><?= $userData['nama']; ?></h2>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1 italic">Sinar Taxi Member</p>
                        
                        <div class="mt-8 pt-8 border-t border-gray-50 flex justify-around">
                            <div class="text-center">
                                <p class="text-xl font-black italic text-gray-900"><?= $stats['total_aktif']; ?></p>
                                <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Pending</p>
                            </div>
                            <div class="text-center">
                                <p class="text-xl font-black italic text-gray-900"><?= $stats['total_riwayat']; ?></p>
                                <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Selesai</p>
                            </div>
                        </div>
                    </div>

                    <a href="../backend/logout.php" class="mt-6 flex items-center justify-center gap-3 w-full bg-red-50 text-red-500 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all duration-300">
                        Logout Akun
                    </a>
                </div>

                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[2.5rem] p-10 border border-gray-100 profile-card h-full">
                        <div class="space-y-8">
                            <div>
                                <label class="text-[9px] font-black text-gray-300 uppercase tracking-[0.2em] block mb-2">Nama Lengkap</label>
                                <p class="text-lg font-black italic text-gray-900 uppercase tracking-tight border-b border-gray-50 pb-2">
                                    <?= $userData['nama']; ?>
                                </p>
                            </div>

                            <div>
                                <label class="text-[9px] font-black text-gray-300 uppercase tracking-[0.2em] block mb-2">Email Address</label>
                                <p class="text-lg font-black italic text-gray-900 tracking-tight border-b border-gray-50 pb-2 lowercase">
                                    <?= $userData['email']; ?>
                                </p>
                            </div>

                            <div>
                                <label class="text-[9px] font-black text-gray-300 uppercase tracking-[0.2em] block mb-2">Nomor WhatsApp</label>
                                <p class="text-lg font-black italic text-gray-900 tracking-tight border-b border-gray-50 pb-2">
                                    <?= $userData['no_hp'] ?? '-'; ?>
                                </p>
                            </div>

                            <div class="pt-6">
                                <button class="bg-black text-white px-8 py-4 rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] hover:bg-yellow-400 hover:text-black transition-all duration-300 shadow-lg shadow-black/10">
                                    Edit Profil
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <?php include "../components/footer.php"; ?>

</body>
</html>