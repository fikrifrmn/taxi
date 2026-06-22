<?php
session_start();
include "../backend/config.php";

// Proteksi Admin
if (!isset($_SESSION['id'])) {
    header("Location: ../auth.php");
    exit;
}

// Ganti query lama dengan ini
$mobil = mysqli_query($config, "
    SELECT mobil.*, kelas.nama_kelas 
    FROM mobil 
    JOIN kelas ON mobil.id_kelas = kelas.id 
    ORDER BY mobil.id DESC
");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sinar Taxi - Manajemen Armada</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-[#F8FAFC] antialiased text-slate-900" x-data="{ 
        openAdd: false, 
        openEdit: false, 
        openDelete: false, 
        editData: {id: '', merk: '', plat: '', id_kelas: ''},
        deleteUrl: '',
        
        initAdd() {
            this.editData = {id: '', merk: '', plat: ''}; 
            this.openAdd = true;
        },
        initDelete(id) {
            this.deleteUrl = '../backend/armada_action.php?delete=' + id;
            this.openDelete = true;
        }
      }">

    <?php include "../components/admin-nav.php"; ?>

    <main class="ml-64 p-10">

        <div class="flex justify-between items-end mb-10">
            <div>
                <h1 class="text-3xl font-black italic tracking-tighter uppercase leading-none">
                    Jadwal & <span class="text-yellow-400">Armada.</span>
                </h1>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-2">Pusat Kendali Inventaris Kendaraan</p>
            </div>

            <button @click="initAdd()"
                class="group bg-slate-900 hover:bg-yellow-400 text-white hover:text-black transition-all duration-300 px-7 py-3.5 rounded-2xl flex items-center gap-3 shadow-xl shadow-slate-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                </svg>
                <span class="text-xs font-extrabold uppercase tracking-wider">Tambah Mobil</span>
            </button>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/60 overflow-hidden border border-white">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Unit Kendaraan</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Kelas</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Identitas / Plat</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php while ($m = mysqli_fetch_assoc($mobil)): ?>
                        <tr class="group hover:bg-slate-50/80 transition-all duration-200">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-5">
                                    <div class="w-12 h-12 bg-slate-100 rounded-2xl flex items-center justify-center group-hover:bg-yellow-100 transition-colors duration-300">
                                        <svg class="w-6 h-6 text-slate-400 group-hover:text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="block font-bold text-slate-800 text-lg tracking-tight"><?= $m['merk_mobil'] ?></span>
                                        <span class="text-[10px] font-bold text-green-500 uppercase tracking-widest">Active Unit</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider 
                                    <?= $m['nama_kelas'] == 'Premium' ? 'bg-purple-100 text-purple-600' : 'bg-blue-100 text-blue-600' ?>">
                                    <?= $m['nama_kelas'] ?>
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <span class="font-mono bg-slate-100 text-slate-600 px-4 py-2 rounded-xl text-sm font-bold border border-slate-200/50 shadow-sm">
                                    <?= $m['plat'] ?>
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex justify-center gap-3">
                                    <button @click="openEdit = true; editData = {id: '<?= $m['id'] ?>', merk: '<?= $m['merk_mobil'] ?>', plat: '<?= $m['plat'] ?>', id_kelas: '<?= $m['id_kelas'] ?>'}"
                                        class="p-3 bg-blue-50 text-blue-600 rounded-2xl hover:bg-blue-600 hover:text-white hover:shadow-lg hover:shadow-blue-200 transition-all duration-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>

                                    <button @click="initDelete('<?= $m['id'] ?>')"
                                        class="p-3 bg-red-50 text-red-600 rounded-2xl hover:bg-red-600 hover:text-white hover:shadow-lg hover:shadow-red-200 transition-all duration-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                                <div x-show="openDelete"
                                    class="fixed inset-0 z-[110] flex items-center justify-center p-6 bg-slate-900/60 backdrop-blur-md"
                                    x-transition x-cloak>
                                    <div class="bg-white w-full max-w-sm rounded-[2.5rem] p-8 shadow-3xl text-center" @click.away="openDelete = false">
                                        <div class="w-20 h-20 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        </div>
                                        <h4 class="text-xl font-black uppercase tracking-tighter mb-2">Hapus Armada?</h4>
                                        <p class="text-slate-500 text-sm mb-8">Data yang dihapus tidak dapat dikembalikan. Lanjutkan?</p>
                                        <div class="flex gap-3">
                                            <a :href="deleteUrl" class="flex-1 bg-red-600 text-white py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-red-700 transition-all shadow-lg shadow-red-200 text-center">
                                                Ya, Hapus
                                            </a>
                                            <button @click="openDelete = false" class="flex-1 bg-slate-100 text-slate-400 py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-slate-200 transition-all">
                                                Batal
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>

    <div x-show="openAdd || openEdit"
        class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-900/60 backdrop-blur-md"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        x-cloak>

        <div class="bg-white w-full max-w-md rounded-[3rem] p-10 shadow-3xl border border-white" @click.away="openAdd = false; openEdit = false">
            <div class="mb-8">
                <h4 class="text-2xl font-black italic uppercase tracking-tighter"
                    x-text="openAdd ? 'Tambah Armada' : 'Edit Armada'"></h4>
                <p class="text-slate-400 text-xs font-bold uppercase mt-1">Lengkapi informasi unit di bawah ini</p>
            </div>

            <form action="../backend/armada_action.php" method="POST" class="space-y-6">
                <input type="hidden" name="id" :value="editData.id">

                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Merk / Brand Kendaraan</label>
                    <input type="text" name="merk" x-model="editData.merk" required placeholder="Contoh: Toyota Innova"
                        class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl px-5 py-4 mt-2 focus:bg-white focus:border-yellow-400 outline-none transition-all font-bold text-slate-700">
                </div>

                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Nomor Plat Polisi</label>
                    <input type="text" name="plat" x-model="editData.plat" required placeholder="Contoh: BK 1234 ABC"
                        class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl px-5 py-4 mt-2 focus:bg-white focus:border-yellow-400 outline-none transition-all font-mono font-bold text-slate-700 uppercase">
                </div>
                <div class="mb-4">
                    <label class="block text-xs font-black uppercase mb-2">Pilih Kelas Armada</label>
                    <select name="id_kelas" x-model="editData.id_kelas" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold outline-none focus:border-yellow-400">
                        <option value="" disabled selected>-- Pilih Kelas --</option>
                        <?php
                        // Ambil data kelas langsung dari database
                        $kelasQuery = mysqli_query($config, "SELECT * FROM kelas");
                        while ($k = mysqli_fetch_assoc($kelasQuery)) {
                            echo "<option value='" . $k['id'] . "'>" . $k['nama_kelas'] . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="submit" :name="openAdd ? 'add' : 'update'"
                        class="flex-[2] bg-slate-900 text-white py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-yellow-400 hover:text-black transition-all shadow-lg">
                        Simpan Data
                    </button>
                    <button type="button" @click="openAdd = false; openEdit = false"
                        class="flex-1 bg-slate-100 text-slate-400 py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-slate-200 transition-all">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>