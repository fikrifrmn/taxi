<?php
session_start();
include "../backend/config.php";

// Proteksi Admin
if (!isset($_SESSION['id'])) {
    header("Location: ../auth.php");
    exit;
}

// Ambil data filter (Tambahkan opsi 'all')
$filter_bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$filter_tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

// Siapkan variabel WHERE untuk Query
$whereClause = "WHERE 1=1"; // Default awal agar query tetap valid
if ($filter_bulan !== 'all') {
    $whereClause .= " AND MONTH(b.tgl_berangkat) = '$filter_bulan'";
}
if ($filter_tahun !== 'all') {
    $whereClause .= " AND YEAR(b.tgl_berangkat) = '$filter_tahun'";
}

// 1. Query Utama (Data Tabel)
$queryReport = mysqli_query($config, "
    SELECT b.*, u.nama, k.nama_kelas, ko.kota AS tujuan
    FROM booking b
    JOIN users u ON b.id_user = u.id
    LEFT JOIN kelas k ON b.id_kelas = k.id
    LEFT JOIN kota ko ON b.id_kota_tujuan = ko.id
    $whereClause
    ORDER BY b.tgl_berangkat DESC
");

// 2. Query Statistik (Perbaikan: Menghitung Revenue Sesuai Filter)
// Menggunakan Alias b agar konsisten dengan whereClause
$statsQuery = mysqli_query($config, "
    SELECT 
        COUNT(*) as total_order,
        SUM(CASE WHEN b.status = 'paid' THEN b.total_harga ELSE 0 END) as total_pendapatan
    FROM booking b
    $whereClause
");
$stats = mysqli_fetch_assoc($statsQuery);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sinar Taxi - Laporan Pendapatan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
</head>
<body class="bg-[#F8FAFC] antialiased">
    <?php include "../components/admin-nav.php"; ?>

    <main class="ml-64 p-10">
        <div class="flex justify-between items-start mb-10">
            <div>
                <h1 class="text-3xl font-black italic uppercase tracking-tighter">Financial <span class="text-yellow-400">Report.</span></h1>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-2">
                    Menampilkan: <?= $filter_bulan == 'all' ? 'Semua Bulan' : 'Bulan '.$filter_bulan ?> <?= $filter_tahun == 'all' ? 'Semua Tahun' : $filter_tahun ?>
                </p>
            </div>
            
            <button onclick="exportToExcel()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-2xl flex items-center gap-3 transition-all shadow-lg shadow-green-100 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span class="text-xs font-black uppercase tracking-wider">Unduh Excel</span>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Transaksi (Filter)</p>
                <h3 class="text-3xl font-black text-slate-800"><?= number_format($stats['total_order'], 0, ',', '.') ?> <span class="text-sm font-bold text-slate-300 ml-1 italic">Order</span></h3>
            </div>
            <div class="bg-slate-900 p-8 rounded-[2.5rem] shadow-xl shadow-slate-200">
                <p class="text-[10px] font-black text-yellow-400 uppercase tracking-widest mb-1">Total Revenue (Selesai)</p>
                <h3 class="text-3xl font-black text-white">Rp <?= number_format($stats['total_pendapatan'] ?? 0, 0, ',', '.') ?></h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 mb-6">
            <form method="GET" class="flex items-center gap-4">
                <div class="flex flex-col">
                    <label class="text-[9px] font-bold text-slate-400 uppercase ml-2 mb-1">Pilih Bulan</label>
                    <select name="bulan" class="bg-slate-50 border-none rounded-xl px-4 py-2 text-sm font-bold outline-none focus:ring-2 focus:ring-yellow-400">
                        <option value="all" <?= $filter_bulan == 'all' ? 'selected' : '' ?>>Semua Bulan</option>
                        <?php
                        $bulanArr = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                        foreach($bulanArr as $index => $nama) {
                            $val = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
                            echo "<option value='$val' ".($val == $filter_bulan ? 'selected' : '').">$nama</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="flex flex-col">
                    <label class="text-[9px] font-bold text-slate-400 uppercase ml-2 mb-1">Pilih Tahun</label>
                    <select name="tahun" class="bg-slate-50 border-none rounded-xl px-4 py-2 text-sm font-bold outline-none focus:ring-2 focus:ring-yellow-400">
                        <option value="all" <?= $filter_tahun == 'all' ? 'selected' : '' ?>>Semua Tahun</option>
                        <?php for($i=date('Y'); $i>=2024; $i--) echo "<option value='$i' ".($i==$filter_tahun?'selected':'').">$i</option>"; ?>
                    </select>
                </div>

                <button type="submit" class="self-end bg-yellow-400 text-black px-8 py-2.5 rounded-xl text-xs font-black uppercase hover:bg-black hover:text-white transition-all shadow-lg shadow-yellow-100">
                    Apply Filter
                </button>
            </form>
        </div>

        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
            <table id="reportTable" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400">ID Booking</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400">Tgl Berangkat</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400">Nama Pelanggan</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400">Rute & Kelas</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400">Total Harga</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if (mysqli_num_rows($queryReport) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($queryReport)): ?>
                        <tr class="hover:bg-slate-50/50 transition-all">
                            <td class="px-8 py-5 text-sm font-bold text-slate-400">#SN-<?= $row['id'] ?></td>
                            <td class="px-8 py-5 text-sm font-bold text-slate-700"><?= date('d M Y', strtotime($row['tgl_berangkat'])) ?></td>
                            <td class="px-8 py-5 text-sm font-black text-slate-800"><?= $row['nama'] ?></td>
                            <td class="px-8 py-5">
                                <span class="text-xs font-bold text-slate-500 block italic">Medan → <?= $row['tujuan'] ?></span>
                                <span class="text-[9px] font-black text-yellow-600 uppercase tracking-widest"><?= $row['nama_kelas'] ?></span>
                            </td>
                            <td class="px-8 py-5 text-sm font-black text-slate-800">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                            <td class="px-8 py-5 text-center">
                                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter <?= $row['status'] == 'paid' ? 'bg-green-100 text-green-600' : 'bg-orange-100 text-orange-600' ?>">
                                    <?= $row['status'] ?>
                                </span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center text-slate-300 font-bold uppercase italic tracking-widest">Data tidak ditemukan</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <script>
        function exportToExcel() {
            const table = document.getElementById("reportTable");
            const ws = XLSX.utils.table_to_sheet(table);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Laporan Sinar Taxi");
            
            // Nama file dinamis
            const bln = "<?= $filter_bulan ?>";
            const thn = "<?= $filter_tahun ?>";
            const fileName = `Report_SinarTaxi_${bln}_${thn}.xlsx`;
            
            XLSX.writeFile(wb, fileName);
        }
    </script>
</body>
</html>