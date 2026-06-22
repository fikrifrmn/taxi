<?php $current_page = basename($_SERVER['PHP_SELF']); ?>
<aside class="fixed inset-y-0 left-0 bg-black w-64 transition-transform duration-300 z-50 overflow-y-auto">
    <div class="p-8">
        <h1 class="text-white text-2xl font-black italic tracking-tighter">SINAR <span class="text-yellow-400">TAXI.</span></h1>
        <p class="text-gray-500 text-[10px] uppercase font-bold tracking-widest mt-1">Management System</p>
    </div>

    <nav class="px-4 space-y-1">
        <!-- Dashboard Link -->
        <a href="index.php" class="w-full flex items-center gap-4 px-4 py-3 rounded-xl font-bold text-sm transition-all <?= ($current_page == 'index.php') ? 'bg-yellow-400 text-black' : 'text-gray-400 hover:text-white' ?>">
            Overview
        </a>

        <!-- Order Processing Section -->
        <div class="pt-4 pb-2">
            <p class="px-4 text-[10px] font-black text-gray-600 uppercase tracking-[0.2em] mb-2">Order Processing</p>
            
            <div class="relative ml-2 border-l border-gray-800 space-y-1">
                <!-- Step 1: Penentuan Supir -->
                <a href="order.php" class="group relative w-full flex items-center gap-3 pl-6 pr-4 py-3 rounded-r-xl font-bold text-sm transition-all <?= ($current_page == 'order.php') ? 'bg-yellow-400/10 text-yellow-400 border-l-2 border-yellow-400' : 'text-gray-400 hover:text-white' ?>">
                    <span class="absolute -left-[9px] w-4 h-4 rounded-full border-2 border-gray-800 bg-black flex items-center justify-center text-[9px] <?= ($current_page == 'order.php') ? 'border-yellow-400 text-yellow-400' : '' ?>">1</span>
                    Penentuan Supir
                </a>

                <!-- Step 2: Konfirmasi Bayar -->
                <a href="payment.php" class="group relative w-full flex items-center gap-3 pl-6 pr-4 py-3 rounded-r-xl font-bold text-sm transition-all <?= ($current_page == 'payment.php') ? 'bg-yellow-400/10 text-yellow-400 border-l-2 border-yellow-400' : 'text-gray-400 hover:text-white' ?>">
                    <span class="absolute -left-[9px] w-4 h-4 rounded-full border-2 border-gray-800 bg-black flex items-center justify-center text-[9px] <?= ($current_page == 'payment.php') ? 'border-yellow-400 text-yellow-400' : '' ?>">2</span>
                    Konfirmasi Bayar
                </a>
            </div>
        </div>

        <!-- Data Management Section -->
        <div class="pt-4 space-y-1">
            <p class="px-4 text-[10px] font-black text-gray-600 uppercase tracking-[0.2em] mb-2">Master Data</p>
            <a href="armada.php" class="w-full flex items-center gap-4 px-4 py-3 rounded-xl font-bold text-sm transition-all <?= ($current_page == 'armada.php') ? 'bg-yellow-400 text-black' : 'text-gray-400 hover:text-white' ?>">
                Jadwal & Armada
            </a>
            <a href="user.php" class="w-full flex items-center gap-4 px-4 py-3 rounded-xl font-bold text-sm transition-all <?= ($current_page == 'user.php') ? 'bg-yellow-400 text-black' : 'text-gray-400 hover:text-white' ?>">
                Manajemen User
            </a>
            <a href="payment_method.php" class="w-full flex items-center gap-4 px-4 py-3 rounded-xl font-bold text-sm transition-all <?= ($current_page == 'payment_method.php') ? 'bg-yellow-400 text-black' : 'text-gray-400 hover:text-white' ?>">
                Metode Pembayaran
            </a>
            <a href="report.php" class="w-full flex items-center gap-4 px-4 py-3 rounded-xl font-bold text-sm transition-all <?= ($current_page == 'report.php') ? 'bg-yellow-400 text-black' : 'text-gray-400 hover:text-white' ?>">
                Laporan
            </a>
        </div>

        <!-- Logout -->
        <div class="pt-10 border-t border-gray-800 mt-4">
            <a href="../backend/logout.php" class="w-full flex items-center gap-4 px-4 py-3 rounded-xl font-bold text-sm text-red-400 hover:bg-red-500 hover:text-white transition-all">
                Logout
            </a>
        </div>
    </nav>
</aside>