<?php
include "../backend/config.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$user = $_SESSION['nama'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://unpkg.com/alpinejs" defer></script>

    <style>
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
    </style>
</head>

<body>
    <nav class="glass-nav sticky top-0 z-50 border-b border-gray-100">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-10">
                    <div class="flex items-center space-x-3">
                        <div class="bg-yellow-400 p-2 rounded-xl shadow-lg shadow-yellow-400/20">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5H6.5C5.84 5 5.29 5.42 5.08 6.01L3 12V20C3 20.55 3.45 21 4 21H5C5.55 21 6 20.55 6 20V19H18V20C18 20.55 18.45 21 19 21H20C20.55 21 21 20.55 21 20V12L18.92 6.01ZM6.85 7H17.15L18.22 10.12H5.78L6.85 7ZM19 17H5V12H19V17Z" fill="#000000" />
                                <circle cx="7" cy="14.5" r="1.5" fill="#000000" />
                                <circle cx="17" cy="14.5" r="1.5" fill="#000000" />
                                <path d="M5 2L2 5M19 2L22 5" stroke="#000000" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </div>
                        <span class="text-xl font-black italic tracking-tighter text-gray-900">SINAR TAXI</span>
                    </div>
                    <?php
                    $current_page = basename($_SERVER['SCRIPT_NAME']);
                    ?>

                    <div class="hidden md:flex items-center space-x-8">
                        <a href="home.php"
                            class="nav-link <?= ($current_page == 'home.php') ? 'active text-gray-900' : 'text-gray-400' ?> text-xs font-black uppercase tracking-widest hover:text-gray-900">
                            Destinasi
                        </a>

                        <a href="order.php"
                            class="nav-link <?= ($current_page == 'order.php') ? 'active text-gray-900' : 'text-gray-400' ?> text-xs font-black uppercase tracking-widest hover:text-gray-900">
                            Pesanan Aktif
                        </a>

                        <a href="profile.php"
                            class="nav-link <?= ($current_page == 'profile.php') ? 'active text-gray-900' : 'text-gray-400' ?> text-xs font-black uppercase tracking-widest hover:text-gray-900">
                            Profile
                        </a>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="hidden sm:block text-right">
                        <p class="text-sm font-black text-gray-900 leading-none"><?= $user ?> 👋</p>
                    </div>
                    <div class="relative" x-data="{ open: false }">

                        <!-- BUTTON -->
                        <button @click="open = !open"
                            class="w-11 h-11 rounded-2xl bg-gray-900 flex items-center justify-center border-2 border-white shadow-md overflow-hidden transition hover:scale-105">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($user) ?>&background=FACC15&color=000" alt="avatar">
                        </button>

                        <!-- DROPDOWN -->
                        <div x-show="open"
                            x-transition
                            @click.outside="open = false"
                            class="absolute right-0 mt-3 w-56 bg-white rounded-[1.5rem] shadow-2xl border border-gray-100 py-3 overflow-hidden"
                            style="display: none;">

                            <!-- USER INFO -->
                            <div class="px-4 py-3 border-b border-gray-50">
                                <p class="text-sm font-black text-gray-900"><?= $user ?></p>
                            </div>

                            <!-- MENU -->
                            <a href="profile.php"
                                class="flex items-center space-x-3 px-4 py-3 hover:bg-yellow-50 transition text-xs font-bold text-gray-700">
                                <span>👤</span> <span>Profil Saya</span>
                            </a>

                            <hr class="mx-4 my-2 border-gray-100">

                            <a href="../backend/logout.php"
                                class="flex items-center space-x-3 px-4 py-3 hover:bg-red-50 transition text-xs font-bold text-red-500">
                                <span>🚪</span> <span>Keluar Sistem</span>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </nav>
</body>

</html>