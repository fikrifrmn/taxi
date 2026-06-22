<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CV. Sinar Taxi - Solusi Perjalanan Anda</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script
      defer
      src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"
    ></script>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
  </head>
  <body class="bg-white font-sans overflow-x-hidden">
    <nav
      class="fixed w-full z-50 transition-all duration-300 bg-white/80 backdrop-blur-md shadow-sm"
      x-data="{ open: false }"
    >
      <div
        class="container mx-auto px-6 py-4 flex justify-between items-center"
      >
        <div class="flex items-center space-x-2">
          <div class="bg-yellow-400 p-2 rounded-lg shadow-sm">
            <svg
              width="30"
              height="30"
              viewBox="0 0 24 24"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5H6.5C5.84 5 5.29 5.42 5.08 6.01L3 12V20C3 20.55 3.45 21 4 21H5C5.55 21 6 20.55 6 20V19H18V20C18 20.55 18.45 21 19 21H20C20.55 21 21 20.55 21 20V12L18.92 6.01ZM6.85 7H17.15L18.22 10.12H5.78L6.85 7ZM19 17H5V12H19V17Z"
                fill="#000000"
              />
              <circle cx="7" cy="14.5" r="1.5" fill="#000000" />
              <circle cx="17" cy="14.5" r="1.5" fill="#000000" />
              <path
                d="M5 2L2 5M19 2L22 5"
                stroke="#000000"
                stroke-width="2"
                stroke-linecap="round"
              />
            </svg>
          </div>
          <span class="text-xl font-black italic tracking-tighter text-gray-900"
            >SINAR TAXI</span
          >
        </div>

        <div class="hidden md:flex space-x-8 font-semibold text-gray-700">
          <a href="#home" class="hover:text-yellow-500 transition">Beranda</a>
          <a href="#services" class="hover:text-yellow-500 transition"
            >Layanan</a
          >
          <a href="#destinations" class="hover:text-yellow-500 transition"
            >Destinasi</a
          >
        </div>

        <div class="hidden md:block">
          <a
            href="auth.php"
            class="bg-black text-white px-6 py-2 rounded-full font-bold hover:bg-yellow-400 hover:text-black transition duration-300"
            >Pesan Sekarang</a
          >
        </div>
      </div>
    </nav>

    <section
      id="home"
      class="relative min-h-screen flex items-center pt-20 overflow-hidden bg-yellow-400"
    >
      <div
        class="container mx-auto px-6 grid md:grid-cols-2 gap-12 items-center relative z-10"
      >
        <div class="animate__animated animate__fadeInLeft">
          <span
            class="bg-black text-white px-4 py-1 rounded-full text-sm font-bold tracking-widest uppercase"
            >Travel Terpercaya di Sumatera</span
          >
          <h1
            class="text-5xl md:text-7xl font-black text-gray-900 mt-4 leading-tight"
          >
            Perjalanan Nyaman, Sinar Taxi
            <span class="text-white">Solusinya.</span>
          </h1>
          <p class="text-lg text-gray-800 mt-6 mb-8 max-w-lg">
            Nikmati layanan taxi dan tour premium dengan armada terbaru dan
            supir profesional. Kami siap mengantar Anda ke tujuan dengan aman
            dan tepat waktu.
          </p>
          <div
            class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4"
          >
            <a
              href="auth.php"
              class="bg-black text-white text-center px-8 py-4 rounded-xl font-bold text-lg hover:scale-105 transition-transform shadow-xl"
              >Booking Sekarang</a
            >
            <a
              href="#services"
              class="border-2 border-black text-black text-center px-8 py-4 rounded-xl font-bold text-lg hover:bg-black hover:text-white transition shadow-lg"
              >Lihat Layanan</a
            >
          </div>
        </div>
        <div class="relative animate__animated animate__zoomIn">
          <img
            src="https://images.unsplash.com/photo-1549194388-2469d41e236e?auto=format&fit=crop&q=80&w=800"
            alt="Sinar Taxi Car"
            class="rounded-3xl shadow-2xl rotate-3 hover:rotate-0 transition duration-500 border-8 border-white"
          />
          <div
            class="absolute -bottom-6 -left-6 bg-white p-6 rounded-2xl shadow-xl animate-bounce"
          >
            <p class="text-sm font-bold text-gray-500">Rating Penumpang</p>
            <p class="text-2xl font-black text-yellow-500">4.9/5.0 ⭐</p>
          </div>
        </div>
      </div>
      <div
        class="absolute top-0 right-0 w-1/3 h-full bg-yellow-300 -skew-x-12 translate-x-1/2"
      ></div>
    </section>

    <section id="services" class="py-24 bg-gray-50">
      <div class="container mx-auto px-6">
        <div class="text-center mb-16">
          <h2 class="text-4xl font-black text-gray-900 mb-4">
            Mengapa Memilih Kami?
          </h2>
          <div class="w-20 h-2 bg-yellow-400 mx-auto rounded-full"></div>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
          <div
            class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-2xl hover:-translate-y-2 transition duration-300"
            data-aos="fade-up"
          >
            <div
              class="w-14 h-14 bg-yellow-100 text-yellow-600 rounded-2xl flex items-center justify-center mb-6 text-2xl"
            >
              🛡️
            </div>
            <h3 class="text-xl font-bold mb-3">Keamanan Terjamin</h3>
            <p class="text-gray-500">
              Setiap perjalanan dilengkapi dengan asuransi dan armada yang rutin
              dicek kondisinya.
            </p>
          </div>
          <div
            class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-2xl hover:-translate-y-2 transition duration-300"
            data-aos="fade-up"
            data-aos-delay="100"
          >
            <div
              class="w-14 h-14 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mb-6 text-2xl"
            >
              ⏰
            </div>
            <h3 class="text-xl font-bold mb-3">Tepat Waktu</h3>
            <p class="text-gray-500">
              Kami menghargai waktu Anda. Penjemputan dilakukan tepat waktu
              sesuai jadwal yang disepakati.
            </p>
          </div>
          <div
            class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-2xl hover:-translate-y-2 transition duration-300"
            data-aos="fade-up"
            data-aos-delay="200"
          >
            <div
              class="w-14 h-14 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center mb-6 text-2xl"
            >
              💰
            </div>
            <h3 class="text-xl font-bold mb-3">Harga Kompetitif</h3>
            <p class="text-gray-500">
              Tarif transparan tanpa biaya tersembunyi. Lebih hemat dengan paket
              tour keluarga.
            </p>
          </div>
        </div>
      </div>
    </section>

    <section class="bg-black py-20 text-white">
      <div
        class="container mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-8 text-center"
      >
        <div>
          <p class="text-4xl font-black text-yellow-400">10k+</p>
          <p class="text-gray-400 uppercase text-sm mt-2">Pelanggan Puas</p>
        </div>
        <div>
          <p class="text-4xl font-black text-yellow-400">50+</p>
          <p class="text-gray-400 uppercase text-sm mt-2">Armada Siap</p>
        </div>
        <div>
          <p class="text-4xl font-black text-yellow-400">20+</p>
          <p class="text-gray-400 uppercase text-sm mt-2">Kota Tujuan</p>
        </div>
        <div>
          <p class="text-4xl font-black text-yellow-400">24/7</p>
          <p class="text-gray-400 uppercase text-sm mt-2">Layanan CS</p>
        </div>
      </div>
    </section>
    <section id="destinations" class="py-24 bg-white">
      <div class="container mx-auto px-6">
        <div
          class="flex flex-col md:flex-row justify-between items-end mb-16 gap-4"
        >
          <div>
            <h2 class="text-5xl font-black text-gray-900 leading-none">
              Paket <br /><span class="text-yellow-500"
                >Destinasi Populer.</span
              >
            </h2>
          </div>
          <p class="text-gray-500 max-w-sm font-medium">
            Pilih rute perjalanan Anda dengan harga transparan dan layanan antar
            jemput pintu ke pintu.
          </p>
        </div>

        <div class="grid md:grid-cols-3 gap-10">
          <div
            class="group card-zoom relative overflow-hidden rounded-[2rem] bg-gray-100 h-[450px]"
            data-aos="fade-up"
          >
            <img
              src="https://images.unsplash.com/photo-1623692267429-45330c42bbd9?q=80&w=735&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
              class="absolute inset-0 w-full h-full object-cover transition duration-700 group-hover:scale-110"
              alt="Kota Medan"
            />
            <div
              class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent"
            ></div>
            <div class="absolute bottom-0 p-8 w-full">
              <span
                class="bg-yellow-400 text-black px-3 py-1 rounded-full text-[10px] font-black uppercase mb-3 inline-block"
                >City Tour</span
              >
              <h3 class="text-2xl font-black text-white mb-2">
                Medan City Center
              </h3>
              <p class="text-gray-300 text-sm mb-4">
                Eksplorasi kuliner dan sejarah kota Medan dengan driver lokal
                berpengalaman.
              </p>
              <div
                class="flex justify-between items-center border-t border-white/20 pt-4 text-white"
              >
                <span class="font-bold">Mulai Dari</span>
                <span class="text-xl font-black text-yellow-400">Rp 350rb</span>
              </div>
            </div>
          </div>

          <div
            class="group card-zoom relative overflow-hidden rounded-[2rem] bg-gray-100 h-[450px]"
            data-aos="fade-up"
            data-aos-delay="100"
          >
            <img
              src="http://googleusercontent.com/image_collection/image_retrieval/11465719470322775304_0"
              class="absolute inset-0 w-full h-full object-cover transition duration-700 group-hover:scale-110"
              alt="Danau Toba"
            />
            <div
              class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent"
            ></div>
            <div class="absolute bottom-0 p-8 w-full">
              <span
                class="bg-blue-500 text-white px-3 py-1 rounded-full text-[10px] font-black uppercase mb-3 inline-block"
                >Nature</span
              >
              <h3 class="text-2xl font-black text-white mb-2">
                Danau Toba (Parapat)
              </h3>
              <p class="text-gray-300 text-sm mb-4">
                Perjalanan nyaman menuju keajaiban dunia dengan armada suspensi
                empuk.
              </p>
              <div
                class="flex justify-between items-center border-t border-white/20 pt-4 text-white"
              >
                <span class="font-bold">Mulai Dari</span>
                <span class="text-xl font-black text-yellow-400">Rp 1.2jt</span>
              </div>
            </div>
          </div>

          <div
            class="group card-zoom relative overflow-hidden rounded-[2rem] bg-gray-100 h-[450px]"
            data-aos="fade-up"
            data-aos-delay="200"
          >
            <img
              src="http://googleusercontent.com/image_collection/image_retrieval/12168058581749407133_0"
              class="absolute inset-0 w-full h-full object-cover transition duration-700 group-hover:scale-110"
              alt="Berastagi"
            />
            <div
              class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent"
            ></div>
            <div class="absolute bottom-0 p-8 w-full">
              <span
                class="bg-green-500 text-white px-3 py-1 rounded-full text-[10px] font-black uppercase mb-3 inline-block"
                >Mountain View</span
              >
              <h3 class="text-2xl font-black text-white mb-2">
                Berastagi Highlands
              </h3>
              <p class="text-gray-300 text-sm mb-4">
                Udara sejuk dan pemandangan gunung dengan layanan taxi privat.
              </p>
              <div
                class="flex justify-between items-center border-t border-white/20 pt-4 text-white"
              >
                <span class="font-bold">Mulai Dari</span>
                <span class="text-xl font-black text-yellow-400">Rp 600rb</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="py-24 bg-white">
      <div class="container mx-auto px-6">
        <div
          class="bg-yellow-400 rounded-[3rem] p-12 md:p-20 text-center relative overflow-hidden"
        >
          <div class="relative z-10">
            <h2 class="text-4xl md:text-5xl font-black mb-6">
              Siap Untuk Perjalanan Berikutnya?
            </h2>
            <p class="text-lg font-medium mb-10 opacity-80 max-w-2xl mx-auto">
              Jangan tunda lagi perjalanan Anda. Daftar sekarang dan dapatkan
              diskon 20% untuk perjalanan pertama Anda bersama CV. Sinar Taxi!
            </p>
            <a
              href="auth.php"
              class="bg-black text-white px-10 py-4 rounded-full font-bold text-xl hover:bg-gray-800 transition shadow-2xl inline-block"
              >Mulai Sekarang</a
            >
          </div>
          <div
            class="absolute top-0 left-0 w-32 h-32 bg-white/20 rounded-full -translate-x-1/2 -translate-y-1/2"
          ></div>
          <div
            class="absolute bottom-0 right-0 w-64 h-64 bg-black/5 rounded-full translate-x-1/4 translate-y-1/4"
          ></div>
        </div>
      </div>
    </section>

    <footer class="bg-slate-950 text-gray-400 py-20 border-t border-white/5">
      <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
          <div class="col-span-1 md:col-span-1">
            <div class="flex items-center space-x-3 mb-6">
              <div
                class="bg-yellow-400 p-2 rounded-lg italic font-black text-black text-sm"
              >
                ST
              </div>
              <span
                class="text-2xl font-black italic tracking-tighter text-white"
                >SINAR TAXI</span
              >
            </div>
            <p class="text-sm leading-relaxed mb-6">
              Menghadirkan standar baru dalam perjalanan travel dan taxi.
              Keamanan Anda adalah prioritas, kenyamanan Anda adalah komitmen
              kami.
            </p>
            <div class="flex space-x-4">
              <a
                href="#"
                class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-yellow-400 hover:text-black transition-all duration-300"
              >
                <i class="fab fa-facebook-f"></i>
              </a>
              <a
                href="#"
                class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-yellow-400 hover:text-black transition-all duration-300"
              >
                <i class="fab fa-instagram"></i>
              </a>
              <a
                href="#"
                class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-yellow-400 hover:text-black transition-all duration-300"
              >
                <i class="fab fa-whatsapp"></i>
              </a>
            </div>
          </div>

          <div>
            <h4
              class="text-white font-bold uppercase tracking-widest text-xs mb-6"
            >
              Navigasi
            </h4>
            <ul class="space-y-4 text-sm font-medium">
              <li>
                <a href="#home" class="hover:text-yellow-400 transition"
                  >Beranda</a
                >
              </li>
              <li>
                <a href="#services" class="hover:text-yellow-400 transition"
                  >Layanan Kami</a
                >
              </li>
              <li>
                <a href="#destinations" class="hover:text-yellow-400 transition"
                  >Destinasi Populer</a
                >
              </li>
              <li>
                <a href="#" class="hover:text-yellow-400 transition"
                  >Syarat & Ketentuan</a
                >
              </li>
            </ul>
          </div>

          <div>
            <h4
              class="text-white font-bold uppercase tracking-widest text-xs mb-6"
            >
              Kontak Kami
            </h4>
            <ul class="space-y-4 text-sm">
              <li class="flex items-start space-x-3">
                <span class="text-yellow-400"
                  ><i class="fas fa-map-marker-alt"></i
                ></span>
                <span
                  >Jl. Raya Lintas Sumatera No. 123, Medan, Sumatera
                  Utara.</span
                >
              </li>
              <li class="flex items-center space-x-3">
                <span class="text-yellow-400"
                  ><i class="fas fa-phone-alt"></i
                ></span>
                <span>+62 812 3456 7890</span>
              </li>
              <li class="flex items-center space-x-3">
                <span class="text-yellow-400"
                  ><i class="fas fa-envelope"></i
                ></span>
                <span>admin@sinartaxi.com</span>
              </li>
            </ul>
          </div>

          <div>
            <h4
              class="text-white font-bold uppercase tracking-widest text-xs mb-6"
            >
              Dapatkan Promo
            </h4>
            <p class="text-xs mb-4">
              Dapatkan info diskon paket tour langsung di email Anda.
            </p>
            <div class="flex">
              <input
                type="email"
                placeholder="Email Anda"
                class="bg-white/5 border border-white/10 px-4 py-2 rounded-l-lg focus:outline-none focus:border-yellow-400 w-full text-sm"
              />
              <button
                class="bg-yellow-400 text-black px-4 py-2 rounded-r-lg hover:bg-yellow-500 transition"
              >
                <i class="fas fa-paper-plane"></i>
              </button>
            </div>
          </div>
        </div>

        <div
          class="pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-4 text-xs font-semibold tracking-wider"
        >
          <p>© 2026 CV. SINAR TAXI. MADE WITH PRIDE IN SUMATERA.</p>
          <div class="flex space-x-6">
            <a href="#" class="hover:text-white transition">PRIVACY POLICY</a>
            <a href="#" class="hover:text-white transition">COOKIES</a>
          </div>
        </div>
      </div>
    </footer>

    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
      AOS.init({
        duration: 1000,
        once: true,
      });
    </script>
  </body>
</html>
