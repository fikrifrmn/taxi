<?php
session_start();
include "../backend/config.php";

// Proteksi Admin
if (!isset($_SESSION['id'])) {
    header("Location: ../auth.php");
    exit;
}

// 1. Hapus Data
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($config, "DELETE FROM metode_pembayaran WHERE id = $id");
    header("Location: payment_method.php");
}

// 2. Tambah / Edit Data
if (isset($_POST['save_payment'])) {
    $id = $_POST['id'];
    $metode = $_POST['metode'];
    $kategori = $_POST['kategori'];
    $nomor_rekening = $_POST['nomor_rekening'];
    $nama_pemilik = $_POST['nama_pemilik'];
    $status = $_POST['status'];
 
    $logo_name = $_POST['old_logo'] ?? ''; 

    if (isset($_FILES['logo_file']) && $_FILES['logo_file']['error'] == 0) {
        $target_dir = "../assets/payment/";
        $file_ext = pathinfo($_FILES["logo_file"]["name"], PATHINFO_EXTENSION);
        
        $new_filename = strtolower(str_replace(' ', '_', $metode)) . "_" . time() . "." . $file_ext;
        $target_file = $target_dir . $new_filename;

        if (move_uploaded_file($_FILES["logo_file"]["tmp_name"], $target_file)) {
            $logo_name = $new_filename;

            if (!empty($_POST['old_logo'])) {
                @unlink($target_dir . $_POST['old_logo']);
            }
        }
    }

    if ($id == "") {
        $query = "INSERT INTO metode_pembayaran (metode, kategori, nomor_rekening, nama_pemilik, logo, status) 
                  VALUES ('$metode', '$kategori', '$nomor_rekening', '$nama_pemilik', '$logo_name', '$status')";
    } else {
        $query = "UPDATE metode_pembayaran SET 
                  metode='$metode', kategori='$kategori', nomor_rekening='$nomor_rekening', 
                  nama_pemilik='$nama_pemilik', logo='$logo_name', status='$status' 
                  WHERE id=$id";
    }
    
    mysqli_query($config, $query);
    header("Location: payment_method.php");
}

$editData = null;
if (isset($_GET['edit'])) {
    $id_edit = $_GET['edit'];
    $res = mysqli_query($config, "SELECT * FROM metode_pembayaran WHERE id = $id_edit");
    $editData = mysqli_fetch_assoc($res);
}

