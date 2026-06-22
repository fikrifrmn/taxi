<?php
session_start();
include "../backend/config.php";
$_SESSION["booking_in_progress"] = true;
// ambil dari URL
if (isset($_GET['paket'])) {
    $_SESSION['booking_paket'] = $_GET['paket'];
    $_SESSION['booking_in_progress'] = true;
}

// fallback kalau reload
$id_paket = $_SESSION['booking_paket'] ?? null;

if (!isset($_SESSION['id'])) {
    header("Location: ../auth.php");
    exit;
}
$id_paket = isset($_GET['paket']) ? mysqli_real_escape_string($config, $_GET['paket']) : '';

$queryArmada = mysqli_query($config, "
    SELECT 
        m.id as id_mobil,
        m.merk_mobil,
        k.id as id_kelas,
        k.nama_kelas
    FROM mobil m
    JOIN kelas k ON m.id_kelas = k.id
    WHERE m.status = 'ready'
");

// Mengambil data paket spesifik untuk ditampilkan di ringkasan
$query = "SELECT * FROM paket WHERE id = '$id_paket'";
$result = mysqli_query($config, $query);
$paket = mysqli_fetch_assoc($result);
$harga_kelas = [];
$qHarga = mysqli_query($config, "
    SELECT id_kelas, harga 
    FROM paket_harga 
    WHERE id_paket = '$id_paket'
");
while ($h = mysqli_fetch_assoc($qHarga)) {
    $harga_kelas[$h['id_kelas']] = $h['harga'];
}

$harga_zona = [];
$qZona = mysqli_query($config, "
    SELECT id_zona, id_kelas, harga 
    FROM harga_zona
");

while ($z = mysqli_fetch_assoc($qZona)) {
    $harga_zona[$z['id_zona']][$z['id_kelas']] = $z['harga'];
}
$rute_data = [];
if ($paket['tipe'] == 'custom') {
    $query_rute = "SELECT id, kota, id_zona FROM kota";
    $result_rute = mysqli_query($config, $query_rute);

    while ($row = mysqli_fetch_assoc($result_rute)) {
        $rute_data[] = $row;
    }
}

// Jika paket tidak ditemukan, kembalikan ke dashboard
if (!$paket) {
    header("Location: home.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking <?= $paket['nama_paket'] ?> - Sinar Taxi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
        .input-focus {
            transition: all 0.3s ease;
        }

        .input-focus:focus {
            border-color: #FACC15;
            box-shadow: 0 0 0 4px rgba(250, 204, 21, 0.1);
            outline: none;
        }
    </style>
</head>

<body class="bg-[#FBFBFB] font-sans min-h-screen">

    <main class="container mx-auto px-6 py-10">
        <div class="max-w-5xl mx-auto">

            <div class="mb-10 animate__animated animate__fadeIn">
                <!-- <a href="home.php" class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-yellow-500 transition flex items-center gap-2 mb-4">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Dashboard
                </a> -->
                <h1 class="text-4xl font-black text-gray-900 italic tracking-tighter">KONFIRMASI <span class="text-yellow-500">PESANAN.</span></h1>
            </div>

            <div class="grid lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2 animate__animated animate__fadeInLeft">
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100">
                        <form action="../backend/proceed_booking.php" method="POST" class="space-y-6">
                            <input type="hidden" name="id_paket" value="<?= $paket['id'] ?>">
                            <input type="hidden" name="id_user" value="<?= $_SESSION['id'] ?>">
                            <input type="hidden" name="total_harga" id="inputHarga" value="0">
                            <input type="hidden" name="status" value="pending">
                            <input type="hidden" name="rute_asal" value="Medan">
                            <?php if ($paket['id'] != 3) { ?>
                                <input type="hidden" name="id_kota_tujuan" value="">
                            <?php } ?>

                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2">Nama Penumpang</label>
                                    <input type="text" name="nama_penumpang" required placeholder="Contoh: Budi Santoso"
                                        class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-bold input-focus">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2">Nomor WhatsApp</label>
                                    <input type="tel" name="no_hp" required placeholder="0812xxxx"
                                        class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-bold input-focus">
                                </div>
                            </div>

                            <!-- <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2">Tanggal Penjemputan</label>
                                    <input type="date" name="tgl_berangkat" required
                                        class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-bold input-focus">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2">Jam</label>
                                    <input type="time" name="jam_berangkat" required
                                        class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-bold input-focus">
                                </div>
                            </div> -->
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2">Tanggal Penjemputan</label>
                                    <input type="date" name="tgl_berangkat" id="tgl_berangkat" required
                                        class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-bold input-focus">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2">Pilihan Waktu Berangkat</label>
                                    <select name="jam_berangkat" id="jam_berangkat" required
                                        class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-bold input-focus">
                                        <option value="">-- Pilih Jam --</option>
                                        <option value="08:00">Malam (08:00 WIB)</option>
                                        <option value="16:00">Sore (16:00 WIB)</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2">Alamat Lengkap Penjemputan</label>
                                <textarea name="alamat" required rows="3" placeholder="Jl. Gatot Subroto No. 123..."
                                    class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-bold input-focus"></textarea>
                            </div>
                            <?php if ($paket['id'] == 3) { ?>
                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2">
                                        Pilih Rute Sumatera
                                    </label>
                                    <select name="id_kota_tujuan" id="ruteSelect"
                                        class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-bold input-focus">
                                        <option value="">-- Pilih Rute --</option>
                                        <?php foreach ($rute_data as $r) { ?>
                                            <option
                                                value="<?= $r['id'] ?>"
                                                data-zona="<?= $r['id_zona'] ?>">
                                                <?= $r['kota'] ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            <?php } ?>
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2">
                                    Pilih Jenis Armada
                                </label>

                                <select name="armada" id="armadaSelect" required
                                    class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-bold input-focus">

                                    <option value="">-- Pilih Armada --</option>

                                    <?php while ($a = mysqli_fetch_assoc($queryArmada)): ?>
                                        <option
                                            value="<?= $a['id_kelas']; ?>"
                                            data-kelas="<?= $a['id_kelas']; ?>">
                                            <?= $a['nama_kelas']; ?> - <?= $a['merk_mobil']; ?>
                                        </option>
                                    <?php endwhile; ?>

                                </select>
                            </div>
                            <button type="submit"
                                class="w-full bg-yellow-400 hover:bg-black text-black hover:text-white py-5 rounded-2xl font-black uppercase tracking-[0.2em] text-xs transition-all duration-300 shadow-xl shadow-yellow-400/20">
                                Proses Booking Sekarang
                            </button>
                        </form>
                        <form action="../backend/cancel_temp_booking.php" method="POST">
                            <button type="submit"
                                class="w-full bg-gray-300 hover:bg-black text-black hover:text-white py-5 rounded-2xl font-black uppercase tracking-[0.2em] text-xs transition-all duration-300 mt-4">
                                Cancel
                            </button>
                        </form>
                    </div>
                </div>

                <div class="lg:col-span-1 animate__animated animate__fadeInRight">
                    <div class="bg-black rounded-[2.5rem] p-8 text-white sticky top-10">
                        <span class="bg-yellow-400 text-black px-3 py-1 rounded-full text-[9px] font-black uppercase mb-6 inline-block">Ringkasan Perjalanan</span>

                        <div class="mb-6">
                            <h3 class="text-2xl font-black italic tracking-tight mb-2"><?= $paket['nama_paket'] ?></h3>
                            <p class="text-gray-400 text-xs leading-relaxed font-medium">
                                <?= $paket['deskripsi'] ?>
                            </p>
                        </div>

                        <div class="space-y-4 border-t border-white/10 pt-6">
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Harga Dasar</span>
                                <span id="hargaDasar" class="font-black">Rp 0</span>
                            </div>
                            <div class="flex justify-between items-center text-yellow-400">
                                <span class="text-[10px] font-bold uppercase tracking-widest">Layanan Alamat</span>
                                <span class="font-black text-xs uppercase italic">Gratis</span>
                            </div>
                            <div class="pt-4 border-t border-white/10 flex justify-between items-end">
                                <div>
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Total Bayar</p>
                                    <p id="totalHarga" class="text-2xl font-black text-white italic">Rp 0</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 bg-white/5 rounded-2xl p-4 border border-white/5">
                            <div class="flex gap-3 items-center">
                                <div class="bg-yellow-400/20 p-2 rounded-lg">
                                    <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <p class="text-[10px] text-gray-400 font-medium leading-tight">Konfirmasi booking akan dikirimkan langsung melalui WhatsApp setelah Anda menekan tombol proses.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <?php include '../components/footer.php'; ?>
    <script>
        const hargaKelas = <?= json_encode($harga_kelas); ?>;
        const hargaZona = <?= json_encode($harga_zona); ?>;

        const armadaSelect = document.getElementById('armadaSelect');
        const ruteSelect = document.getElementById('ruteSelect');
        const totalHargaText = document.getElementById('totalHarga');
        const hargaDasarText = document.getElementById('hargaDasar');
        const inputHargaHidden = document.getElementById('inputHarga');

        armadaSelect.addEventListener('change', updateHarga);
        if (ruteSelect) {
            ruteSelect.addEventListener('change', updateHarga);
        }

        function updateHarga() {
            let total = 0;
            let kelas = armadaSelect.value; // Ini mengambil id_kelas

            if (!kelas) {
                totalHargaText.innerText = "Rp 0";
                hargaDasarText.innerText = "Rp 0";
                return;
            }

            if (hargaKelas[kelas]) {
                total = parseInt(hargaKelas[kelas]) || 0; // Proteksi || 0
            }

            if (ruteSelect && ruteSelect.value) {
                let selected = ruteSelect.options[ruteSelect.selectedIndex];
                let zona = selected.getAttribute('data-zona');

                if (zona && hargaZona[zona] && hargaZona[zona][kelas]) {
                    total += parseInt(hargaZona[zona][kelas]) || 0;
                }
            }

            // Format angka ke Rupiah
            let format = new Intl.NumberFormat('id-ID').format(total);

            // Update tampilan
            totalHargaText.innerText = "Rp " + format;
            hargaDasarText.innerText = "Rp " + format;
            inputHargaHidden.value = total;
        }

        // Jalankan sekali saat halaman load untuk inisialisasi
        updateHarga();

        const inputTanggal = document.getElementById('tgl_berangkat');
        const selectJam = document.getElementById('jam_berangkat');

        // 1. Set minimal tanggal adalah hari ini
        const today = new Date();
        const yyyy = today.getFullYear();
        const mm = String(today.getMonth() + 1).padStart(2, '0');
        const dd = String(today.getDate()).padStart(2, '0');
        const minDate = `${yyyy}-${mm}-${dd}`;
        inputTanggal.setAttribute('min', minDate);

        // 2. Fungsi untuk memvalidasi jam berdasarkan tanggal yang dipilih
        function validateTimeSlots() {
            const selectedDate = inputTanggal.value;
            const now = new Date();
            const currentHour = now.getHours();

            const options = selectJam.options;

            for (let i = 0; i < options.length; i++) {
                const option = options[i];

                if (option.value === "") continue;

                // simpan label asli sekali saja
                if (!option.dataset.label) {
                    option.dataset.label = option.text;
                }

                const hourPart = parseInt(option.value.split(':')[0]);

                if (selectedDate === minDate) {
                    if (hourPart <= currentHour + 2) {
                        option.disabled = true;
                        option.text = option.dataset.label + " (Tidak Tersedia)";
                    } else {
                        option.disabled = false;
                        option.text = option.dataset.label; // RESET TEXT
                    }
                } else {
                    option.disabled = false;
                    option.text = option.dataset.label; // RESET TEXT
                }
            }
        }

        // Jalankan validasi saat tanggal diubah
        inputTanggal.addEventListener('change', function() {
            validateTimeSlots();
            // Reset pilihan jam jika jam yang dipilih sebelumnya jadi ter-disable
            if (selectJam.selectedOptions[0].disabled) {
                selectJam.value = "";
            }
        });

        // Jalankan saat pertama kali muat (jika ada value default)
        validateTimeSlots();
    </script>

</body>

</html>