<?php
session_start();
include "../backend/config.php";
$users = mysqli_query($config, "SELECT * FROM users ORDER BY role DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sinar Taxi - Users</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#F3F4F6] font-sans">
    <?php include "../components/admin-nav.php"; ?>
    <main class="ml-64 p-8">
        <h3 class="text-2xl font-black italic uppercase mb-10">User <span class="text-yellow-500">Database.</span></h3>
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-gray-400 border-b italic text-[10px] uppercase font-black">
                        <th class="py-3">Nama</th>
                        <th class="py-3">Email</th>
                        <th class="py-3">Role</th>
                        <th class="py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($u = mysqli_fetch_assoc($users)): ?>
                    <tr class="border-b border-gray-50">
                        <td class="py-4 font-bold"><?= $u['nama'] ?></td>
                        <td class="py-4 text-gray-500"><?= $u['email'] ?></td>
                        <td class="py-4 uppercase font-black text-[9px] <?= $u['role'] == 'admin' ? 'text-red-500' : 'text-blue-500' ?>"><?= $u['role'] ?></td>
                        <td class="py-4 text-right">
                            <button class="text-red-500 hover:underline font-bold text-xs">Block</button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>