$payments = mysqli_query($config, "SELECT * FROM metode_pembayaran ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sinar Taxi - Payment Methods</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                <h2 class="text-gray-400 text-xs font-black uppercase tracking-widest">Finance</h2>
                <h3 class="text-2xl font-black italic uppercase">Payment <span class="text-yellow-500">Methods.</span></h3>
            </div>
            <button onclick="openModal()" class="bg-black text-white px-6 py-3 rounded-2xl font-black uppercase text-xs tracking-wider hover:bg-yellow-400 hover:text-black transition-all shadow-lg shadow-black/10">
                + Add Method
            </button>
        </header>

        <!-- Form Tambah/Edit -->
        <div id="paymentModal" class="<?= isset($_GET['edit']) ? 'flex' : 'hidden' ?> fixed inset-0 bg-black/50 z-50 items-center justify-center backdrop-blur-sm">
            <div class="bg-white w-full max-w-md p-8 rounded-[2.5rem] shadow-2xl relative">
                <h4 class="text-xl font-black uppercase italic mb-6"><?= $editData ? 'Edit' : 'Add New' ?> Method</h4>
                <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                    <input type="hidden" name="id" value="<?= $editData['id'] ?? '' ?>">
                    <input type="hidden" name="old_logo" value="<?= $editData['logo'] ?? '' ?>">
                    
                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-400 ml-2">Method Name</label>
                        <input type="text" name="metode" value="<?= $editData['metode'] ?? '' ?>" required class="w-full bg-gray-100 border-none rounded-xl p-3 focus:ring-2 focus:ring-yellow-400 outline-none">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-400 ml-2">Category</label>
                            <select name="kategori" class="w-full bg-gray-100 border-none rounded-xl p-3 focus:ring-2 focus:ring-yellow-400 outline-none">
                                <option value="bank" <?= isset($editData) && $editData['kategori'] == 'bank' ? 'selected' : '' ?>>Bank Transfer</option>
                                <option value="ewallet" <?= isset($editData) && $editData['kategori'] == 'ewallet' ? 'selected' : '' ?>>E-Wallet</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-400 ml-2">Status</label>
                            <select name="status" class="w-full bg-gray-100 border-none rounded-xl p-3 focus:ring-2 focus:ring-yellow-400 outline-none">
                                <option value="active" <?= isset($editData) && $editData['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="inactive" <?= isset($editData) && $editData['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-400 ml-2">Account Number</label>
                        <input type="text" name="nomor_rekening" value="<?= $editData['nomor_rekening'] ?? '' ?>" required class="w-full bg-gray-100 border-none rounded-xl p-3 focus:ring-2 focus:ring-yellow-400 outline-none">
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-400 ml-2">Holder Name</label>
                        <input type="text" name="nama_pemilik" value="<?= $editData['nama_pemilik'] ?? '' ?>" required class="w-full bg-gray-100 border-none rounded-xl p-3 focus:ring-2 focus:ring-yellow-400 outline-none">
                    </div>

                    <!-- BAGIAN UPLOAD FILE -->
                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-400 ml-2">Logo Image</label>
                        <div class="flex items-center gap-4">
                            <?php if(isset($editData['logo'])): ?>
                                <img src="../assets/payment/<?= $editData['logo'] ?>" class="w-10 h-10 rounded-lg object-contain bg-gray-50">
                            <?php endif; ?>
                            <input type="file" name="logo_file" accept="image/*" class="text-xs file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-yellow-100 file:text-yellow-700 hover:file:bg-yellow-200">
                        </div>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="submit" name="save_payment" class="flex-1 bg-black text-yellow-400 font-black uppercase py-3 rounded-xl hover:bg-yellow-400 hover:text-black transition-colors">Save Data</button>
                        <a href="payment_method.php" class="flex-1 bg-gray-100 text-center text-gray-400 font-black uppercase py-3 rounded-xl hover:bg-red-500 hover:text-white transition-colors">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Display -->
        <div class="bg-white rounded-[2.5rem] overflow-hidden border border-gray-100 shadow-sm">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="p-6 text-[10px] font-black uppercase text-gray-400 tracking-widest">Method</th>
                        <th class="p-6 text-[10px] font-black uppercase text-gray-400 tracking-widest">Category</th>
                        <th class="p-6 text-[10px] font-black uppercase text-gray-400 tracking-widest">Account Info</th>
                        <th class="p-6 text-[10px] font-black uppercase text-gray-400 tracking-widest">Status</th>
                        <th class="p-6 text-[10px] font-black uppercase text-gray-400 tracking-widest text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php while($row = mysqli_fetch_assoc($payments)): ?>
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="p-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-gray-100 rounded-xl overflow-hidden flex items-center justify-center border border-gray-100">
                                    <?php 
                                    $pathLogo = "../assets/payment/" . $row['logo'];
                                    if (!empty($row['logo']) && file_exists($pathLogo)) : 
                                    ?>
                                        <img src="<?= $pathLogo ?>" alt="<?= $row['metode'] ?>" class="w-full h-full object-contain p-1">
                                    <?php else : ?>
                                        <span class="font-bold text-gray-400 uppercase text-xs">
                                            <?= substr($row['metode'], 0, 1) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <span class="font-bold text-gray-800"><?= $row['metode'] ?></span>
                            </div>
                        </td>
                        <td class="p-6">
                            <span class="text-[10px] font-bold uppercase px-3 py-1 rounded-full <?= $row['kategori'] == 'bank' ? 'bg-blue-100 text-blue-600' : 'bg-purple-100 text-purple-600' ?>">
                                <?= $row['kategori'] ?>
                            </span>
                        </td>
                        <td class="p-6">
                            <p class="text-sm font-bold text-gray-800"><?= $row['nomor_rekening'] ?></p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase"><?= $row['nama_pemilik'] ?></p>
                        </td>
                        <td class="p-6">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full <?= $row['status'] == 'active' ? 'bg-green-500' : 'bg-red-500' ?>"></div>
                                <span class="text-xs font-bold capitalize"><?= $row['status'] ?></span>
                            </div>
                        </td>
                        <td class="p-6">
                            <div class="flex justify-center gap-2">
                                <a href="?edit=<?= $row['id'] ?>" class="p-2 bg-gray-100 rounded-lg hover:bg-yellow-400 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Hapus metode ini?')" class="p-2 bg-gray-100 rounded-lg hover:bg-red-500 hover:text-white transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m4-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v2M19 7H5"></path></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    </main>

    <script>
        function openModal() {
            document.getElementById('paymentModal').classList.remove('hidden');
            document.getElementById('paymentModal').classList.add('flex');
        }
    </script>
</body>
</html>