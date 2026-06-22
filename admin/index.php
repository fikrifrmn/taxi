<?php
session_start();
include "../backend/config.php";

// Proteksi Admin
if (!isset($_SESSION['id'])) {
    header("Location: ../auth.php");
    exit;
}

// --- QUERY STATISTIK DINAMIS ---
$totalBooking = mysqli_fetch_assoc(mysqli_query($config, "SELECT COUNT(*) as total FROM booking"))['total'] ?? 0;
$totalPendapatan = mysqli_fetch_assoc(mysqli_query($config, "SELECT SUM(total_harga) as total FROM booking WHERE status = 'paid'"))['total'] ?? 0;
$totalUser = mysqli_fetch_assoc(mysqli_query($config, "SELECT COUNT(*) as total FROM users WHERE role = 'user'"))['total'] ?? 0;
$supirAktif = mysqli_fetch_assoc(mysqli_query($config, "SELECT COUNT(*) as total FROM supir"))['total'] ?? 0;

// Logika Chart (Contoh: Mengambil jumlah booking per hari dalam seminggu terakhir)
// Untuk sementara kita gunakan data dummy yang bisa kamu ganti dengan query GROUP BY tanggal
$chartData = [12, 19, 15, 8, 22, 30, 45]; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sinar Taxi - Overview</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#F3F4F6] antialiased">

    <?php include "../components/admin-nav.php"; ?>

    <main class="ml-64 p-8">
        
        <header class="flex justify-between items-center mb-10">
            <div>
                <h2 class="text-gray-400 text-xs font-black uppercase tracking-widest">Dashboard</h2>
                <h3 class="text-2xl font-black italic uppercase">Welcome Back, <span class="text-yellow-500">Chief.</span></h3>
            </div>
            <div class="flex items-center gap-4 text-black">
                <div class="text-right">
                    <p class="text-xs font-black uppercase"><?= $_SESSION['nama'] ?? 'Admin Sinar' ?></p>
                    <p class="text-[10px] text-green-500 font-bold uppercase italic">System Online</p>
                </div>
                <div class="w-12 h-12 bg-yellow-400 rounded-2xl flex items-center justify-center font-black shadow-lg shadow-yellow-400/20">A</div>
            </div>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 hover:border-yellow-400 transition-all group">
                <p class="text-gray-400 text-[10px] font-black uppercase mb-1">Total Booking</p>
                <h4 class="text-3xl font-extrabold group-hover:scale-105 transition-transform"><?= $totalBooking; ?></h4>
            </div>
            
            <div class="bg-black p-6 rounded-[2rem] shadow-xl shadow-black/10 border border-black hover:border-yellow-400 transition-all">
                <p class="text-gray-500 text-[10px] font-black uppercase mb-1">Revenue (Paid)</p>
                <h4 class="text-3xl font-extrabold text-yellow-400">Rp <?= number_format($totalPendapatan, 0, ',', '.'); ?></h4>
            </div>

            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 hover:border-yellow-400 transition-all">
                <p class="text-gray-400 text-[10px] font-black uppercase mb-1">Active Users</p>
                <h4 class="text-3xl font-extrabold"><?= $totalUser; ?></h4>
            </div>

            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 hover:border-yellow-400 transition-all">
                <p class="text-gray-400 text-[10px] font-black uppercase mb-1">Ready Drivers</p>
                <h4 class="text-3xl font-extrabold"><?= $supirAktif; ?></h4>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h4 class="font-black uppercase italic tracking-tighter">Weekly Fleet Activity</h4>
                    <span class="text-[10px] bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full font-bold">Real-time Data</span>
                </div>
                <canvas id="myChart" height="150"></canvas>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
                <h4 class="font-black uppercase italic tracking-tighter mb-6">System Logs</h4>
                <div class="space-y-6">
                    <?php
                    // Contoh mengambil 3 order terbaru untuk log
                    $recentOrders = mysqli_query($config, "SELECT * FROM booking ORDER BY id DESC LIMIT 3");
                    while($log = mysqli_fetch_assoc($recentOrders)):
                    ?>
                    <div class="flex gap-4 items-start">
                        <div class="w-2 h-2 mt-1.5 <?= $log['status'] == 'pending' ? 'bg-yellow-400' : 'bg-green-400' ?> rounded-full"></div>
                        <div>
                            <p class="text-xs font-bold text-gray-800">Order #SN-<?= $log['id'] ?> <?= $log['status'] ?></p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase"><?= date('H:i A', strtotime($log['tanggal_booking'] ?? 'now')) ?></p>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
                <a href="order_process.php" class="block w-full mt-8 text-center py-3 bg-gray-50 rounded-xl text-[10px] font-black uppercase hover:bg-yellow-400 transition-colors">View All Orders</a>
            </div>
        </div>
    </main>

    <script>
        const ctx = document.getElementById('myChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(250, 204, 21, 0.4)');
        gradient.addColorStop(1, 'rgba(250, 204, 21, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Bookings',
                    data: [<?= implode(',', $chartData); ?>],
                    borderColor: '#FACC15',
                    borderWidth: 4,
                    pointBackgroundColor: '#000',
                    pointBorderColor: '#FACC15',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        beginAtZero: true,
                        grid: { display: false },
                        ticks: { font: { weight: 'bold' } }
                    },
                    x: { grid: { display: false }, ticks: { font: { weight: 'bold' } } }
                }
            }
        });
    </script>
</body>
</html>