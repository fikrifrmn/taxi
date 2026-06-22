<?php
session_start();
include "backend/config.php";

if (isset($_SESSION['id'])) {

    if ($_SESSION['role'] === 'admin') {
        header("Location: /cvsinartaxi/admin/index.php");
    } else {
        header("Location: /cvsinartaxi/user/home.php");
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Masuk & Daftar - CV Sinar Taxi</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    [x-cloak] {
      display: none !important;
    }

    .auth-card {
      border-radius: 3rem;
    }
  </style>
</head>

<body class="bg-gray-50 font-sans min-h-screen flex flex-col">

  <nav class="fixed w-full z-50 bg-white/80 backdrop-blur-md shadow-sm">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
      <div class="flex items-center space-x-2">
        <div class="bg-yellow-400 p-2 rounded-lg">
          <svg
            width="30"
            height="30"
            viewBox="0 0 24 24"
            fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path
              d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5H6.5C5.84 5 5.29 5.42 5.08 6.01L3 12V20C3 20.55 3.45 21 4 21H5C5.55 21 6 20.55 6 20V19H18V20C18 20.55 18.45 21 19 21H20C20.55 21 21 20.55 21 20V12L18.92 6.01ZM6.85 7H17.15L18.22 10.12H5.78L6.85 7ZM19 17H5V12H19V17Z"
              fill="#000000" />
            <circle cx="7" cy="14.5" r="1.5" fill="#000000" />
            <circle cx="17" cy="14.5" r="1.5" fill="#000000" />
            <path
              d="M5 2L2 5M19 2L22 5"
              stroke="#000000"
              stroke-width="2"
              stroke-linecap="round" />
          </svg>
        </div>
        <span class="text-xl font-black italic tracking-tighter">SINAR TAXI</span>
      </div>
      <a href="index.html" class="text-sm font-bold text-gray-700 hover:text-yellow-500 transition">← Kembali ke Beranda</a>
    </div>
  </nav>

  <main class="flex-grow flex items-center justify-center p-6 pt-28">
    <div class="fixed top-0 right-0 w-1/3 h-full bg-yellow-400 -skew-x-12 translate-x-1/2 -z-10"></div>

    <div
      class="bg-white w-full max-w-[480px] auth-card shadow-2xl shadow-gray-200/50 p-10 md:p-14 animate__animated animate__fadeInUp relative overflow-hidden"
      x-data="authPage()">
      <div class="flex mb-10 bg-gray-100 p-1.5 rounded-2xl">
        <button @click="isLogin = true" :class="isLogin ? 'bg-white text-black shadow-md' : 'text-gray-500'" class="flex-1 py-3 rounded-xl font-bold transition-all duration-300">MASUK</button>
        <button @click="isLogin = false" :class="!isLogin ? 'bg-white text-black shadow-md' : 'text-gray-500'" class="flex-1 py-3 rounded-xl font-bold transition-all duration-300">DAFTAR</button>
      </div>

      <div class="mb-8 text-center">
        <h2 class="text-3xl font-black text-gray-900 leading-tight" x-text="isLogin ? 'Selamat Datang Kembali!' : 'Buat Akun Baru.'"></h2>
        <p class="text-gray-500 mt-2 font-medium" x-text="isLogin ? 'Silahkan masuk ke akun Mitra Anda' : 'Lengkapi data untuk menikmati layanan kami'"></p>
      </div>

      <form action="backend/login.php" method="post" x-show="isLogin" x-cloak class="space-y-5">
        <div class="space-y-1">
          <label class="text-xs font-bold uppercase tracking-wider text-gray-400 ml-1">Email</label>
          <input type="email" name="email" required class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-yellow-400/20 focus:border-yellow-400 outline-none transition-all font-medium">
        </div>
        <div class="space-y-1">
          <label class="text-xs font-bold uppercase tracking-wider text-gray-400 ml-1">Password</label>
          <input type="password" name="password" required class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-yellow-400/20 focus:border-yellow-400 outline-none transition-all font-medium">
        </div>
        <button type="submit" class="w-full bg-black text-white py-4 rounded-2xl font-bold text-lg hover:bg-yellow-400 hover:text-black transition-all duration-300 shadow-xl flex items-center justify-center gap-3">
          <span x-show="!loading">MASUK SEKARANG</span>
          <div x-show="loading" class="w-6 h-6 border-4 border-white border-t-transparent rounded-full animate-spin"></div>
        </button>
      </form>

      <form action="backend/register.php" method="post" x-show="!isLogin" x-cloak class="space-y-4">
        <div class="space-y-1">
          <label class="text-xs font-bold uppercase tracking-wider text-gray-400 ml-1">Nama Lengkap</label>
          <input type="text" name="nama" required placeholder="Contoh: Budi Santoso" class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-yellow-400/20 focus:border-yellow-400 outline-none transition-all font-medium">
        </div>
        <div class="space-y-1">
          <label class="text-xs font-bold uppercase tracking-wider text-gray-400 ml-1">Nomor WhatsApp</label>
          <input type="tel" name="no_hp" required placeholder="0812..." class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-yellow-400/20 focus:border-yellow-400 outline-none transition-all font-medium">
        </div>
        <div class="space-y-1">
          <label class="text-xs font-bold uppercase tracking-wider text-gray-400 ml-1">Email</label>
          <input type="email" name="email" required class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-yellow-400/20 focus:border-yellow-400 outline-none transition-all font-medium">
        </div>
        <div class="space-y-1">
          <label class="text-xs font-bold uppercase tracking-wider text-gray-400 ml-1">Password</label>
          <input type="password" name="password" required class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-yellow-400/20 focus:border-yellow-400 outline-none transition-all font-medium">
        </div>
        <button type="submit" class="w-full bg-black text-white py-4 rounded-2xl font-bold text-lg hover:bg-yellow-400 hover:text-black transition-all duration-300 shadow-xl flex items-center justify-center gap-3 mt-4">
          <span x-show="!loading">DAFTAR AKUN</span>
          <div x-show="loading" class="w-6 h-6 border-4 border-white border-t-transparent rounded-full animate-spin"></div>
        </button>
      </form>

      <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-yellow-400/20 rounded-full"></div>
    </div>
  </main>

  <footer class="py-8 text-center text-gray-400 text-xs font-bold tracking-widest uppercase">
    © 2026 CV. SINAR TAXI • Premium Sumatera Travel
  </footer>
  <script>
    document.addEventListener('DOMContentLoaded', function() {

      const urlParams = new URLSearchParams(window.location.search);
      const msg = urlParams.get('msg');
      const error = urlParams.get('error');

      if (msg === 'registered') {
        Swal.fire({
          icon: 'success',
          title: 'Pendaftaran Berhasil!',
          text: 'Akun Sinar Taxi Anda sudah aktif. Silakan masuk.',
          confirmButtonColor: '#FACC15'
        });
      }

      if (msg === 'email_exists') {
        Swal.fire({
          icon: 'error',
          title: 'Email Sudah Terdaftar',
          text: 'Silakan gunakan email lain atau masuk ke akun Anda.',
          confirmButtonColor: '#000'
        });
      }

      if (msg === 'password') {
        Swal.fire({
          icon: 'error',
          title: 'Login Gagal',
          text: 'Password yang Anda masukkan salah.',
          confirmButtonColor: '#000'
        });
      }

      if (msg === 'email') {
        Swal.fire({
          icon: 'error',
          title: 'Login Gagal',
          text: 'Email tidak ditemukan.',
          confirmButtonColor: '#000'
        });
      }

      if (msg === 'error') {
        Swal.fire({
          icon: 'warning',
          title: 'Terjadi Kesalahan',
          text: 'Gagal memproses data. Coba lagi nanti.',
          confirmButtonColor: '#000'
        });
      }

      if (msg || error) {
        const newUrl = window.location.origin + window.location.pathname;
        window.history.replaceState({}, document.title, newUrl);
      }

    });

    function authPage() {

      const params = new URLSearchParams(window.location.search);
      const msg = params.get('msg');
      const error = params.get('error');

      let startLogin = true;
      if (msg === 'email_exists') {
        startLogin = false;
      }
      if (msg === 'registered') {
        startLogin = true;
      }

      return {
        isLogin: startLogin,
        loading: false
      }
    }
  </script>
</body>

</html>